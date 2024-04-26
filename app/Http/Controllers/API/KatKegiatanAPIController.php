<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KatKegiatanAPIController extends Controller
{   
    public function __construct()
    {
        $this->route = explode('.',\Route::CurrentRouteName());
        
    }

    public function getKatKegiatan()
    {   

        $katKegiatans = \DB::table('kat_kegiatan')
                          ->select('kat_kegiatan.kat_kegiatan_id','kat_kegiatan.nama_kat_kegiatan','kat_kegiatan.kode_kat')
                          ->whereIn('kat_kegiatan.kat_kegiatan_id',function($query){
                            $query->select('kegiatan.kat_kegiatan_id')
                                  ->from('kegiatan')
                                  ->whereIn('kegiatan.rt_id',function($query){
                                    $operator = $this->route[1] === 'general' ? '!=' : '=';

                                    $query->select('rt.rt_id')
                                          ->from('rt')
                                          ->where('rt.rt',$operator,'DKM');
                                });
                          })
                          ->get();

        $result = [];

        foreach($katKegiatans as $key => $val){
            $katKegiatan['name'] = $val->nama_kat_kegiatan;
            $katKegiatan['id'] = $val->kat_kegiatan_id;
            $katKegiatan['kode_kat'] = $val->kode_kat;

            array_push($result,$katKegiatan);
        }
                        
        return response()->json(compact('result'));
    }
}
