<?php

namespace App\Repositories\RoleManagement;

use App\Models\Master\Keluarga\AnggotaKeluarga;

class ListRtRwRepository
{
    public function __construct()
    {
        
    }

    public function dataTables()
    {   
        $model = AnggotaKeluarga::select('anggota_keluarga.nama',
                                         'keluarga.rt_id',
                                         'keluarga.rw_id',
                                         'anggota_keluarga.is_rt',
                                         'anggota_keluarga.is_rw',
                                         'anggota_keluarga.is_dkm',
                                         'anggota_keluarga.anggota_keluarga_id',
                                         'rt.rt',
                                         'rw.rw')
                                  ->join('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                                  ->join('hub_keluarga','hub_keluarga.hub_keluarga_id','anggota_keluarga.hub_keluarga_id')
                                  ->join('rt','rt.rt_id','keluarga.rt_id')
                                  ->join('rw','rw.rw_id','keluarga.rw_id')
                                  ->where('hub_keluarga.hubungan_kel','Kepala Keluarga')
                                  ->where('anggota_keluarga.is_active', '!=', false);

        $datatableButtons = method_exists(new AnggotaKeluarga, 'datatableButtons') ? AnggotaKeluarga::datatableButtons() : ['show', 'edit', 'destroy'];

        $badgeYes = '<span class="badge badge-pill badge-primary">Ya</span>';
        $badgeNo = '<span class="badge badge-pill badge-danger">Tidak</span>';

        return \DataTables::of($model)
                           ->addIndexColumn()
                           ->addColumn('action',function($model)use($datatableButtons){
                                return view('partials.buttons.cust-datatable',[
                                    'customButton' => ['route' => route('role_management.ListRtRw.show',$model->anggota_keluarga_id),
                                                       'name' => 'Pilih RT/RW/DKM'
                                                      ]
                                ]);
                            })
                           ->addColumn('nama',function($model){
                               return $model->nama.' ('.$model->rt.'/'.$model->rw.')';
                           })
                           ->addColumn('rt',function($model)use($badgeYes,$badgeNo){
                                $badge = $model->is_rt ? $badgeYes : $badgeNo;
                                return $badge;
                           })
                           ->addColumn('rw',function($model)use($badgeYes,$badgeNo){
                                $badge = $model->is_rw ? $badgeYes : $badgeNo;
                                return $badge;
                           })
                           ->addColumn('dkm',function($model)use($badgeYes,$badgeNo){
                                $badge = $model->is_dkm ? $badgeYes : $badgeNo;
                                return $badge;
                           })
                           ->rawColumns(['action','rt','rw','dkm'])
                           ->make(true);
    }

    public function update($request,$id)
    {
        $transaction = false;

        \DB::beginTransaction();
        try{    

            AnggotaKeluarga::findOrFail($id)
                            ->update($request->all());
            \DB::commit();
            $transaction = true;
        }catch(\Exception $e){

            \DB::rollback();
            throw $e;
        }

        if($transaction){
            return response()->json(['status' => 'success']);
        }
    }
}