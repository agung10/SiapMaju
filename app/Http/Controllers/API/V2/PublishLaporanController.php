<?php

namespace App\Http\Controllers\API\V2;

use App\Models\Laporan\Laporan;
use App\Models\Master\Keluarga\AnggotaKeluarga;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PublishLaporanController extends Controller
{
    public function __construct(Laporan $laporan, AnggotaKeluarga $anggotaKeluarga)
    {
        $this->userLoggedIn    = auth('api')->user();
        $this->laporan         = $laporan;
        $this->anggotaKeluarga = $anggotaKeluarga;
    }

    public function get(Request $request)
    {
        $wargaLoggedIn = $this->userLoggedIn->anggotaKeluarga;
        $laporan = [];

        if ($wargaLoggedIn) {
            $ketuaRWWarga = $this->anggotaKeluarga->where('rw_id', $wargaLoggedIn->rw_id)->where('is_rw', true)->first();

            if ($ketuaRWWarga) {
                $laporan = $this->laporan->select([
                    'laporan.laporan_id', 'kat_laporan.nama_kategori', 'laporan.laporan', 'laporan.detail_laporan', 'laporan.upload_materi', 'laporan.image', 'laporan.created_at'
                ])
                    ->join('kat_laporan', 'kat_laporan.kat_laporan_id', 'laporan.kat_laporan_id')
                    ->where('laporan.rw_id', $wargaLoggedIn->rw_id)
                    ->orderBy('laporan.created_at')
                    ->get();
            }
        }

        if ($request->laporan_id) {
            $laporan = $this->laporan->select([
                'laporan.laporan_id', 'kat_laporan.nama_kategori', 'laporan.laporan', 'laporan.detail_laporan', 'laporan.upload_materi', 'laporan.image', 'laporan.created_at'
            ])
                ->join('kat_laporan', 'kat_laporan.kat_laporan_id', 'laporan.kat_laporan_id')
                ->where('laporan_id', $request->laporan_id)
                ->first();
        }

        return response()->json($laporan);
    }
}
