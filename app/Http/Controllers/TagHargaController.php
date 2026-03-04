<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;

class TagHargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Barang::all();
        return view('Cetak.tag-harga.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Cetak.tag-harga.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:191',
            'harga' => 'required|numeric|min:0',
        ]);

        Barang::create([
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga,
        ]);

        return redirect()->route('tag-harga.index')->with('success', 'Barang berhasil ditambahkan');
    }

    // public function show(string $id)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Barang::findOrFail($id);
        return view('Cetak.tag-harga.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Barang::findOrFail($id);

        $request->validate([
            'nama_barang' => 'required|string|max:191|unique:barang,nama_barang,' . $id . ',id_barang',
            'harga' => 'required|numeric|min:0',
        ]);

        $product->update([
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga,
        ]);

        return redirect()->route('tag-harga.index')->with('success', 'Barang berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Barang::findOrFail($id);
        $product->delete();

        return redirect()->route('tag-harga.index')->with('success', 'Barang berhasil dihapus');
    }

    public function print(Request $request)
    {
        $request->validate([
            'selected_barang' => 'required|array|min:1',
            'x' => 'required|integer|min:1|max:5',
            'y' => 'required|integer|min:1|max:8',
        ]);

        $x = (int) $request->x;
        $y = (int) $request->y;

        $startPosition = ($y - 1) * 5 + $x;

        $dataBarang = Barang::whereIn('id_barang', $request->selected_barang)
            ->get()
            ->values()
            ->all();

        $pdf = Pdf::loadView('Cetak.tag-harga.label', [
            'dataBarang' => $dataBarang,
            'startPosition' => $startPosition,
        ])->setPaper('A4', 'landscape');

        return $pdf->stream('tag-harga.pdf');
    }
}
