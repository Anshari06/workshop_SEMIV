<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\detail_pesanan;
use App\Models\menu;
use App\Models\pesanan;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config as MidtransConfig;
use Midtrans\Notification;
use Midtrans\Snap;
use Midtrans\Transaction;

class Keranjang extends Controller
{
    private const CART_SESSION_KEY = 'shopping_cart';

    private function initMidtrans(): void
    {
        MidtransConfig::$serverKey = config('services.midtrans.server_key');
        MidtransConfig::$isProduction = (bool) config('services.midtrans.is_production', false);
        MidtransConfig::$isSanitized = (bool) config('services.midtrans.is_sanitized', true);
        MidtransConfig::$is3ds = (bool) config('services.midtrans.is_3ds', true);
    }

    public function index()
    {
        $cart = session(self::CART_SESSION_KEY, []);

        $grandTotal = 0;
        foreach ($cart as $vendorCart) {
            $grandTotal += $vendorCart['subtotal'] ?? 0;
        }

        return view('Customer.Keranjang', [
            'cart' => $cart,
            'grandTotal' => $grandTotal,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendor,id',
            'items' => 'required|array|min:1',
            'items.*' => 'required|integer|min:1',
        ]);

        $vendor = Vendor::findOrFail($validated['vendor_id']);
        $menuIds = array_keys($validated['items']);

        $menus = menu::where('id_vendor', $vendor->id)
            ->whereIn('id', $menuIds)
            ->get()
            ->keyBy('id');

        if ($menus->isEmpty()) {
            return back()->withErrors(['error' => 'Menu vendor tidak valid.']);
        }

        $vendorItems = [];
        $vendorSubtotal = 0;

        foreach ($validated['items'] as $menuId => $qty) {
            if (! isset($menus[$menuId])) {
                continue;
            }

            $menu = $menus[$menuId];
            $lineSubtotal = $menu->harga * $qty;
            $vendorSubtotal += $lineSubtotal;

            $vendorItems[$menuId] = [
                'menu_id' => (int) $menu->id,
                'nama_menu' => $menu->nama_menu,
                'harga' => (int) $menu->harga,
                'qty' => (int) $qty,
                'subtotal' => (int) $lineSubtotal,
                'path_gambar' => $menu->path_gambar,
            ];
        }

        if (empty($vendorItems)) {
            return back()->withErrors(['error' => 'Tidak ada item valid untuk disimpan ke keranjang.']);
        }

        $cart = session(self::CART_SESSION_KEY, []);
        $cart[$vendor->id] = [
            'vendor_id' => (int) $vendor->id,
            'vendor_name' => $vendor->nama_vendor,
            'items' => $vendorItems,
            'subtotal' => (int) $vendorSubtotal,
        ];

        session([self::CART_SESSION_KEY => $cart]);

        return redirect()->route('customer.menu', $vendor->id)
            ->with('success', 'Keranjang vendor berhasil disimpan. Kamu bisa lanjut vendor lain atau buka shopping cart.');
    }

    public function updateItem(Request $request, $vendorId, $menuId)
    {
        $validated = $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        $cart = session(self::CART_SESSION_KEY, []);
        if (! isset($cart[$vendorId]['items'][$menuId])) {
            return back()->withErrors(['error' => 'Item tidak ditemukan di keranjang.']);
        }

        $item = $cart[$vendorId]['items'][$menuId];
        $item['qty'] = (int) $validated['qty'];
        $item['subtotal'] = (int) ($item['harga'] * $item['qty']);
        $cart[$vendorId]['items'][$menuId] = $item;
        $cart[$vendorId]['subtotal'] = $this->recalculateVendorSubtotal($cart[$vendorId]['items']);

        session([self::CART_SESSION_KEY => $cart]);

        return redirect()->route('customer.cart')->with('success', 'Jumlah item keranjang berhasil diperbarui.');
    }

    public function removeItem($vendorId, $menuId)
    {
        $cart = session(self::CART_SESSION_KEY, []);

        if (isset($cart[$vendorId]['items'][$menuId])) {
            unset($cart[$vendorId]['items'][$menuId]);

            if (empty($cart[$vendorId]['items'])) {
                unset($cart[$vendorId]);
            } else {
                $cart[$vendorId]['subtotal'] = $this->recalculateVendorSubtotal($cart[$vendorId]['items']);
            }

            session([self::CART_SESSION_KEY => $cart]);
        }

        return redirect()->route('customer.cart')->with('success', 'Item dihapus dari keranjang.');
    }

