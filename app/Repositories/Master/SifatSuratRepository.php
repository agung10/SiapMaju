<?php

namespace App\Repositories\Master;
use App\Models\Surat\SifatSurat;

class SifatSuratRepository
{
    public function __construct()
    {

    }

    public function dataTables()
    {
        $model = SifatSurat::select('sifat_surat_id','sifat_surat')
                            ->get();

        $datatableButtons = method_exists(new SifatSurat, 'datatableButtons') ? SifatSurat::datatableButtons() : ['show', 'edit', 'destroy'];

        return \DataTables::of($model)
                          ->addIndexColumn()
                          ->addColumn('action',function($model)use($datatableButtons){
                            return view('partials.buttons.cust-datatable',[
                                'show'         => in_array("show", $datatableButtons ) ? route('Master.SifatSurat'.'.show', \Crypt::encryptString($model->sifat_surat_id)) : null,
                                'edit'         => in_array("edit", $datatableButtons ) ? route('Master.SifatSurat'.'.edit', \Crypt::encryptString($model->sifat_surat_id)) : null,
                                'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $model->sifat_surat_id : null
                            ]);
                          })
                          ->rawColumns(['action'])
                          ->make(true);
    }

    public function store($request)
    {
        $input = $request->except('proengsoft_jsvalidation');
        $input['user_created'] = \Auth::user()->user_id;

        try {
            SifatSurat::create($input);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        return SifatSurat::select('sifat_surat_id','sifat_surat')->findOrFail($id);
    }

    public function update($request,$id)
    {
        $data = SifatSurat::findOrFail($id);

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
            SifatSurat::destroy($id);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }
}