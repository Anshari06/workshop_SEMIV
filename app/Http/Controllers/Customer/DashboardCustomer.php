<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\menu;

class DashboardCustomer extends Controller
{
    /**
     * Display customer dashboard with vendor list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $vendors = Vendor::with('menus')->get();

        return view('Customer.dashboard', compact('vendors'));
    }

    /**
     * Get menu by vendor ID for AJAX.
     *
     * @param  int  $vendorId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMenuByVendor($vendorId)
    {
        $menus = menu::where('id_vendor', $vendorId)
            ->select('id', 'nama_menu', 'harga', 'path_gambar')
            ->get();

        return response()->json($menus);
    }

    /**
     * Display menu page for selected vendor.
     *
     * @param  int  $vendorId
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showMenu($vendorId)
    {
        $vendor = Vendor::with('menus')->findOrFail($vendorId);

        return view('Customer.Menu', compact('vendor'));
    }
}
