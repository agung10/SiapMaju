<?php

namespace App\Repositories\UMKM;

use App\Models\UMKM\Kategori;

class KategoriRepository
{
    public function dataTables()
    {
        $model = Kategori::select('umkm_kategori_id', 'nama', 'keterangan')->get();
        $datatableButtons = method_exists(new Kategori, 'datatableButtons') ? Kategori::datatableButtons() : ['show', 'edit', 'destroy'];

        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function ($model) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show'         => in_array("show", $datatableButtons) ? route('UMKM.Kategori' . '.show', \Crypt::encryptString($model->umkm_kategori_id)) : null,
                    'edit'         => in_array("edit", $datatableButtons) ? route('UMKM.Kategori' . '.edit', \Crypt::encryptString($model->umkm_kategori_id)) : null,
                    'ajax_destroy' => in_array("destroy", $datatableButtons) ? $model->umkm_kategori_id : null
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store($request)
    {

        try {
            Kategori::create($request->except('proengsoft_jsvalidation'));
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        return Kategori::select('umkm_kategori_id', 'nama', 'keterangan')->findOrFail($id);
    }

    public function update($request, $id)
    {
        $data = Kategori::findOrFail($id);

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
            Kategori::destroy($id);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }
}
