<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BuktiPembayaranController extends Controller
{
    public function temp(Request $request)
    {

        $file = $request->file('bukti_pembayaran');
        $fileName = 'bukti_pembayaran'.'_'.rand().'.'.$file->getClientOriginalExtension();
        $file->move('temp/bukti_pembayaran',$fileName);

        $status = 'success';

        return response()->json(compact('fileName','status'));
    }
}
