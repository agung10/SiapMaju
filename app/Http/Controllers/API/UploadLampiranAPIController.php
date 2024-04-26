<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadLampiranAPIController extends Controller
{
    public function temp(Request $request)
    {   
        $lampiran = '';

        foreach($request->all() as $key => $val){
            if(is_object($val)) $lampiran = $key;
        }

        $file = $request->file($lampiran);
        $fileName = $lampiran.'_'.rand().'.'.$file->getClientOriginalExtension();
        $file->move('temp/lampiran',$fileName);

        return response()->json(compact('fileName'));
    }
}
