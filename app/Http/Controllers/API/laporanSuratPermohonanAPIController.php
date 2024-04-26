<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class laporanSuratPermohonanAPIController extends Controller
{
    public function search(Request $request)
    {   
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $laporans = \DB::table('surat_permohonan')
                      ->select('surat_permohonan.nama_lengkap',
                               'surat_permohonan.no_surat',
                               'surat_permohonan.created_at',
                               'surat_permohonan.tgl_permohonan',
                               'surat_permohonan.tgl_approve_rw',
                               'surat_permohonan.tgl_approve_rt',
                               'surat_permohonan.hal',
                               'surat_permohonan.surat_permohonan_id',
                               'jenis_surat.jenis_permohonan')
                      ->join('jenis_surat','jenis_surat.jenis_surat_id','surat_permohonan.jenis_surat_id')
                      ->whereBetween('surat_permohonan.created_at',[$startDate,$endDate])
                      ->orderBy('surat_permohonan.created_at','DESC')
                      ->get();

        $result = [];

        foreach($laporans as $key => $val){
            $tgl_permohonan = new \DateTime($val->tgl_permohonan);
            $tgl_apporval_rw = new \DateTime($val->tgl_approve_rw);
                                
            $lamaProses = $tgl_permohonan->diff($tgl_apporval_rw);
            $lamaProsesInDay = $lamaProses->d;

            $posisi_surat = empty($val->tgl_approve_rt) ? 'RT'
                                                        : (!empty($val->tgl_approve_rt) && empty($val->tgl_approve_rw) 
                                                            ? 'RW'
                                                            : '');

            $laporan['surat_permohonan_id'] = $val->surat_permohonan_id;
            $laporan['nama_lengkap'] = $val->nama_lengkap;
            $laporan['no_surat'] = $val->no_surat;
            $laporan['tgl_permohonan'] = $val->created_at;
            $laporan['status_surat'] =  empty($val->tgl_approve_rw) ? 'Proses' : 'Selesai';
            $laporan['lama_proses'] = $lamaProsesInDay;
            $laporan['posisi_surat'] = $posisi_surat;
            $laporan['hal'] = $val->hal;

            array_push($result,$laporan);
        }

        return response()->json(compact('result'),200);
    }
}
