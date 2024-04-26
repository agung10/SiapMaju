<?php

namespace App\Repositories\Master;
use App\Models\Master\StatusPernikahan;

class StatusPernikahanRepository
{
    
    public function dataTables()
    {
        $model = StatusPernikahan::select('status_pernikahan_id','nama_status_pernikahan')
                             ->get();

        $datatableButtons = method_exists(new StatusPernikahan, 'datatableButtons') ? StatusPernikahan::datatableButtons() : ['show', 'edit', 'destroy'];

        return \DataTables::of($model)
                            ->addIndexColumn()
                            ->addColumn('action',function($model) use($datatableButtons){
                                return view('partials.buttons.cust-datatable',[
                                    'show'         => in_array("show", $datatableButtons ) ? route('Master.StatusPernikahan'.'.show', \Crypt::encryptString($model->status_pernikahan_id)) : null,
                                    'edit'         => in_array("edit", $datatableButtons ) ? route('Master.StatusPernikahan'.'.edit', \Crypt::encryptString($model->status_pernikahan_id)) : null,
                                    'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $model->status_pernikahan_id : null
                                ]);
                            })
                            ->rawColumns(['action'])
                            ->make(true);
    }

    public function store($request)
    {
        $request['user_created'] = \Auth::user()->user_id;
        
        try {
            StatusPernikahan::create($request->except('proengsoft_jsvalidation'));
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        return StatusPernikahan::select('status_pernikahan_id','nama_status_pernikahan')->findOrFail($id);
    }

    public function update($request,$id)
    {
        $data = StatusPernikahan::findOrFail($id);

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
            StatusPernikahan::destroy($id);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }
}