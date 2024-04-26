<?php

namespace App\Repositories\Surat;

use App\Models\Surat\SuratMasukKeluarRw;
use App\Helpers\helper;

class SuratMasukKeluarRwRepository
{
    public function __construct(SuratMasukKeluarRw $_SuratMasukKeluarRw)
    {
        $this->surat = $_SuratMasukKeluarRw;
    }

    public function dataTables()
    {
        $model = $this->surat
                      ->select('rt.rt','surat_masuk_keluar_rw.no_surat','surat_masuk_keluar_rw.hal','surat_masuk_keluar_rw.tgl_surat',
                               'jenis_surat_rw.jenis_surat','surat_masuk_keluar_rw.surat_masuk_keluar_rw_id')
                      ->join('rt','rt.rt_id','surat_masuk_keluar_rw.rt_id','surat_masuk_keluar_rw.surat_masuk_keluar_rw_id')
                      ->join('jenis_surat_rw','jenis_surat_rw.jenis_surat_rw_id','surat_masuk_keluar_rw.jenis_surat_rw_id')
                      ->orderBy('surat_masuk_keluar_rw.updated_at','DESC')
                      ->get();

       $datatableButtons = method_exists(new $this->surat, 'datatableButtons') ? $this->surat->datatableButtons() : ['show', 'edit', 'destroy'];

        return \DataTables::of($model)
                          ->addIndexColumn()
                          ->addColumn('action',function($model)use($datatableButtons){
                            return view('partials.buttons.cust-datatable',[
                                'show'         => in_array("show", $datatableButtons ) ? route('Surat.SuratMasukKeluarRw'.'.show', \Crypt::encryptString($model->surat_masuk_keluar_rw_id)) : null,
                                'edit'         => in_array("edit", $datatableButtons ) ? route('Surat.SuratMasukKeluarRw'.'.edit', \Crypt::encryptString($model->surat_masuk_keluar_rw_id)) : null,
                                'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $model->surat_masuk_keluar_rw_id : null
                            ]);
                          })
                          ->rawColumns(['action'])
                          ->make(true);
    }

    public function store($request)
    {
        $transaction = false;
        $input = $request->except('proengsoft_jsvalidation');
        $input['user_created'] = \Auth::user()->user_id;

        if($request->hasFile('upload_file')){
            $input['upload_file'] = 'surat_masuk_rt_rw_'.rand().'.'.$request->upload_file->getClientOriginalExtension();
            $request->upload_file->move(public_path('uploaded_files/surat_masuk_keluar_rw'),$input['upload_file']);
        }

        \DB::beginTransaction();
        try{    

            $this->surat->create($input);
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

    public function update($request,$id)
    {
        $transaction = true;
        $surat = $this->surat
                      ->findOrFail($id);

        $input = $request->except('proengsoft_jsvalidation');


        
        if($request->hasFile('upload_file')){
            if($surat->upload_file){
                $this->deleteFile($surat);
            }

            $input['upload_file'] = 'surat_masuk_rt_rw_'.rand().'.'.$request->upload_file->getClientOriginalExtension();
            $request->upload_file->move(public_path('uploaded_files/surat_masuk_keluar_rw'),$input['upload_file']);
        }

        \DB::beginTransaction();
        
        try{

            $surat->update($input);
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

    public function destroy($id)
    {
        $surat = $this->surat
                      ->findOrFail($id);
        $transaction = false;

        if($surat->upload_file){
            $this->deleteFile($surat);
        }

        \DB::beginTransaction();
        try{
            
            $this->surat->destroy($id);
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

    public function deleteFile($surat)
    {
        $oldFile = public_path("uploaded_files/surat_masuk_keluar_rw/$surat->upload_file");
    
        if(file_exists($oldFile)){
            \File::delete($oldFile);
        }
    }

    public function generateNoSuratKeluar()
    {
        $countSurat = $this->surat
                           ->where('jenis_surat_rw_id',2)
                           ->count();

        $user = \DB::table('users')
                     ->select('rw.rw')
                     ->join('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                     ->join('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                     ->join('rw','rw.rw_id','keluarga.rw_id')
                     ->where('users.user_id',\Auth::user()->user_id)
                     ->first();
        
        $noRw = $user ? preg_replace('/[A-Za-z ]/','',$user->rw) : 'No RW';
        $currentCount = $countSurat + 1;
        $kodeSuratKeluar = '01';
        $month = date('m');
        $romanMonth = helper::romanMonth($month);
        $year = date('Y');
        
        $noSurat = "$currentCount/$noRw/$kodeSuratKeluar/$romanMonth/$year";

        return response()->json(['status' => 'success',
                                 'noSurat' => $noSurat]);
    }

    public function show($id)
    {
        $surat = $this->surat
                      ->select('jenis_surat_rw.jenis_surat',
                               'surat_masuk_keluar_rw.jenis_surat_rw_id',
                               'sifat_surat.sifat_surat',
                               'surat_masuk_keluar_rw.surat_masuk_keluar_rw_id',
                               'surat_masuk_keluar_rw.sifat_surat_id',
                               'surat_masuk_keluar_rw.no_surat',
                               'surat_masuk_keluar_rw.lampiran',
                               'surat_masuk_keluar_rw.hal',
                               'surat_masuk_keluar_rw.tgl_surat',
                               'surat_masuk_keluar_rw.asal_surat',
                               'surat_masuk_keluar_rw.nama_pengirim',
                               'surat_masuk_keluar_rw.tujuan_surat',
                               'surat_masuk_keluar_rw.nama_penerima',
                               'surat_masuk_keluar_rw.isi_surat',
                               'surat_masuk_keluar_rw.upload_file',
                               'surat_masuk_keluar_rw.warga_id',
                               'surat_masuk_keluar_rw.rt_id',
                               'surat_masuk_keluar_rw.rw_id',
                               'surat_masuk_keluar_rw.surat_balasan_id')
                      ->join('jenis_surat_rw','jenis_surat_rw.jenis_surat_rw_id','surat_masuk_keluar_rw.jenis_surat_rw_id')
                      ->join('sifat_surat','sifat_surat.sifat_surat_id','surat_masuk_keluar_rw.sifat_surat_id')
                      ->findOrFail($id);
    
        return $surat;
    }

    public function selectSuratBalasan($existId=false)
    {
        $model = \DB::table('surat_masuk_keluar_rw')
                     ->select('surat_masuk_keluar_rw.no_surat as no_surat_balasan','surat_masuk_keluar_rw.surat_masuk_keluar_rw_id')
                     ->where('surat_masuk_keluar_rw.jenis_surat_rw_id',2)
                     ->get();
       
        $result = '<option disabled selected>Pilih Surat Balasan</option>';
        
        foreach($model as $key => $val){
            $result .= '<option value="'.$val->surat_masuk_keluar_rw_id.'" '.($val->surat_masuk_keluar_rw_id == $existId ? "selected" : "").'>'.$val->no_surat_balasan.'</option>';
        }
  
        return $result;
    }
}