<?php

namespace App\Http\Controllers\API\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Kegiatan\Kegiatan;

class KegiatanController extends Controller
{
    public function __construct(Kegiatan $kegiatan)
    {
        $this->user     = auth('api')->user();
        $this->kegiatan = $kegiatan;
    }

    public function get(Request $request)
    {
        $anggotaKeluarga = $this->user->anggotaKeluarga;
        $kegiatan = $this->kegiatan->where('rw_id', $anggotaKeluarga->rw_id)->orderBy('created_at','DESC')->get();

        return response()->json($kegiatan,200);
    }

    public function getKegiatanByKategori(Request $request)
    {
        $anggotaKeluarga = $this->user->anggotaKeluarga;
        $kegiatans = $this->kegiatan
                         ->where('kegiatan.kat_kegiatan_id',$request->kat_kegiatan_id)
                         ->where('rt_id', $anggotaKeluarga->rt_id)
                         ->orderBy('created_at','DESC')
                         ->get();

        $result = [];

        foreach($kegiatans as $key => $val){
            $kegiatan['name'] = $val->nama_kegiatan;
            $kegiatan['id'] = $val->kegiatan_id;

            array_push($result,$kegiatan);
        }

        return response()->json(compact('result'),200);
    }


}
