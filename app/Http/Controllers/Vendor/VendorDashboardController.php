<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\detail_pesanan;
use App\Models\menu;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    public function pesananDetail($pesananId)
    {
        $data = $this->getVendorDashboardData();

        $orderItems = $data['pesanan']
            ->where('id_pesanan', (int) $pesananId)
            ->values();

        if ($orderItems->isEmpty()) {
            abort(404, 'Pesanan tidak ditemukan untuk vendor ini.');
        }

        $firstItem = $orderItems->first();
        $order = [
            'pesanan_id' => (int) $pesananId,
            'kode' => '#'.str_pad((string) $pesananId, 6, '0', STR_PAD_LEFT),
            'customer' => $firstItem->pesanan->nama ?? 'Guest',
            'status' => (int) ($firstItem->pesanan->status ?? 0),
            'created_at' => $firstItem->pesanan->created_at,
            'item_count' => $orderItems->sum('jumlah'),
            'subtotal' => $orderItems->sum('subtotal'),
            'items' => $orderItems,
        ];

        return view('Vendor.PesananDetail', array_merge($data, [
            'order' => $order,
        ]));
    }

    public function menu()
    {
        $data = $this->getVendorDashboardData();

        return view('Vendor.Menu', $data);
    }

    public function menuStore(Request $request)
    {
        $vendor = $this->getAuthVendor();

        $validated = $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
            'path_gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('path_gambar')) {
            $imagePath = $request->file('path_gambar')->store('menus', 'public');
        }

        menu::create([
            'nama_menu' => $validated['nama_menu'],
            'harga' => (int) $validated['harga'],
            'path_gambar' => $imagePath,
            'id_vendor' => $vendor->id,
        ]);

        return redirect()->route('vendor.menu')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function menuUpdate(Request $request, $menuId)
    {
        $vendor = $this->getAuthVendor();
        $menuItem = menu::where('id', $menuId)->where('id_vendor', $vendor->id)->firstOrFail();

        $validated = $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
            'path_gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('path_gambar')) {
            $this->deleteStoredImageIfLocal($menuItem->path_gambar);
            $menuItem->path_gambar = $request->file('path_gambar')->store('menus', 'public');
        }

        $menuItem->nama_menu = $validated['nama_menu'];
        $menuItem->harga = (int) $validated['harga'];
        $menuItem->save();

        return redirect()->route('vendor.menu')->with('success', 'Menu berhasil diperbarui.');
    }

    public function menuDelete($menuId)
    {
        $vendor = $this->getAuthVendor();
        $menuItem = menu::where('id', $menuId)->where('id_vendor', $vendor->id)->firstOrFail();

        $this->deleteStoredImageIfLocal($menuItem->path_gambar);
        $menuItem->delete();

        return redirect()->route('vendor.menu')->with('success', 'Menu berhasil dihapus.');
    }

    private function getVendorDashboardData(): array
    {
        $vendor = $this->getAuthVendor();

        $menus = menu::where('id_vendor', $vendor->id)
            ->select('id', 'nama_menu', 'harga', 'path_gambar')
            ->get()
            ->map(function ($item) {
                $item->image_url = $this->resolveMenuImageUrl($item->path_gambar);
                return $item;
            });

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

    private function getAuthVendor(): Vendor
    {
        $userId = Auth::id();

        return Vendor::where('id_user', $userId)->firstOrFail();
    }

    private function deleteStoredImageIfLocal(?string $path): void
    {
        if (! $path) {
            return;
        }

        $normalized = ltrim($path, '/');

        if (str_starts_with($normalized, 'menus/')) {
            Storage::disk('public')->delete($normalized);
            return;
        }

        if (str_starts_with($normalized, 'storage/menus/')) {
            Storage::disk('public')->delete(substr($normalized, strlen('storage/')));
        }
    }

    private function resolveMenuImageUrl(?string $path): string
    {
        if (! $path) {
            return asset('assets/images/apple.png');
        }

        $normalized = ltrim($path, '/');

        if (str_starts_with($normalized, 'http://') || str_starts_with($normalized, 'https://')) {
            return $normalized;
        }

        if (str_starts_with($normalized, 'storage/')) {
            return asset($normalized);
        }

        if (str_starts_with($normalized, 'assets/')) {
            return asset($normalized);
        }

        return asset('storage/'.$normalized);
    }
}