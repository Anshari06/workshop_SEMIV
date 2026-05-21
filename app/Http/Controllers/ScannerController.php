<?php

namespace App\Http\Controllers;

use App\Models\Barang;

class ScannerController extends Controller
{
    public function index()
    {
        $barangList = Barang::query()
            ->select('id_barang', 'nama_barang', 'harga')
            ->orderBy('nama_barang')
            ->get();

        return view('scanner.indexScanner', compact('barangList'));
    }
}
