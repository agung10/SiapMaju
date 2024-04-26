<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class statusPernikahanAPIController extends Controller
{
    public function statusPernikahan()
    {
        $statusPernikahan = \DB::table('status_pernikahan')
                                ->select('status_pernikahan.status_pernikahan_id','status_pernikahan.nama_status_pernikahan')
                                ->get();

        $result = [];

        foreach($statusPernikahan as $key => $val){
            $status['id'] = $val->status_pernikahan_id;
            $status['name'] = $val->nama_status_pernikahan;

            array_push($result,$status);
        }

        return response()->json(compact('result'));
    }
}
