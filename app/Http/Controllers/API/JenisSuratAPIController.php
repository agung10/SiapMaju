<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JenisSuratAPIController extends Controller
{
    public function getJenisSurat()
    {
        $jenisSurat = \DB::table('jenis_surat')
                          ->select('jenis_surat.jenis_permohonan','jenis_surat.kd_surat','jenis_surat.jenis_surat_id')
                          ->orderBy('jenis_surat.kd_surat','ASC')
                          ->get();

        $result = [];

        foreach($jenisSurat as $key => $val){
            $surat['jenis_permohonan'] = $val->jenis_permohonan;
            $surat['icon'] = asset("assets/icon/jenisSurat/$val->kd_surat.png");
            $surat['jenis_surat_id'] = $val->jenis_surat_id;

            array_push($result,$surat);
        }

        return response()->json(compact('result'));
    }
}
