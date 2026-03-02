<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $buku = Buku::all();
        return view('admin.buku.buku', compact('buku'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = Kategori::all();
        return view('admin.buku.create', compact('kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'idkategori' => 'required|exists:kategori,idkategori',
            'pengarang' => 'required|string|max:255',
        ]);

        // Ambil kategori yang dipilih
        $kategori = Kategori::findOrFail($request->idkategori);
        
        // Hitung jumlah buku dalam kategori ini
        $jumlahBuku = Buku::where('idkategori', $request->idkategori)->count();
        
        // Generate kode buku
        // Ambil 2 huruf pertama dari nama kategori dan ubah ke uppercase
        $singkatan = strtoupper(substr($kategori->nama_kategori, 0, 1) . substr($kategori->nama_kategori, 4, 1));
        $nomorUrut = $jumlahBuku + 1;
        $kodeBuku = $singkatan . '-' . $nomorUrut;

        Buku::create([
            'kode' => $kodeBuku,
            'judul' => $request->judul,
            'idkategori' => $request->idkategori,
            'pengarang' => $request->pengarang,
        ]);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $buku = Buku::findOrFail($id);
        $kategori = Kategori::all();
        return view('admin.buku.edit', compact('buku', 'kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $buku = Buku::findOrFail($id);
        
        $request->validate([
            'judul' => 'required|string|max:255',
            'idkategori' => 'required|exists:kategori,idkategori',
            'pengarang' => 'required|string|max:255',
        ]);

        $buku->update([
            'judul' => $request->judul,
            'idkategori' => $request->idkategori,
            'pengarang' => $request->pengarang,
        ]);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus!');
    }
}
