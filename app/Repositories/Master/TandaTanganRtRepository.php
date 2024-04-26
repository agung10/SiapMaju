<?php

namespace App\Repositories\Master;

use App\Models\Master\TandaTanganRT;
use App\Helpers\helper;

class TandaTanganRtRepository
{
    public function __construct(TandaTanganRT $_TandaTanganRT)
    {
        $this->ttdRT = $_TandaTanganRT;
    }

    public function dataTables($request)
    {
        if (request()->ajax()) {
            $model = \DB::table('tanda_tangan_rt')
                ->select('tanda_tangan_rt.tanda_tangan_rt_id', 'tanda_tangan_rt.tanda_tangan_rt', 'rt.rt')
                ->join('rt', 'rt.rt_id', 'tanda_tangan_rt.rt_id')
                ->when(!empty($request->kelurahan_id), function ($query) use ($request) {
                    $query->where('rt.kelurahan_id', $request->kelurahan_id);
                    if (!empty($request->rw_id)) {
                        $query->where('rt.rw_id', $request->rw_id);
                    }
                })
                ->orderBy('tanda_tangan_rt_id', 'DESC')
                ->get();
        }

        $datatableButtons = method_exists(new TandaTanganRT, 'datatableButtons') ? TandaTanganRT::datatableButtons() : ['show', 'edit', 'destroy'];

        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function ($model) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show'         => in_array("show", $datatableButtons) ? route('Master.Tanda_Tangan_RT' . '.show', \Crypt::encryptString($model->tanda_tangan_rt_id)) : null,
                    'edit'         => in_array("edit", $datatableButtons) ? route('Master.Tanda_Tangan_RT' . '.edit', \Crypt::encryptString($model->tanda_tangan_rt_id)) : null,
                    'ajax_destroy' => in_array("destroy", $datatableButtons) ? $model->tanda_tangan_rt_id : null
                ]);
            })
            ->addColumn('tanda_tangan_rt', function ($model) {
                if ($model->tanda_tangan_rt) {
                    return '<img src=' . Helper::imageLoad('tanda_tangan_rt', $model->tanda_tangan_rt) . ' width="100" height="100" />';
                } else {
                    return '<img src=' . asset('images/NoPic.png') . ' width="100" height="100">';
                }
            })
            ->rawColumns(['action', 'tanda_tangan_rt'])
            ->make(true);
    }

    public function store($request)
    {
        $input = $request->except('proengsoft_jsvalidation', 'rw_id', 'kelurahan_id');
        \DB::beginTransaction();

        try {
            if ($request->hasFile('tanda_tangan_rt')) {
                $input['tanda_tangan_rt'] = 'tanda_tangan_rt_' . rand() . '.' . $request->tanda_tangan_rt->getClientOriginalExtension();
                $request->tanda_tangan_rt->move(public_path('uploaded_files/tanda_tangan_rt'), $input['tanda_tangan_rt']);
            }

            $this->ttdRT->create($input);

            \DB::commit();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function update($request, $id)
    {
        $model = $this->ttdRT->findOrFail($id);
        $input = $request->except('proengsoft_jsvalidation', 'rw_id', 'kelurahan_id');

        if ($request->hasFile('tanda_tangan_rt')) {
            $input['tanda_tangan_rt'] = 'tanda_tangan_rt_' . rand() . '.' . $request->tanda_tangan_rt->getClientOriginalExtension();
            $request->tanda_tangan_rt->move(public_path('uploaded_files/tanda_tangan_rt'), $input['tanda_tangan_rt']);

            \File::delete(public_path('uploaded_files/tanda_tangan_rt/' . $model->tanda_tangan_rt));
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
        $model = $this->ttdRT->findOrFail($id);

        try {

            if ($model->tanda_tangan_rt) {
                \File::delete(public_path('uploaded_files/tanda_tangan_rt/' . $model->tanda_tangan_rt));
            }

            $this->ttdRT->destroy($id);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }
}
