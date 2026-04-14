<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\detail_pesanan;
use App\Models\menu;
use App\Models\pesanan;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Keranjang extends Controller
{
    private const CART_SESSION_KEY = 'shopping_cart';

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

    private function recalculateVendorSubtotal(array $items): int
    {
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += (int) (($item['harga'] ?? 0) * ($item['qty'] ?? 0));
        }

        return $subtotal;
    }
}
