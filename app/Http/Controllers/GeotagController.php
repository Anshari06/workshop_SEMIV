<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\LokasiToko;
use Illuminate\Http\Request;

class GeotagController extends Controller
{
    public function index()
    {
        return view('Geotag.GeotagPage');
    }

    public function getTokos()
    {
        $tokos = LokasiToko::orderBy('nama_toko')->get();

        return response()->json([
            'status' => true,
            'data' => $tokos->map(fn($t) => [
                'barcode' => $t->barcode,
                'nama_toko' => $t->nama_toko,
                'latitude' => (float) $t->latitude,
                'longitude' => (float) $t->longitude,
                'accuracy' => (float) $t->accuracy,
            ]),
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string|max:8',
        ]);

        $toko = LokasiToko::where('barcode', $request->barcode)->first();

        if (!$toko) {
            return response()->json([
                'status' => false,
                'message' => 'Toko tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'barcode' => $toko->barcode,
                'nama_toko' => $toko->nama_toko,
                'latitude' => (float) $toko->latitude,
                'longitude' => (float) $toko->longitude,
                'accuracy' => (float) $toko->accuracy,
            ],
        ]);
    }

    public function storeKunjungan(Request $request)
    {
        try {
            $validated = $request->validate([
                'barcode_toko' => 'required|string|max:8',
                'nama_toko' => 'required|string|max:100',
                'lat_toko' => 'required|numeric',
                'lng_toko' => 'required|numeric',
                'accuracy_toko' => 'required|numeric',
                'lat_sales' => 'required|numeric',
                'lng_sales' => 'required|numeric',
                'accuracy_sales' => 'required|numeric',
                'jarak' => 'required|numeric',
                'threshold' => 'required|numeric',
                'threshold_efektif' => 'required|numeric',
                'status' => 'required|in:diterima,ditolak',
            ]);

            $kunjungan = Kunjungan::create($validated);

            return response()->json([
                'status' => true,
                'message' => 'Kunjungan berhasil disimpan.',
                'data' => $kunjungan,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan kunjungan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function riwayat()
    {
        $kunjungans = Kunjungan::orderByDesc('created_at')->get();
        return view('Geotag.RiwayatKunjungan', compact('kunjungans'));
    }
}