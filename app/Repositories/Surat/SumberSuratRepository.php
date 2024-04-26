<?php

namespace App\Repositories\Surat;
use App\Models\Surat\SumberSurat;

class SumberSuratRepository
{
    public function __construct()
    {

    }

    public function dataTables()
    {
        $model = SumberSurat::select('sumber_surat_id','asal_surat')
                            ->get();

        $datatableButtons = method_exists(new SumberSurat, 'datatableButtons') ? SumberSurat::datatableButtons() : ['show', 'edit', 'destroy'];

        return \DataTables::of($model)
                          ->addIndexColumn()
                          ->addColumn('action',function($model)use($datatableButtons){
                            return view('partials.buttons.cust-datatable',[
                                'show'         => in_array("show", $datatableButtons ) ? route('Master.SumberSurat'.'.show', \Crypt::encryptString($model->sumber_surat_id)) : null,
                                'edit'         => in_array("edit", $datatableButtons ) ? route('Master.SumberSurat'.'.edit', \Crypt::encryptString($model->sumber_surat_id)) : null,
                                'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $model->sumber_surat_id : null
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
            SumberSurat::create($input);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        return SumberSurat::select('sumber_surat_id','asal_surat')->findOrFail($id);
    }

    public function update($request,$id)
    {
        $data = SumberSurat::findOrFail($id);

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
            SumberSurat::destroy($id);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }
}