<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Picqer\Barcode\BarcodeGeneratorSVG;

class BarcodeController extends Controller
{
    public function index()
    {
        $products = Barang::orderBy('nama_barang')->get();

        $generator = null;
        if (class_exists(BarcodeGeneratorSVG::class)) {
            $generator = new BarcodeGeneratorSVG();
        }

        $products->transform(function ($product) use ($generator) {
            $product->barcode_svg = null;

            if ($generator) {
                $product->barcode_svg = $generator->getBarcode(
                    (string) $product->id_barang,
                    $generator::TYPE_CODE_128,
                    2,
                    55
                );
            }

            return $product;
        });

        return view('barcode.index', compact('products'));
    }

    public function generate(Request $request)
    {
        return redirect()->route('barcode.index');
    }

    public function print(Barang $barang)
    {
        $barcodePngBase64 = null;

        if (class_exists(BarcodeGeneratorPNG::class)) {
            $generator = new BarcodeGeneratorPNG();
            $barcodeBinary = $generator->getBarcode((string) $barang->id_barang, $generator::TYPE_CODE_128);
            $barcodePngBase64 = base64_encode($barcodeBinary);
        }

        $pdf = Pdf::loadView('barcode.pdf', [
            'barang' => $barang,
            'barcodePngBase64' => $barcodePngBase64,
        ])->setPaper([0, 0, 255.12, 155.91], 'portrait');

        return $pdf->stream('barcode-'.$barang->id_barang.'.pdf');
    }
}
