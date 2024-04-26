<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AgamaAPIController extends Controller
{
    public function agama()
    {
        $religions  = \DB::table('agama')
                       ->select('agama.agama_id','agama.nama_agama')
                       ->get();

        $result = [];

        foreach($religions as $key => $val){

            $religion['id'] = $val->agama_id;
            $religion['name'] = $val->nama_agama;

            array_push($result,$religion);
        }

        return response()->json(compact('result'));
    }
}
