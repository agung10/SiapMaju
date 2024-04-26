<?php

namespace App\Repositories\Master;

use App\Models\Master\Kelurahan;
use App\Repositories\RajaOngkir\RajaOngkirRepository;

class KelurahanRepository
{
    public function __construct(RajaOngkirRepository $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    public function dataTables($request)
    {
        if (request()->ajax()) {
            $model = Kelurahan::select('kelurahan.kelurahan_id', 'kelurahan.nama as kelurahan', 'kelurahan.kode_pos')
                ->when(!empty($request->province_id), function ($query) use ($request) {
                    $query->where('kelurahan.province_id', $request->province_id);
                    if (!empty($request->city_id)) {
                        $query->where('kelurahan.city_id', $request->city_id);
                    }
                    if (!empty($request->subdistrict_id)) {
                        $query->where('kelurahan.subdistrict_id', $request->subdistrict_id);
                    }
                })
                ->orderBy('kelurahan', 'ASC')
                ->get();
        }
        $datatableButtons = method_exists(new Kelurahan, 'datatableButtons') ? Kelurahan::datatableButtons() : ['show', 'edit', 'destroy'];

        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function ($model) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show2'         => ['name' => 'Detail', 'route' => route('Master.kelurahan' . '.show', \Crypt::encryptString($model->kelurahan_id))],
                    'edit2'         => ['name' => 'Edit', 'route' => route('Master.kelurahan' . '.edit', \Crypt::encryptString($model->kelurahan_id))],
                    'ajaxDestroy2'  => ['name' => 'Delete', 'id' => $model->kelurahan_id]
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store($request)
    {
        try {
            Kelurahan::create($request->except('proengsoft_jsvalidation'));
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function update($request, $id)
    {
        $data = Kelurahan::findOrFail($id);
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
            Kelurahan::destroy($id);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function getAlamat($id)
    {
        $kelurahanGetAlamat = \DB::table('kelurahan')->where('kelurahan_id', $id)->first();
        $nama = [
            "subdistrict_id"   => "",
            "province_id"      => "",
            "province"         => "",
            "city_id"          => "",
            "city"             => "",
            "type"             => "",
            "subdistrict_name" => "",
            "nama"           => ""
        ];

        if ($kelurahanGetAlamat) {
            if (empty($kelurahanGetAlamat->subdistrict_id)) {
                $nama = json_decode($this->rajaOngkir->getSubdistrictDetailById(1579), true);
            } else {
                $nama = json_decode($this->rajaOngkir->getSubdistrictDetailById($kelurahanGetAlamat->subdistrict_id), true);
            }
            $nama['nama'] = $kelurahanGetAlamat->nama;
        }

        return $nama;
    }
}
