<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Perusahaan;
use Illuminate\Http\Request;

class PerusahaanController extends Controller
{
    //
    public function index()
    {
        //
        $data = Perusahaan::all();
        return $data;
    }
    public function store(Request $request)
    {
        $perusahaan = new Perusahaan();
        $perusahaan->nama = $request->input('nama');
        $perusahaan->standard_discount = $request->input('sd');
        $perusahaan->additional_discount = $request->input('ad');
        $perusahaan->save();
        return response()->json([
            'result' => "success",
            'message' => 'Perusahaan berhasil ditambahkan',
            'data' => $perusahaan
        ]);
    }
}
