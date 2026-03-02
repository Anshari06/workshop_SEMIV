<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Buku;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();
        $buku = Buku::all();
        $user = Auth::user();
        return view('admin.dashboard', compact('kategori', 'buku', 'user'));
    }
}
