<?php

namespace App\Repositories\UMKM;

use App\Models\UMKM\Medsos;

class MedsosRepository
{
    public function __construct(Medsos $medsos)
    {
        $this->medsos = $medsos;
    }

    public function store($request)
    {
        $input = $request->except('proengsoft_jsvalidation');

        \DB::beginTransaction();

        try {

            if ($request->hasFile('icon')) {
                $input['icon'] = 'icon' . rand() . '.' . $request->icon->getClientOriginalExtension();
                $request->icon->move(public_path('uploaded_files/medsos'), $input['icon']);
            }

            $this->medsos->create($input);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function update($request, $id)
    {
        $model = $this->medsos->findOrFail($id);

        $input = $request->except('proengsoft_jsvalidation');
        $input['updated_at'] = date('Y-m-d H:i:s');

        \DB::beginTransaction();

        try {
            if ($request->hasFile('icon')) {
                $input['icon'] = 'icon' . rand() . '.' . $request->icon->getClientOriginalExtension();
                $request->icon->move(public_path('uploaded_files/medsos'), $input['icon']);

                \File::delete(public_path('uploaded_files/medsos/' . $model->icon));
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
        $model = $this->medsos->findOrFail($id);

        try {

            if ($model->icon) {
                \File::delete(public_path('uploaded_files/medsos/' . $model->icon));
            }

            $this->medsos->destroy($id);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function dataTablesMedsos()
    {
        $datatableButtons = method_exists(new $this->medsos, 'datatableButtons') ? $this->medsos->datatableButtons() : ['show', 'edit', 'destroy'];
        $model = \DB::table('medsos')
            ->select('medsos.medsos_id', 'medsos.nama', 'medsos.icon')
            ->orderBy('created_at', 'desc')
            ->get();
        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('icon', function ($model) {
                if ($model->icon) {
                    return '<embed src=' . asset('uploaded_files/medsos/' . $model->icon) . ' width="55" height="55" ></embed>';
                } else {
                    return '<img src=' . asset('images/NoPic.png') . ' width="55" height="55">';
                }
            })
            ->addColumn('action', function ($data) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show'         => in_array("show", $datatableButtons) ? route('UMKM.Medsos' . '.show', \Crypt::encryptString($data->medsos_id)) : null,
                    'edit'         => in_array("edit", $datatableButtons) ? route('UMKM.Medsos' . '.edit', \Crypt::encryptString($data->medsos_id)) : null,
                    'ajax_destroy' => in_array("destroy", $datatableButtons) ? $data->medsos_id : null
                ]);
            })
            ->rawColumns(['icon', 'action'])
            ->make(true);
    }

    public function selectMedsos()
    {
        $data = \DB::table('medsos')
            ->select(
                'medsos.medsos_id',
                'medsos.nama'
            )
            ->orderBy('nama')
            ->pluck('nama', 'medsos_id');

        return $data;
    }
}
