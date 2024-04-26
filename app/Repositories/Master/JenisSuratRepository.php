<?php

namespace App\Repositories\Master;
use App\Models\Master\JenisSurat;

class JenisSuratRepository
{
    public function __construct()
    {

    }

    public function dataTables()
    {
        $model = JenisSurat::select('jenis_surat_id','jenis_permohonan','keterangan','kd_surat')
                             ->orderBy('kd_surat','ASC')
                             ->get();

        $datatableButtons = method_exists(new JenisSurat, 'datatableButtons') ? JenisSurat::datatableButtons() : ['show', 'edit', 'destroy'];

        return \DataTables::of($model)
                            ->addIndexColumn()
                            ->addColumn('action',function($model) use($datatableButtons){
                                return view('partials.buttons.cust-datatable',[
                                    'show'         => in_array("show", $datatableButtons ) ? route('Master.JenisSurat'.'.show', \Crypt::encryptString($model->jenis_surat_id)) : null,
                                    'edit'         => in_array("edit", $datatableButtons ) ? route('Master.JenisSurat'.'.edit', \Crypt::encryptString($model->jenis_surat_id)) : null,
                                    'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $model->jenis_surat_id : null
                                ]);
                            })
                            ->rawColumns(['action'])
                            ->make(true);
    }

    public function store($request)
    {

        try {
            JenisSurat::create($request->except('proengsoft_jsvalidation'));
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        return JenisSurat::select('jenis_surat_id','jenis_permohonan','kd_surat','keterangan')->findOrFail($id);
    }

    public function update($request,$id)
    {
        $data = JenisSurat::findOrFail($id);

        \DB::beginTransaction();

        try {
            $data->update($request->except('proengsoft_jsvalidation'));
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            JenisSurat::destroy($id);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }
}