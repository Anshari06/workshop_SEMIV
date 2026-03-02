<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class CetakController extends Controller
{
    public function cetakSertif()
    {
        $data = [
            'nama' => 'Anshari',
            'judul' => 'Workshop Laravel'
        ];

        return view('pdf.sertifikat', $data);
    }

    public function downloadSertif()
    {
        $data = [
            'nama' => 'Anshari',
            'judul' => 'Workshop Laravel',
            'isPdf' => true,
        ];

        return Pdf::loadView('pdf.sertifikat', $data)
            ->setPaper('a4', 'landscape')
            ->download('sertifikat.pdf');
    }

    public function cetakUndangan()
    {
        $data = [
            'nama' => 'Anshari',
            'judul' => 'Workshop Laravel',
            'tanggal' => Carbon::now()->format('d F Y')
        ];

        return view('pdf.undangan', $data);
    }

    public function downloadUndangan()
    {
        $data = [
            'nama' => 'Anshari',
            'judul' => 'Workshop Laravel',
            'tanggal' => Carbon::now()->format('d F Y'),
            'isPdf' => true,
        ];

        return Pdf::loadView('pdf.undangan', $data)
            ->setPaper('a4', 'portrait')
            ->download('undangan.pdf');
    } 
}
