<?php

namespace App\Repositories\Master;

use App\Models\Master\CapRT;
use App\Helpers\helper;

class CapRtRepository
{
    public function __construct(CapRT $CapRT)
    {
        $this->capRT = $CapRT;
    }

    public function dataTables($request)
    {
        if (request()->ajax()) {
            $model = CapRT::select('cap_rt.cap_rt', 'cap_rt.cap_rt_id', 'rt.rt', 'rt.rw_id', 'rt.kelurahan_id')
                ->join('rt', 'rt.rt_id', 'cap_rt.rt_id')
                ->when(!empty($request->kelurahan_id), function ($query) use ($request) {
                    $query->where('rt.kelurahan_id', $request->kelurahan_id);
                    if (!empty($request->rw_id)) {
                        $query->where('rt.rw_id', $request->rw_id);
                    }
                })
                ->orderBy('cap_rt_id', 'DESC')
                ->get();
        }

        $datatableButtons = method_exists(new CapRT, 'datatableButtons') ? CapRT::datatableButtons() : ['show', 'edit', 'destroy'];

        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function ($model) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show'         => in_array("show", $datatableButtons) ? route('Master.CapRT' . '.show', \Crypt::encryptString($model->cap_rt_id)) : null,
                    'edit'         => in_array("edit", $datatableButtons) ? route('Master.CapRT' . '.edit', \Crypt::encryptString($model->cap_rt_id)) : null,
                    'ajax_destroy' => in_array("destroy", $datatableButtons) ? $model->cap_rt_id : null
                ]);
            })
            ->addColumn('cap_rt', function ($model) {
                if ($model->cap_rt) {
                    return '<img src=' . helper::imageLoad('cap_rt', $model->cap_rt) . ' width="100" height="100" />';
                } else {
                    return '<img src=' . asset('images/NoPic.png') . ' width="100" height="100">';
                }
            })
            ->rawColumns(['action', 'cap_rt'])
            ->make(true);
    }

    public function store($request)
    {
        $input = $request->except('proengsoft_jsvalidation', 'rw_id', 'kelurahan_id');
        \DB::beginTransaction();

        try {
            if ($request->hasFile('cap_rt')) {
                $input['cap_rt'] = 'cap_rt_' . rand() . '.' . $request->cap_rt->getClientOriginalExtension();
                $request->cap_rt->move(public_path('uploaded_files/cap_rt'), $input['cap_rt']);
            }

            $this->capRT->create($input);

            \DB::commit();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function update($request, $id)
    {
        $model = $this->capRT->findOrFail($id);
        $input = $request->except('proengsoft_jsvalidation', 'rw_id', 'kelurahan_id');

        if ($request->hasFile('cap_rt')) {
            $input['cap_rt'] = 'cap_rt_' . rand() . '.' . $request->cap_rt->getClientOriginalExtension();
            $request->cap_rt->move(public_path('uploaded_files/cap_rt'), $input['cap_rt']);

            \File::delete(public_path('uploaded_files/cap_rt/' . $model->cap_rt));
        }

        \DB::beginTransaction();
        try {
            $model->update($input);

            \DB::commit();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function delete($id)
    {
        $model = $this->capRT->findOrFail($id);

        try {

            if ($model->cap_rt) {
                \File::delete(public_path('uploaded_files/cap_rt/' . $model->cap_rt));
            }

            $this->capRT->destroy($id);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }
}
