<?php

namespace App\Repositories\Surat;
use App\Models\Surat\JenisSuratRw;

class JenisSuratRwRepository
{
    public function __construct(JenisSuratRw $_JenisSuratRw)
    {
        $this->jenisSurat = $_JenisSuratRw;
    }

    public function dataTables()
    {
        $model = $this->jenisSurat->select('jenis_surat','jenis_surat_rw_id')
                                  ->get();

        $datatableButtons = method_exists(new $this->jenisSurat, 'datatableButtons') ? $this->jenisSurat->datatableButtons() : ['show', 'edit', 'destroy'];

        return \DataTables::of($model)
                          ->addIndexColumn()
                          ->addColumn('action',function($model)use($datatableButtons){
                            return view('partials.buttons.cust-datatable',[
                                'show'         => in_array("show", $datatableButtons ) ? route('Master.JenisSuratRw'.'.show', \Crypt::encryptString($model->jenis_surat_rw_id)) : null,
                                'edit'         => in_array("edit", $datatableButtons ) ? route('Master.JenisSuratRw'.'.edit', \Crypt::encryptString($model->jenis_surat_rw_id)) : null,
                                'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $model->jenis_surat_rw_id : null
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
            $this->jenisSurat->create($input);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        return $this->jenisSurat->select('jenis_surat','jenis_surat_rw')->findOrFail($id);
    }

    public function update($request,$id)
    {
        $data = $this->jenisSurat->findOrFail($id);

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
            $this->jenisSurat->destroy($id);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }
}