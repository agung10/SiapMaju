<?php

namespace App\Repositories\Master;

use App\Models\Master\TandaTanganRW;
use App\Helpers\helper;

class TandaTanganRWRepository
{
    public function __construct(TandaTanganRW $TandaTanganRW)
    {
        $this->ttdRW = $TandaTanganRW;
    }

    public function dataTables($request)
    {
        if (request()->ajax()) {
            $model = TandaTanganRW::select('tanda_tangan_rw.tanda_tangan_rw', 'tanda_tangan_rw.tanda_tangan_rw_id', 'rw.rw')
                ->join('rw', 'rw.rw_id', 'tanda_tangan_rw.rw_id')
                ->when(!empty($request->kelurahan_id), function ($query) use ($request) {
                    $query->where('rw.kelurahan_id', $request->kelurahan_id);
                })
                ->orderBy('tanda_tangan_rw_id', 'DESC')
                ->get();
        }

        $datatableButtons = method_exists(new TandaTanganRW, 'datatableButtons') ? TandaTanganRW::datatableButtons() : ['show', 'edit', 'destroy'];

        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function ($model) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show'         => in_array("show", $datatableButtons) ? route('Master.TandaTanganRW' . '.show', \Crypt::encryptString($model->tanda_tangan_rw_id)) : null,
                    'edit'         => in_array("edit", $datatableButtons) ? route('Master.TandaTanganRW' . '.edit', \Crypt::encryptString($model->tanda_tangan_rw_id)) : null,
                    'ajax_destroy' => in_array("destroy", $datatableButtons) ? $model->tanda_tangan_rw_id : null
                ]);
            })
            ->addColumn('tanda_tangan_rw', function ($model) {
                if ($model->tanda_tangan_rw) {
                    return '<img src=' . helper::imageLoad('tanda_tangan_rw', $model->tanda_tangan_rw) . ' width="100" height="100" />';
                } else {
                    return '<img src=' . asset('images/NoPic.png') . ' width="100" height="100">';
                }
            })
            ->rawColumns(['action', 'tanda_tangan_rw'])
            ->make(true);
    }

    public function store($request)
    {
        $input = $request->except('proengsoft_jsvalidation', 'kelurahan_id');
        \DB::beginTransaction();

        try {
            if ($request->hasFile('tanda_tangan_rw')) {
                $input['tanda_tangan_rw'] = 'tanda_tangan_rw_' . rand() . '.' . $request->tanda_tangan_rw->getClientOriginalExtension();
                $request->tanda_tangan_rw->move(public_path('uploaded_files/tanda_tangan_rw'), $input['tanda_tangan_rw']);
            }

            $this->ttdRW->create($input);

            \DB::commit();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function update($request, $id)
    {
        $model = $this->ttdRW->findOrFail($id);
        $input = $request->except('proengsoft_jsvalidation', 'kelurahan_id');

        if ($request->hasFile('tanda_tangan_rw')) {
            $input['tanda_tangan_rw'] = 'tanda_tangan_rw_' . rand() . '.' . $request->tanda_tangan_rw->getClientOriginalExtension();
            $request->tanda_tangan_rw->move(public_path('uploaded_files/tanda_tangan_rw'), $input['tanda_tangan_rw']);

            \File::delete(public_path('uploaded_files/tanda_tangan_rw/' . $model->tanda_tangan_rw));
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
        $model = $this->ttdRW->findOrFail($id);

        try {

            if ($model->tanda_tangan_rw) {
                \File::delete(public_path('uploaded_files/tanda_tangan_rw/' . $model->tanda_tangan_rw));
            }

            $this->ttdRW->destroy($id);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }
}
