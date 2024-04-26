<?php

namespace App\Http\Controllers\API\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanSuratPermohonanController extends Controller
{
    public function get(Request $request)
    {   
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $userLoggedIn = auth('api')->user()->anggotaKeluarga;
        $laporans = \DB::table('surat_permohonan')
                      ->select([
                            'surat_permohonan.nama_lengkap',
                           'surat_permohonan.no_surat',
                           'surat_permohonan.created_at',
                           'surat_permohonan.tgl_permohonan',
                           'surat_permohonan.tgl_approve_rw',
                           'surat_permohonan.tgl_approve_rt',
                           'surat_permohonan.hal',
                           'surat_permohonan.surat_permohonan_id',
                           'surat_permohonan.rt_id',
                           'surat_permohonan.rw_id',
                           'surat_permohonan.tgl_approve_lurah',
                           'jenis_surat.jenis_permohonan'
                        ])
                      ->join('jenis_surat','jenis_surat.jenis_surat_id','surat_permohonan.jenis_surat_id')
                      ->whereBetween('surat_permohonan.created_at',[$startDate,$endDate])
                      ->orderBy('surat_permohonan.created_at','DESC');

        if($userLoggedIn->is_rt) {
            $laporans = $laporans->where('surat_permohonan.rt_id', $userLoggedIn->rt_id)->get();
        } else if($userLoggedIn->is_rw) {
            $laporans = $laporans->where('surat_permohonan.rw_id', $userLoggedIn->rw_id)->get();
        } else {
            $laporans = [];
        }

        $result = [];

        foreach($laporans as $key => $val){
            $tgl_permohonan    = new \DateTime($val->tgl_permohonan);
            $lamaProsesInDay   = $val->tgl_approve_lurah 
                                    ? $tgl_permohonan->diff( new \DateTime($val->tgl_approve_lurah) )->d
                                    : '-';

            $posisi_surat = '';

            switch(true){
                case($val->tgl_approve_rt === null):
                    $posisi_surat = 'RT';
                    break;
                case($val->tgl_approve_rw === null):
                    $posisi_surat = 'RW';
                    break;
                case($val->tgl_approve_lurah === null):
                    $posisi_surat = 'Kelurahan';
                    break;

                default: 
                $posisi_surat = 'Selesai'; 
            }

            $laporan['surat_permohonan_id'] = $val->surat_permohonan_id;
            $laporan['nama_lengkap'] = $val->nama_lengkap;
            $laporan['no_surat'] = $val->no_surat;
            $laporan['tgl_permohonan'] = $val->created_at;
            $laporan['status_surat'] =  empty($val->tgl_approve_lurah) ? 'Proses' : 'Selesai';
            $laporan['lama_proses'] = $lamaProsesInDay;
            $laporan['posisi_surat'] = $posisi_surat;
            $laporan['rt_id'] = $val->rt_id;
            $laporan['rw_id'] = $val->rw_id;
            $laporan['tgl_approve_lurah'] = $val->tgl_approve_lurah;
            $laporan['hal'] = $val->hal;

            array_push($result,$laporan);
        }

        return response()->json(compact('result'),200);
    }
}
