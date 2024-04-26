<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HubKeluargaAPIController extends Controller
{
    public function getData()
    {
        $table = \DB::table('hub_keluarga')
                    ->select('hub_keluarga.hub_keluarga_id','hub_keluarga.hubungan_kel')
                    ->where('hub_keluarga.hub_keluarga_id','!=',1)
                    ->get();

        $result = [];

        foreach($table as $key => $val){

            $hubKeluarga['id'] = $val->hub_keluarga_id;
            $hubKeluarga['name'] =  $val->hubungan_kel;

            array_push($result,$hubKeluarga);
        }
        
        return response()->json(compact('result'));
    }
}
