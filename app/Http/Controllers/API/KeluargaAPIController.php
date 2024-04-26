<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Master\AnggotaKeluargaRepository;

class KeluargaAPIController extends Controller
{   
    public function __construct(AnggotaKeluargaRepository $_AnggotaKeluargaRepository)
    {
        $this->user = auth('api')->user();
        $this->anggotaKeluarga = $_AnggotaKeluargaRepository;
    }

    public function getKepalaKeluarga()
    {
        $kepalaKeluargas = \DB::table('anggota_keluarga')
                              ->select('anggota_keluarga.anggota_keluarga_id','anggota_keluarga.nama','anggota_keluarga.keluarga_id')
                              ->join('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                              ->where('anggota_keluarga.keluarga_id',$this->user->anggotaKeluarga->keluarga_id)
                              ->where('anggota_keluarga.hub_keluarga_id',1)
                              ->get();
        $result = [];

        foreach($kepalaKeluargas as $key => $val){

            $kepalaKeluarga['id'] = $val->anggota_keluarga_id;
            $kepalaKeluarga['name'] = $val->nama;
            $kepalaKeluarga['keluarga_id'] = $val->keluarga_id;

            array_push($result,$kepalaKeluarga);
        }

        return response()->json(compact('result'),200);
    }

    public function getAnggotaKeluarga(Request $request)
    {
        $anggotaKeluargas = \DB::table('anggota_keluarga')
                              ->select('anggota_keluarga.anggota_keluarga_id','anggota_keluarga.nama')
                              ->where('anggota_keluarga.keluarga_id',$request->keluarga_id)
                              ->get();
        
        $result = [];

        foreach($anggotaKeluargas as $key => $val){

            $anggotaKeluarga['id'] = $val->anggota_keluarga_id;
            $anggotaKeluarga['name'] =  $val->nama;
            
            array_push($result,$anggotaKeluarga);
        }

        return response()->json(compact('result'),200);
    }

    public function addAnggotaKeluarga(Request $request)
    {   
        return $this->anggotaKeluarga->store($request);
    }
}
