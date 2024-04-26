<?php

namespace App\Repositories;

use App\Models\Covid19\Covid19;
use App\Repositories\RoleManagement\UserRepository;

class DataCovidRepository 
{

    public function __construct(Covid19 $_Covid19, UserRepository $_user)
    {
        $this->model = $_Covid19;
        $this->user = $_user;
    }

    public function dataTables($request)
    {
        $checkRole = \helper::checkUserRole('all');
        $isRw = $checkRole['isRw'];
        $isRt = $checkRole['isRt'];

        $getData = $this->user->currentUser();
        $rwID = $getData->rw_id;
        $rtID = $getData->rt_id;

        if (request()->ajax()) {
            $model = $this->model
                    ->select('covid19.covid19_id','covid19.tgl_input','covid19.jml_positif','covid19.jml_sembuh','covid19.jml_meninggal')
                    ->join('rt', 'rt.rt_id', 'covid19.rt_id')
                    ->when($isRw, function ($query) use ($rwID) {
                        $query->where('rt.rw_id', $rwID);
                    })
                    ->when($isRt, function ($query) use ($rtID) {
                        $query->where('rt.rt_id', $rtID);
                    })
                    ->when(!empty($request->kelurahan_id), function ($query) use ($request) {
                        $query->where('rt.kelurahan_id', $request->kelurahan_id);
                        if (!empty($request->rw_id)) {
                            $query->where('rt.rw_id', $request->rw_id);
                        }
                        if (!empty($request->rt_id)) {
                            $query->where('rt.rt_id', $request->rt_id);
                        }
                    })
                    ->get();
        }

        $datatableButtons = method_exists(new Covid19, 'datatableButtons') ? Covid19::datatableButtons() : ['show', 'edit', 'destroy'];

        return \DataTables::of($model)
                            ->addIndexColumn()
                            ->addColumn('action',function($model) use($datatableButtons){
                                return view('partials.buttons.cust-datatable',[
                                    'show'         => in_array("show", $datatableButtons ) ? route('DataStatistik.DataCovid'.'.show', \Crypt::encryptString($model->covid19_id)) : null,
                                    'edit'         => in_array("edit", $datatableButtons ) ? route('DataStatistik.DataCovid'.'.edit', \Crypt::encryptString($model->covid19_id)) : null,
                                    'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $model->covid19_id : null
                                ]);
                            })
                             ->rawColumns(['action'])
                             ->make(true);
    }

    public function store($request)
    {

        try {
            Covid19::create($request->except('proengsoft_jsvalidation'));
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        return Covid19::select('covid19.covid19_id',
                               'covid19.tgl_input',
                               'covid19.jml_positif',
                               'covid19.jml_sembuh',
                               'covid19.jml_meninggal',
                               'covid19.rt_id',
                               'rt.rt')
                        ->join('rt','rt.rt_id','covid19.rt_id')
                        ->findOrFail($id);
    }

    public function update($request,$id)
    {
        $data = Covid19::findOrFail($id);

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
            Covid19::destroy($id);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }
}
