<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlockAPIController extends Controller
{
    public function getBlock()
    {
        $anggotaKeluarga = \DB::table('anggota_keluarga')
                                ->select('blok.nama_blok','blok.blok_id','blok.long','blok.lang')
                                ->join('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                                ->join('rt','rt.rt_id','keluarga.rt_id')
                                ->join('blok','blok.blok_id','keluarga.blok_id')
                                ->get();
                                         
        $result = [];

        foreach($anggotaKeluarga as $key => $val){
            $blok['value'] = $val->blok_id;
            $blok['label'] = $val->nama_blok;
            $blok['long'] = $val->long;
            $blok['lang'] = $val->lang;

            $duplicate = in_array($blok,$result);

            if(!$duplicate){
                array_push($result,$blok);
            }
        }

        return response()->json(compact('result'));
    }

    public function getAnggotaKeluarga(Request $request)
    {
        $keluarga = \DB::table('keluarga')
                       ->select('anggota_keluarga.anggota_keluarga_id', 'anggota_keluarga.nama','anggota_keluarga.is_rt','anggota_keluarga.is_rw')
                       ->join('anggota_keluarga','anggota_keluarga.keluarga_id','keluarga.keluarga_id')
                       ->leftJoin('users','users.anggota_keluarga_id','anggota_keluarga.anggota_keluarga_id')
                       ->where('anggota_keluarga.hub_keluarga_id',1)
                       ->where('blok_id',$request->blok_id)
                       ->orderBy('anggota_keluarga.anggota_keluarga_id','asc')
                       ->get();

        return response()->json(['result' => $keluarga]);
    }
}
