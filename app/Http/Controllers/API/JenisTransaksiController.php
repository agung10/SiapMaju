<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JenisTransaksiController extends Controller
{
    public function getJenisTransaksi()
    {
        $jenisTransaksis = \DB::table('jenis_transaksi')
                             ->select('jenis_transaksi.kegiatan_id',
                                      'jenis_transaksi.nama_jenis_transaksi',
                                      'jenis_transaksi.jenis_transaksi_id',
                                      'jenis_transaksi.nilai')
                             ->get();

        $result = [];

        foreach($jenisTransaksis as $key => $val){
            $jenisTransaksi['id'] = $val->jenis_transaksi_id;
            $jenisTransaksi['name'] = $val->nama_jenis_transaksi;
            $jenisTransaksi['kegiatan_id'] = $val->kegiatan_id;
            $jenisTransaksi['nilai'] = $val->nilai;

            array_push($result,$jenisTransaksi);
        }

        return response()->json(compact('result'),200);
    }
}
