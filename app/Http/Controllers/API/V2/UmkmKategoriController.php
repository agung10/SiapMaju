<?php

namespace App\Http\Controllers\API\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UmkmKategoriController extends Controller
{
    public function index()
    {
        $query  = \DB::table('umkm_kategori')
            ->select('umkm_kategori.umkm_kategori_id', 'umkm_kategori.nama', 'umkm_kategori.keterangan')
            ->get();

        $result = [];

        foreach ($query as $key => $val) {
            $kategori['id'] = $val->umkm_kategori_id;
            $kategori['nama'] = $val->nama;
            $kategori['keterangan'] = $val->keterangan;

            array_push($result, $kategori);
        }
        return response()->json(compact('result'));
    }
}
