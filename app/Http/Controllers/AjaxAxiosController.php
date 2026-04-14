<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\provinsi;
use App\Models\Regencies;
use App\Models\District;
use App\Models\Villages;

class AjaxAxiosController extends Controller
{
    public function ajax()
    {
        $provinces = Provinsi::orderBy('name')->get();

        return view('ajax axios.ajax', compact('provinces'));
    }

    public function axios()
    {
        $provinces = Provinsi::orderBy('name')->get();

        return view('ajax axios.axios', compact('provinces'));
    }

    public function regencies(Request $request)
    {
        $request->validate([
            'province_id' => ['required'],
        ]);

        $regencies = Regencies::select('id', 'name')
            ->where('province_id', $request->province_id)
            ->orderBy('name')
            ->get();

        return response()->json($regencies);
    }

    public function districts(Request $request)
    {
        $request->validate([
            'regency_id' => ['required'],
        ]);

        $districts = District::select('id', 'name')
            ->where('regency_id', $request->regency_id)
            ->orderBy('name')
            ->get();

        return response()->json($districts);
    }

    public function villages(Request $request)
    {
        $request->validate([
            'district_id' => ['required'],
        ]);

        $villages = Villages::select('id', 'name')
            ->where('district_id', $request->district_id)
            ->orderBy('name')
            ->get();

        return response()->json($villages);
    }
}