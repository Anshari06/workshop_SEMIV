<?php

namespace App\Http\Controllers;

use App\Models\LokasiToko;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Http\Request;

class TokoController extends Controller
{
    public function index()
    {
        $tokos = LokasiToko::orderBy('nama_toko')->get();

        $writerSvg = new SvgWriter();
        $tokos->transform(function ($toko) use ($writerSvg) {
            $qrCode = new QrCode(
                data: $toko->barcode,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::Medium,
                size: 150,
                margin: 5
            );
            $result = $writerSvg->write($qrCode);
            $toko->qr_svg = $result->getString();
            return $toko;
        });

        return view('Toko.index', compact('tokos'));
    }

    public function create()
    {
        $lastToko = LokasiToko::orderBy('barcode', 'desc')->first();
        $nextNum = 1;
        if ($lastToko && preg_match('/TOKO(\d+)/', $lastToko->barcode, $m)) {
            $nextNum = (int) $m[1] + 1;
        }
        $nextBarcode = 'TOKO' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);

        return view('Toko.create', compact('nextBarcode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|string|max:8|unique:lokasi_toko,barcode',
            'nama_toko' => 'required|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'required|numeric|min:1',
        ]);

        LokasiToko::create($validated);

        return redirect()->route('toko.index')->with('success', 'Data toko berhasil ditambahkan.');
    }

    public function edit(string $barcode)
    {
        $toko = LokasiToko::findOrFail($barcode);
        return view('Toko.edit', compact('toko'));
    }

    public function update(Request $request, string $barcode)
    {
        $toko = LokasiToko::findOrFail($barcode);

        $validated = $request->validate([
            'nama_toko' => 'required|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'required|numeric|min:1',
        ]);

        $toko->update($validated);

        return redirect()->route('toko.index')->with('success', 'Data toko berhasil diperbarui.');
    }

    public function destroy(string $barcode)
    {
        LokasiToko::destroy($barcode);
        return redirect()->route('toko.index')->with('success', 'Toko berhasil dihapus.');
    }
}