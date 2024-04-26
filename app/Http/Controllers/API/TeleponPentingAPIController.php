<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeleponPentingAPIController extends Controller
{
    public function getTeleponPenting()
    {
        $db_tlp  = \DB::table('telepon_penting')
                       ->select('telepon_penting_id','nama_instansi', 'no_tlp', 'alamat')
                       ->get();

        $result = [];

        foreach($db_tlp as $key => $val){

            $tlp_penting['id'] = $val->telepon_penting_id;
            $tlp_penting['nama_instansi'] = $val->nama_instansi;
            $tlp_penting['no_tlp'] = $val->no_tlp;
            $tlp_penting['alamat'] = $val->alamat;

            array_push($result,$tlp_penting);
        }

        return response()->json(compact('result'));
    }
}