    public function removeVendor($vendorId)
    {
        $cart = session(self::CART_SESSION_KEY, []);

        if (isset($cart[$vendorId])) {
            unset($cart[$vendorId]);
            session([self::CART_SESSION_KEY => $cart]);
        }

        return redirect()->route('customer.cart')->with('success', 'Keranjang vendor berhasil dihapus.');
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        $cart = session(self::CART_SESSION_KEY, []);
        if (empty($cart)) {
            return redirect()->route('customer.cart')->withErrors(['error' => 'Keranjang masih kosong.']);
        }

        DB::beginTransaction();
        try {
            $grandTotal = 0;
            foreach ($cart as $vendorCart) {
                $grandTotal += $this->recalculateVendorSubtotal($vendorCart['items']);
            }

            if ($grandTotal <= 0) {
                DB::rollBack();
                return redirect()->route('customer.cart')->withErrors(['error' => 'Total checkout tidak valid.']);
            }

            $pesanan = pesanan::create([
                'nama' => $validated['customer_name'],
                'total' => $grandTotal,
                'metode_pembayaran' => 0,
                'status' => 0,
            ]);

            foreach ($cart as $vendorCart) {
                foreach ($vendorCart['items'] as $item) {
                    detail_pesanan::create([
                        'id_pesanan' => $pesanan->id,
                        'id_menu' => $item['menu_id'],
                        'jumlah' => $item['qty'],
                        'subtotal' => $item['subtotal'],
                        'catatan' => null,
                    ]);
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('customer.cart')
                ->withErrors(['error' => 'Checkout gagal: ' . $e->getMessage()]);
        }

        session()->forget(self::CART_SESSION_KEY);

        return redirect()->route('payment.show', ['pesanan' => $pesanan->id])
            ->with('success', 'Checkout berhasil. Silakan lanjut pembayaran.');
    }

    public function showPayment(pesanan $pesanan)
    {
        $this->initMidtrans();

        $pesanan->load('detail_pesanans.menu');

        $orderCode = 'INV-' . str_pad($pesanan->id, 6, '0', STR_PAD_LEFT);
        $customerName = $pesanan->nama;
        $orderDate = $pesanan->created_at ? $pesanan->created_at->format('d M Y H:i') : now()->format('d M Y H:i');
        $subtotal = $pesanan->total;
        $serviceFee = 2000;
        $total = $pesanan->total + $serviceFee;

        $items = $pesanan->detail_pesanans->map(function ($detail) {
            return [
                'nama_menu' => $detail->menu->nama_menu ?? '-',
                'qty' => (int) $detail->jumlah,
                'harga' => (int) ($detail->menu->harga ?? 0),
            ];
        })->toArray();

        $midtransOrderId = 'ORDER-' . $pesanan->id . '-' . now()->format('YmdHis');

        $snapPayload = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => (int) $total,
            ],
            'customer_details' => [
                'first_name' => $customerName,
                'phone' => '-',
            ],
            'item_details' => $pesanan->detail_pesanans->map(function ($detail) {
                return [
                    'id' => 'menu-' . $detail->id_menu,
                    'price' => (int) ($detail->menu->harga ?? 0),
                    'quantity' => (int) $detail->jumlah,
                    'name' => $detail->menu->nama_menu ?? 'Item',
                ];
            })->toArray(),
        ];

        if ($serviceFee > 0) {
            $snapPayload['item_details'][] = [
                'id' => 'service-fee',
                'price' => (int) $serviceFee,
                'quantity' => 1,
                'name' => 'Biaya Layanan',
            ];
        }
    
        $snapToken = Snap::getSnapToken($snapPayload);
        $midtransClientKey = config('services.midtrans.client_key');

        return view('payment.show', compact(
            'orderCode',
            'customerName',
            'orderDate',
            'subtotal',
            'serviceFee',
            'total',
            'items',
            'pesanan',
            'snapToken',
            'midtransClientKey'
        ));
    }

    public function midtransCallback(Request $request)
    {
        $this->initMidtrans();

        try {
            $notification = new Notification();
        } catch (\Throwable $e) {
            Log::error('Midtrans callback parse error', ['message' => $e->getMessage()]);
            return response()->json(['message' => 'Invalid notification'], 400);
        }

        $transactionStatus = $notification->transaction_status ?? null;
        $orderId = $notification->order_id ?? null;

        if (! $orderId || ! str_starts_with($orderId, 'ORDER-')) {
            return response()->json(['message' => 'Unknown order id'], 400);
        }

        $segments = explode('-', $orderId);
        $pesananId = isset($segments[1]) ? (int) $segments[1] : 0;
        $pesanan = pesanan::find($pesananId);

        if (! $pesanan) {
            return response()->json(['message' => 'Pesanan not found'], 404);
        }

        switch ($transactionStatus) {
            case 'capture':
            case 'settlement':
                $pesanan->status = 1;
                $pesanan->metode_pembayaran = 1;
                break;
            case 'pending':
                $pesanan->status = 0;
                break;
            case 'deny':
            case 'cancel':
            case 'expire':
                $pesanan->status = 2;
                break;
            default:
                Log::warning('Midtrans unknown status', ['status' => $transactionStatus, 'order_id' => $orderId]);
                break;
        }

        $pesanan->save();

        return response()->json(['message' => 'OK']);
    }

    public function confirmPayment(Request $request, pesanan $pesanan)
    {
        $validated = $request->validate([
            'order_id' => 'required|string',
        ]);

        $this->initMidtrans();

        try {
            $statusResponse = Transaction::status($validated['order_id']);
        } catch (\Throwable $e) {
            Log::error('Midtrans status check failed', [
                'pesanan_id' => $pesanan->id,
                'order_id' => $validated['order_id'],
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Gagal verifikasi status pembayaran ke Midtrans.',
            ], 500);
        }

        $transactionStatus = $statusResponse->transaction_status ?? null;

        switch ($transactionStatus) {
            case 'capture':
            case 'settlement':
                $pesanan->status = 1;
                $pesanan->metode_pembayaran = 1;
                break;
            case 'pending':
                $pesanan->status = 0;
                break;
            case 'deny':
            case 'cancel':
            case 'expire':
                $pesanan->status = 2;
                break;
            default:
                Log::warning('Midtrans confirm unknown status', [
                    'pesanan_id' => $pesanan->id,
                    'order_id' => $validated['order_id'],
                    'status' => $transactionStatus,
                ]);
                break;
        }

        $pesanan->save();

        return response()->json([
            'message' => 'Status pembayaran berhasil diperbarui.',
            'status' => (int) $pesanan->status,
        ]);
    }

    private function recalculateVendorSubtotal(array $items): int
    {
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += (int) (($item['harga'] ?? 0) * ($item['qty'] ?? 0));
        }

        return $subtotal;
    }
}
