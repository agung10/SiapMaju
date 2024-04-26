<?php

namespace App\Repositories\Master;

use App\Models\Master\TandaTanganKelurahan;

class TandaTanganKelurahanRepository
{
    public function __construct(TandaTanganKelurahan $TandaTanganKelurahan)
    {
        $this->ttdKelurahan = $TandaTanganKelurahan;
    }

    public function dataTables($request)
    {
        $datatableButtons = method_exists(new $this->ttdKelurahan, 'datatableButtons') ? $this->ttdKelurahan->datatableButtons() : ['show', 'edit', 'destroy'];

        if (request()->ajax()) {
            $model = TandaTanganKelurahan::select(
                'tanda_tangan_kelurahan.tanda_tangan_kelurahan_id',
                'tanda_tangan_kelurahan.tanda_tangan_kelurahan',
                'kelurahan.nama',
            )
                ->join('kelurahan', 'kelurahan.kelurahan_id', 'tanda_tangan_kelurahan.kelurahan_id')
                ->when(!empty($request->province_id), function ($query) use ($request) {
                    $query->where('kelurahan.province_id', $request->province_id);
                    if (!empty($request->city_id)) {
                        $query->where('kelurahan.city_id', $request->city_id);
                    }
                    if (!empty($request->subdistrict_id)) {
                        $query->where('kelurahan.subdistrict_id', $request->subdistrict_id);
                    }
                })
                ->get();
        }

        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('tanda_tangan_kelurahan', function ($model) {
                if ($model->tanda_tangan_kelurahan) {
                    return '<img src=' . asset('uploaded_files/tanda_tangan_kelurahan/' . $model->tanda_tangan_kelurahan) . ' width="100" height="100" ></img>';
                } else {
                    return '<img src=' . asset('images/NoPic.png') . ' width="100" height="100">';
                }
            })
            ->addColumn('action', function ($data) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show'         => in_array("show", $datatableButtons) ? route('Master.TandaTanganKelurahan' . '.show', \Crypt::encryptString($data->tanda_tangan_kelurahan_id)) : null,
                    'edit'         => in_array("edit", $datatableButtons) ? route('Master.TandaTanganKelurahan' . '.edit', \Crypt::encryptString($data->tanda_tangan_kelurahan_id)) : null,
                    'ajax_destroy' => in_array("destroy", $datatableButtons) ? $data->tanda_tangan_kelurahan_id : null
                ]);
            })
            ->rawColumns(['tanda_tangan_kelurahan', 'action'])
            ->make(true);
    }

    public function store($request)
    {
        $input = $request->except('proengsoft_jsvalidation', 'province_id', 'city_id', 'subdistrict_id');
        \DB::beginTransaction();

        try {
            if ($request->hasFile('tanda_tangan_kelurahan')) {
                $input['tanda_tangan_kelurahan'] = 'tanda_tangan_kelurahan_' . rand() . '.' . $request->tanda_tangan_kelurahan->getClientOriginalExtension();
                $request->tanda_tangan_kelurahan->move(public_path('uploaded_files/tanda_tangan_kelurahan'), $input['tanda_tangan_kelurahan']);
            }

            $this->ttdKelurahan->create($input);

            \DB::commit();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function update($request, $id)
    {
        $model = $this->ttdKelurahan->findOrFail($id);
        $input = $request->except('proengsoft_jsvalidation', 'province_id', 'city_id', 'subdistrict_id');

        \DB::beginTransaction();

        try {
            if ($request->hasFile('tanda_tangan_kelurahan')) {
                $input['tanda_tangan_kelurahan'] = 'tanda_tangan_kelurahan_' . rand() . '.' . $request->tanda_tangan_kelurahan->getClientOriginalExtension();
                $request->tanda_tangan_kelurahan->move(public_path('uploaded_files/tanda_tangan_kelurahan'), $input['tanda_tangan_kelurahan']);

                \File::delete(public_path('uploaded_files/tanda_tangan_kelurahan/' . $model->tanda_tangan_kelurahan));
            }

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
        $model = $this->ttdKelurahan->findOrFail($id);

        try {

            if ($model->tanda_tangan_kelurahan) {
                \File::delete(public_path('uploaded_files/tanda_tangan_kelurahan/' . $model->tanda_tangan_kelurahan));
            }

            $this->ttdKelurahan->destroy($id);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }
}