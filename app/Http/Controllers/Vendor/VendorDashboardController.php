<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\detail_pesanan;
use App\Models\menu;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;

class VendorDashboardController extends Controller
{
    public function index()
    {
        $data = $this->getVendorDashboardData();

        return view('Vendor.dashboard', $data);
    }

    public function pesanan()
    {
        $data = $this->getVendorDashboardData();

        $pesananGroups = $data['pesanan']
            ->groupBy('id_pesanan')
            ->map(function ($items) {
                $firstItem = $items->first();

                return [
                    'pesanan_id' => $firstItem->id_pesanan,
                    'kode' => '#'.str_pad((string) $firstItem->id_pesanan, 6, '0', STR_PAD_LEFT),
                    'customer' => $firstItem->pesanan->nama ?? 'Guest',
                    'status' => (int) ($firstItem->pesanan->status ?? 0),
                    'created_at' => $firstItem->pesanan->created_at,
                    'item_count' => $items->sum('jumlah'),
                    'subtotal' => $items->sum('subtotal'),
                    'items' => $items,
                ];
            })
            ->sortByDesc('created_at')
            ->values();

        return view('Vendor.Pesanan', array_merge($data, [
            'pesananGroups' => $pesananGroups,
        ]));
    }

    public function menu()
    {
        return redirect()->route('vendor.dashboard');
    }

    private function getVendorDashboardData(): array
    {
        $userId = Auth::id();
        $vendor = Vendor::where('id_user', $userId)->firstOrFail();

        $menus = menu::where('id_vendor', $vendor->id)
            ->select('id', 'nama_menu', 'harga', 'path_gambar')
            ->get();

        $pesanan = detail_pesanan::whereHas('menu.vendor', function ($query) use ($vendor) {
            $query->where('id', $vendor->id);
        })->with(['pesanan', 'menu'])->get();

        $paidCount = $pesanan->where('pesanan.status', 1)->count();
        $pendingCount = $pesanan->where('pesanan.status', 0)->count();
        $totalRevenue = $pesanan->filter(function ($item) {
            return $item->pesanan && (int) $item->pesanan->status === 1;
        })->sum('subtotal');

        return [
            'vendor' => $vendor,
            'menus' => $menus,
            'pesanan' => $pesanan,
            'paidCount' => $paidCount,
            'pendingCount' => $pendingCount,
            'totalOrders' => $pesanan->count(),
            'totalRevenue' => $totalRevenue,
            'user' => Auth::user(),
        ];
    }
}