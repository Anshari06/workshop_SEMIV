<?php

namespace App\Http\Controllers;

use App\Models\pesanan;
use App\Models\detail_pesanan;
use App\Models\menu;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Store a newly created order in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendor,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'items' => 'required|array|min:1',
            'items.*' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Calculate total dari items yang dipilih
            $total = 0;
            foreach ($request->items as $menuId => $qty) {
                $menu = menu::findOrFail($menuId);
                $subtotal = $menu->harga * $qty;
                $total += $subtotal;
            }

            // Create pesanan
            $pesanan = pesanan::create([
                'nama' => $request->customer_name,
                'total' => $total,
                'metode_pembayaran' => 0, // 0 = belum dipilih
                'status' => 0, // 0 = pending
            ]);

            // Create detail_pesanan untuk setiap item
            foreach ($request->items as $menuId => $qty) {
                $menu = menu::findOrFail($menuId);
                $subtotal = $menu->harga * $qty;

                detail_pesanan::create([
                    'id_pesanan' => $pesanan->id,
                    'id_menu' => $menuId,
                    'jumlah' => $qty,
                    'subtotal' => $subtotal,
                    'catatan' => null,
                ]);
            }

            DB::commit();

            // Redirect ke halaman payment dengan data pesanan
            return redirect()->route('payment.show', ['pesanan' => $pesanan->id])
                ->with('success', 'Pesanan berhasil dibuat. Silakan lanjut ke pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat membuat pesanan: ' . $e->getMessage()]);
        }
    }

    /**
     * Show payment page for a specific order.
     *
     * @param  \App\Models\pesanan  $pesanan
     * @return \Illuminate\Http\Response
     */
    public function showPayment(pesanan $pesanan)
    {
        // Load detail pesanan dengan relasi menu
        $pesanan->load('detail_pesanans.menu');

        // Prepare data untuk template payment
        $orderCode = 'INV-' . str_pad($pesanan->id, 6, '0', STR_PAD_LEFT);
        $customerName = $pesanan->nama;
        $orderDate = $pesanan->created_at->format('d M Y H:i');
        $subtotal = $pesanan->total;
        $serviceFee = 2000;
        $total = $pesanan->total + $serviceFee;

        // Transform detail_pesanan ke format untuk template
        $items = $pesanan->detail_pesanans->map(function ($detail) {
            return [
                'nama_menu' => $detail->menu->nama_menu,
                'qty' => $detail->jumlah,
                'harga' => $detail->menu->harga,
            ];
        })->toArray();

        return view('payment.show', compact(
            'orderCode',
            'customerName',
            'orderDate',
            'subtotal',
            'serviceFee',
            'total',
            'items',
            'pesanan'
        ));
    }
}
