<?php

namespace App\Repositories\Master;

use App\Models\Master\CapRW;
use App\Helpers\helper;

class CapRWRepository
{
    public function __construct(CapRW $CapRW)
    {
        $this->capRW = $CapRW;
    }

    public function dataTables($request)
    {
        if (request()->ajax()) {
            $model = CapRW::select('cap_rw.cap_rw', 'rw.rw', 'cap_rw.cap_rw_id')
                ->join('rw', 'rw.rw_id', 'cap_rw.rw_id')
                ->when(!empty($request->kelurahan_id), function ($query) use ($request) {
                    $query->where('rw.kelurahan_id', $request->kelurahan_id);
                })
                ->orderBy('cap_rw_id', 'DESC')
                ->get();
        }

        $datatableButtons = method_exists(new CapRW, 'datatableButtons') ? CapRW::datatableButtons() : ['show', 'edit', 'destroy'];

        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function ($model) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show'         => in_array("show", $datatableButtons) ? route('Master.CapRW' . '.show', \Crypt::encryptString($model->cap_rw_id)) : null,
                    'edit'         => in_array("edit", $datatableButtons) ? route('Master.CapRW' . '.edit', \Crypt::encryptString($model->cap_rw_id)) : null,
                    'ajax_destroy' => in_array("destroy", $datatableButtons) ? $model->cap_rw_id : null
                ]);
            })
            ->addColumn('cap_rw', function ($model) {
                if ($model->cap_rw) {
                    return '<img src=' . helper::imageLoad('cap_rw', $model->cap_rw) . ' width="100" height="100" />';
                } else {
                    return '<img src=' . asset('images/NoPic.png') . ' width="100" height="100">';
                }
            })
            ->rawColumns(['action', 'cap_rw'])
            ->make(true);
    }

    public function store($request)
    {
        $input = $request->except('proengsoft_jsvalidation', 'kelurahan_id');
        \DB::beginTransaction();

        try {
            if ($request->hasFile('cap_rw')) {
                $input['cap_rw'] = 'cap_rw_' . rand() . '.' . $request->cap_rw->getClientOriginalExtension();
                $request->cap_rw->move(public_path('uploaded_files/cap_rw'), $input['cap_rw']);
            }

            $this->capRW->create($input);

            \DB::commit();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function update($request, $id)
    {
        $model = $this->capRW->findOrFail($id);
        $input = $request->except('proengsoft_jsvalidation', 'kelurahan_id');

        if ($request->hasFile('cap_rw')) {
            $input['cap_rw'] = 'cap_rw_' . rand() . '.' . $request->cap_rw->getClientOriginalExtension();
            $request->cap_rw->move(public_path('uploaded_files/cap_rw'), $input['cap_rw']);

            \File::delete(public_path('uploaded_files/cap_rw/' . $model->cap_rw));
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
        $model = $this->capRW->findOrFail($id);

        try {

            if ($model->cap_rw) {
                \File::delete(public_path('uploaded_files/cap_rw/' . $model->cap_rw));
            }

            $this->capRW->destroy($id);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }
}
