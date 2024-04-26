<?php

namespace App\Repositories\Master;

use App\Models\Master\CapKelurahan;

class CapKelurahanRepository
{
    public function __construct(CapKelurahan $CapKelurahan)
    {
        $this->capKelurahan = $CapKelurahan;
    }

    public function dataTables($request)
    {
        $datatableButtons = method_exists(new $this->capKelurahan, 'datatableButtons') ? $this->capKelurahan->datatableButtons() : ['show', 'edit', 'destroy'];

        if (request()->ajax()) {
            $model = CapKelurahan::select(
                'cap_kelurahan.cap_kelurahan_id',
                'cap_kelurahan.cap_kelurahan',
                'kelurahan.nama',
            )
                ->join('kelurahan', 'kelurahan.kelurahan_id', 'cap_kelurahan.kelurahan_id')
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
            ->addColumn('cap_kelurahan', function ($model) {
                if ($model->cap_kelurahan) {
                    return '<img src=' . asset('uploaded_files/cap_kelurahan/' . $model->cap_kelurahan) . ' width="100" height="100" ></img>';
                } else {
                    return '<img src=' . asset('images/NoPic.png') . ' width="100" height="100">';
                }
            })
            ->addColumn('action', function ($data) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show'         => in_array("show", $datatableButtons) ? route('Master.CapKelurahan' . '.show', \Crypt::encryptString($data->cap_kelurahan_id)) : null,
                    'edit'         => in_array("edit", $datatableButtons) ? route('Master.CapKelurahan' . '.edit', \Crypt::encryptString($data->cap_kelurahan_id)) : null,
                    'ajax_destroy' => in_array("destroy", $datatableButtons) ? $data->cap_kelurahan_id : null
                ]);
            })
            ->rawColumns(['cap_kelurahan', 'action'])
            ->make(true);
    }

    public function store($request)
    {
        $input = $request->except('proengsoft_jsvalidation', 'province_id', 'city_id', 'subdistrict_id');
        \DB::beginTransaction();

        try {
            if ($request->hasFile('cap_kelurahan')) {
                $input['cap_kelurahan'] = 'cap_kelurahan_' . rand() . '.' . $request->cap_kelurahan->getClientOriginalExtension();
                $request->cap_kelurahan->move(public_path('uploaded_files/cap_kelurahan'), $input['cap_kelurahan']);
            }

            $this->capKelurahan->create($input);

            \DB::commit();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function update($request, $id)
    {
        $model = $this->capKelurahan->findOrFail($id);
        $input = $request->except('proengsoft_jsvalidation', 'province_id', 'city_id', 'subdistrict_id');

        \DB::beginTransaction();

        try {
            if ($request->hasFile('cap_kelurahan')) {
                $input['cap_kelurahan'] = 'cap_kelurahan_' . rand() . '.' . $request->cap_kelurahan->getClientOriginalExtension();
                $request->cap_kelurahan->move(public_path('uploaded_files/cap_kelurahan'), $input['cap_kelurahan']);

                \File::delete(public_path('uploaded_files/cap_kelurahan/' . $model->cap_kelurahan));
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
        $model = $this->capKelurahan->findOrFail($id);

        try {

            if ($model->cap_kelurahan) {
                \File::delete(public_path('uploaded_files/cap_kelurahan/' . $model->cap_kelurahan));
            }

            $this->capKelurahan->destroy($id);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }
}
