<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Surat\SuratPermohonan;

class SuratPermohonanAPIController extends Controller
{
    public function store(Request $request)
    {   
        $transaction = false;
        \DB::beginTransaction();

        try{

            SuratPermohonan::create($request->all());
            \DB::commit();
            $transaction = true;
           
        }catch(\Exception $e){
            \DB::rollback();
            throw $e;
        }

        if(!$transaction) return response()->json(['status' => 'failed']);

        if($request->lampiran1){
            rename(public_path("temp/lampiran/$request->lampiran1"),public_path("uploaded_files/surat/$request->lampiran1"));
        }

        if($request->lampiran2){
            rename(public_path("temp/lampiran/$request->lampiran2"),public_path("uploaded_files/surat/$request->lampiran2"));
        }

        if($request->lampiran3){
            rename(public_path("temp/lampiran/$request->lampiran3"),public_path("uploaded_files/surat/$request->lampiran3"));
        }

        return response()->json(['status' => 'success']);
    }

    public function detail($id)
    {
       $suratPermohonan = \DB::table('surat_permohonan')
                             ->select('surat_permohonan.nama_lengkap',
                                      'surat_permohonan.no_surat',
                                      'surat_permohonan.hal',
                                      'surat_permohonan.tempat_lahir',
                                      'surat_permohonan.tgl_lahir',
                                      'surat_permohonan.bangsa',
                                      'agama.nama_agama',
                                      'status_pernikahan.nama_status_pernikahan',
                                      'surat_permohonan.pekerjaan',
                                      'surat_permohonan.no_kk',
                                      'surat_permohonan.no_ktp',
                                      'surat_permohonan.tgl_permohonan',
                                      'surat_permohonan.lampiran1',
                                      'surat_permohonan.lampiran2',
                                      'surat_permohonan.lampiran3'
                                      )
                            ->join('agama','agama.agama_id','surat_permohonan.agama_id')
                            ->join('status_pernikahan','status_pernikahan.status_pernikahan_id','surat_permohonan.status_pernikahan_id')
                            ->where('surat_permohonan.surat_permohonan_id',$id)
                            ->get();

        $result = [];

        foreach($suratPermohonan as $key => $val){
        
            $surat['nama_lengkap'] = $val->nama_lengkap;
            $surat['no_surat'] = $val->no_surat;
            $surat['hal'] = $val->hal;
            $surat['tempat_lahir'] = $val->tempat_lahir;
            $surat['tgl_lahir'] = $val->tgl_lahir;
            $surat['bangsa'] = $val->bangsa;
            $surat['agama'] = $val->nama_agama;
            $surat['status_pernikahan'] = $val->nama_status_pernikahan;
            $surat['pekerjaan'] = $val->pekerjaan;
            $surat['no_kk'] = $val->no_kk;
            $surat['no_ktp'] = $val->no_ktp;
            $surat['tgl_permohonan'] = $val->tgl_permohonan;
            $surat['lampiran1'] = $val->lampiran1 ? asset("uploaded_files/surat/$val->lampiran1") : null;
            $surat['lampiran2'] = $val->lampiran2 ? asset("uploaded_files/surat/$val->lampiran2") : null;
            $surat['lampiran3'] = $val->lampiran3 ? asset("uploaded_files/surat/$val->lampiran3") : null;
            
            array_push($result,$surat);
        }

        return response()->json(compact('result'));
    }
}
