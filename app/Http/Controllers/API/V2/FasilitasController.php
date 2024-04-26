<?php

namespace App\Http\Controllers\API\V2;

use App\Models\Fasilitas\Fasilitas;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FasilitasController extends Controller
{
    public function __construct(Fasilitas $fasilitas)
    {
        $this->fasilitas = $fasilitas;
    }

    public function get()
    {
        $wargaLoggedIn = auth('api')->user()->anggotaKeluarga;
        $fasilitas     =  $this->fasilitas->where('rw_id', $wargaLoggedIn->rw_id)->orderBy('created_at', 'DESC')->get();

        return response()->json(['data' => $fasilitas]);
    }
}
