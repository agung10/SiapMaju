<?php

namespace App\Repositories\LaporanSikad;

use App\Models\Surat\SuratPermohonan;

class LaporanSuratPermohonanRepository
{
    public function __construct(SuratPermohonan $_SuratPermohonan)
    {
        $this->surat = $_SuratPermohonan;
    }

    public function dataTables()
    {   
        $logedUSer = \DB::table('users')
                         ->select('anggota_keluarga.is_rt','anggota_keluarga.is_rw','anggota_keluarga.keluarga_id','users.is_admin','keluarga.rt_id','keluarga.rw_id','anggota_keluarga.anggota_keluarga_id')
                         ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                         ->leftJoin('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                         ->where('user_id',\Auth::user()->user_id)
                         ->first();

        $isKepalaKeluarga = \helper::checkUserRole('kepalaKeluarga');

        $isRt = $logedUSer->is_rt == true;
        $isRw = $logedUSer->is_rw == true;
        $isAdmin = $logedUSer->is_admin == true;
        $isPetugas = $isRt == true || $isRw == true || $isAdmin == true || $isKepalaKeluarga == true;
        $isWarga = $isRt != true && $isRw != true && $isAdmin != true;

        $model = \DB::table('surat_permohonan')
                     ->select('jenis_surat.jenis_permohonan',
                              'surat_permohonan.surat_permohonan_id',
                              'surat_permohonan.created_at',
                              'anggota_keluarga.nama',
                              'surat_permohonan.no_surat',
                              'rt.rt',
                              'surat_permohonan.tgl_approve_rw',
                              'surat_permohonan.tgl_permohonan')
                    ->join('jenis_surat','jenis_surat.jenis_surat_id','surat_permohonan.jenis_surat_id')
                    ->join('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','surat_permohonan.anggota_keluarga_id')
                    ->join('rt','rt.rt_id','surat_permohonan.rt_id')
                    ->when($isPetugas !== true, function($query){
                        $query->where('surat_permohonan.anggota_keluarga_id',\Auth::user()->anggota_keluarga_id);
                    })
                    ->when($isKepalaKeluarga && !$isRt && !$isRw, function($query) use ($logedUSer){
                        $query->where('anggota_keluarga.keluarga_id', $logedUSer->keluarga_id);
                    })  
                    ->when($isRt == true,function($query)use($logedUSer){
                        $query->where('surat_permohonan.rt_id',$logedUSer->rt_id)
                            ->orWhere('anggota_keluarga.keluarga_id', $logedUSer->keluarga_id)
                            ->orWhere('surat_permohonan.anggota_keluarga_id',\Auth::user()->anggota_keluarga_id);
                    })
                    ->when($isRw == true,function($query)use($logedUSer){
                        $query->where('surat_permohonan.rw_id',$logedUSer->rw_id)
                            ->orWhere('anggota_keluarga.keluarga_id', $logedUSer->keluarga_id)
                            ->orWhere('surat_permohonan.anggota_keluarga_id',\Auth::user()->anggota_keluarga_id);

                    });
                    // ->when($isWarga == true,function($query)use($logedUSer){
                    //     $query->where('surat_permohonan.anggota_keluarga_id',$logedUSer->anggota_keluarga_id);
                    // })
        
        
        return \DataTables::of($model)
                          ->addIndexColumn()
                          ->addColumn('status_surat',function($model){
                              $isDone = !empty($model->tgl_approve_rw);
                              $badgeColor = $isDone ? 'badge-success' : 'badge-warning';
                              $statusSurat = !empty($model->tgl_approve_rw) ? 'Selesai' : 'Proses';
                              $tooltip = 'title="Approval RW: '.date('d-m-Y',strtotime($model->tgl_approve_rw)).'"
                                          data-toggle="tooltip"
                                          data-placement="bottom"';
                                          
                              return '<span class="badge badge-pill '.$badgeColor.'" '.($isDone ? $tooltip  : "").' >'.$statusSurat.'</span>';
                          })
                          ->addColumn('lama_proses',function($model){
                                $tgl_permohonan = new \DateTime($model->tgl_permohonan);
                                $tgl_apporval_rw = new \DateTime($model->tgl_approve_rw);
                                
                                $lamaProses = $tgl_permohonan->diff($tgl_apporval_rw);
                                $lamaProsesInDay = $lamaProses->d;
                        
                                $badgeColor =  $lamaProsesInDay <= 1 ? 'badge-success' 
                                                                     : ($lamaProsesInDay > 1 && $lamaProsesInDay <= 3 ? 'badge-warning' 
                                                                                                                      : 'badge-danger'); 
                                if($model->tgl_approve_rw){
                                    return '<span class="badge badge-pill '.$badgeColor.'">'.$lamaProsesInDay.' Hari</span>';
                                }
                          })
                          ->addColumn('tgl_surat',function($model){
                              return date('d-m-Y',strtotime($model->created_at));
                          })
                          ->rawColumns(['lama_proses','status_surat'])
                          ->make(true);
    }

    public function searchLaporan($request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
    
        $laporan = $this->surat
                        ->select('jenis_surat.jenis_permohonan',
                                 'surat_permohonan.created_at',
                                 'anggota_keluarga.nama',
                                 'surat_permohonan.no_surat',
                                 'rt.rt',
                                 'surat_permohonan.tgl_approve_rw',
                                 'surat_permohonan.tgl_permohonan')
                        ->join('jenis_surat','jenis_surat.jenis_surat_id','surat_permohonan.jenis_surat_id')
                        ->join('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','surat_permohonan.anggota_keluarga_id')
                        ->join('rt','rt.rt_id','surat_permohonan.rt_id')
                        ->whereBetween('surat_permohonan.created_at',[$startDate,$endDate])
                        ->get();
       
        $result = view('LaporanSikad.LaporanSuratPermohonan.resultLaporan',compact('laporan'))->render();
        
        return response()->json(compact('result'));
    }
}