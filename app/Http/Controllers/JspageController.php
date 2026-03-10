<?php

namespace App\Http\Controllers;

class JspageController extends Controller
{
    public function index()
    {
        return view('Jspage.index');
    }

    public function datatables()
    {
        return view('Jspage.datatables');
    }

    public function kota()
    {
        return view('Jspage.kota');
    }
}
