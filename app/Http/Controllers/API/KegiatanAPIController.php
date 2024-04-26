<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KegiatanAPIController extends Controller
{
    public function getKegiatan(Request $request)
    {
        $kegiatans = \DB::table('kegiatan')
                        ->select('kegiatan.kegiatan_id','kegiatan.nama_kegiatan')
                        ->where('kegiatan.kat_kegiatan_id',$request->kat_kegiatan_id)
                        ->get();

        $result = [];

        foreach($kegiatans as $key => $val){
            $kegiatan['name'] = $val->nama_kegiatan;
            $kegiatan['id'] = $val->kegiatan_id;

            array_push($result,$kegiatan);
        }

        return response()->json(compact('result'),200);
    }
}
