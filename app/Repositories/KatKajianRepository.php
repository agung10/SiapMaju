<?php

namespace App\Repositories;

use App\Models\Kajian\Kategori;
use App\Helpers\helper;

class KatKajianRepository
{
    public function __construct(Kategori $_Kategori)
    {
        $this->kategori = $_Kategori;
    }

    public function dataTables()
    {
        $datatableButtons = method_exists(new $this->kategori, 'datatableButtons') ? $this->kategori->datatableButtons() : ['show', 'edit', 'destroy'];
        $model = \DB::table('kat_kajian')
                      ->select('kat_kajian_id','kategori')
                      ->get();

        return \DataTables::of($model)
                            ->addIndexColumn()
                            ->addColumn('action', function($data) use ($datatableButtons) {
                                return view('partials.buttons.cust-datatable',[
                                    'show'         => in_array("show", $datatableButtons ) ? route('Kajian.Kategori'.'.show', \Crypt::encryptString($data->kat_kajian_id)) : null,
                                    'edit'         => in_array("edit", $datatableButtons ) ? route('Kajian.Kategori'.'.edit', \Crypt::encryptString($data->kat_kajian_id)) : null,
                                    'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $data->kat_kajian_id : null
                                ]);
                            })
                            ->rawColumns(['action'])
                            ->make(true);
    }

    public function show($id)
    {
        return $this->kategori->findOrFail($id);
    }

    public function store($request)
    {   
        $input = $request->except('proengsoft_jsvalidation');

        \DB::beginTransaction();

        try{
            $input['date_created'] = date('Y-m-d H:i:s');

            $this->kategori->create($input);

            \DB::commit();

            return response()->json(['status' => 'success']);

        }catch(\Exception $e){
            \DB::rollback();
            throw $e;
        }
    }

    public function update($request,$id)
    {   
        $model = $this->kategori->findOrFail($id);

        $inputs = $request->except('proengsoft_jsvalidation');
        $inputs['date_updated'] = date('Y-m-d H:i:s');
        $inputs['user_updated'] = \Auth::user()->user_id;

        \DB::beginTransaction();

        try{

            $model->update($inputs);

            \DB::commit();

            return response()->json(['status' => 'success']);


        }catch(\Exception $e){
            \DB::rollback();
            throw $e;
        }
    }

    public function delete($id)
    {
        
        try{

            $this->kategori->destroy($id);

            return response()->json(['status' => 'success']);

        }catch(\Exception $e){
            \DB::rollback();
            throw $e;
        }
    }
}