<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\detail_pesanan;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScannerVendor extends Controller
{
    public function index()
    {
        return view('Vendor.ScannerVendor');
    }

    public function showOrderFromQr($pesananId)
    {
        $vendor = Vendor::where('id_user', Auth::id())->firstOrFail();

        $orderItems = detail_pesanan::where('id_pesanan', (int) $pesananId)
            ->whereHas('menu', function ($query) use ($vendor) {
                $query->where('id_vendor', $vendor->id);
            })
            ->with(['menu', 'pesanan'])
            ->get();

        if ($orderItems->isEmpty()) {
            return response()->json([
                'message' => 'Pesanan tidak ditemukan untuk vendor ini.',
            ], 404);
        }

        $pesanan = $orderItems->first()->pesanan;
        $statusCode = (int) ($pesanan->status ?? 0);

        $statusText = match ($statusCode) {
            1 => 'Lunas',
            2 => 'Gagal / Dibatalkan',
            default => 'Belum Lunas',
        };

        $statusClass = match ($statusCode) {
            1 => 'success',
            2 => 'danger',
            default => 'warning',
        };

        $items = $orderItems->map(function ($item) {
            return [
                'menu' => $item->menu->nama_menu ?? '-',
                'qty' => (int) $item->jumlah,
                'harga' => (int) ($item->menu->harga ?? 0),
                'subtotal' => (int) $item->subtotal,
            ];
        })->values();

        return response()->json([
            'order' => [
                'id' => (int) $pesanan->id,
                'kode' => 'INV-' . str_pad((string) $pesanan->id, 6, '0', STR_PAD_LEFT),
                'customer_name' => $pesanan->nama,
                'status_code' => $statusCode,
                'status_text' => $statusText,
                'status_class' => $statusClass,
                'metode_pembayaran' => (int) $pesanan->metode_pembayaran === 1 ? 'Midtrans' : 'Belum ditentukan',
                'total' => (int) $items->sum('subtotal'),
                'total_item' => (int) $items->sum('qty'),
                'items' => $items,
            ],
        ]);
    }
}
