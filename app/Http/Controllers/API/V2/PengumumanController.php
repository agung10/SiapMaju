<?php

namespace App\Http\Controllers\API\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PengumumanResource;
use App\Models\Pengumuman;

class PengumumanController extends Controller
{
    public function __construct(Pengumuman $pengumuman)
    {
        $this->user = auth('api')->user();
        $this->pengumuman = $pengumuman;
    }

    public function get(Request $request)
    {
        $anggotaKeluarga = $this->user->anggotaKeluarga;
        $pengumuman = $this->pengumuman->where('rw_id', $anggotaKeluarga->rw_id)->orderBy('created_at','DESC');
        $pengumuman = $request->limit ? $pengumuman->limit($request->limit) : $pengumuman;

        return PengumumanResource::collection($pengumuman->get());
    }
}
