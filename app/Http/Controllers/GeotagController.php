<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeotagController extends Controller
{
    public function index()
    {
        return view('Geotag.GeotagPage');
    }
}
