<?php

namespace App\Repositories\Master;
use App\Models\Master\RW;
use App\Repositories\RajaOngkir\RajaOngkirRepository;

class RWRepository
{
    public function __construct(RajaOngkirRepository $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    public function dataTables($request)
    {
        if (request()->ajax()) {
            $model = \DB::table('rw')
                ->select('rw.rw_id', 'rw.rw', 'kelurahan.kelurahan_id', 'kelurahan.nama as kelurahan')
                ->leftjoin('kelurahan', 'kelurahan.kelurahan_id', 'rw.kelurahan_id')
                ->when(!empty($request->province_id), function ($query) use ($request) {
                    $query->where('rw.province_id', $request->province_id);
                    if (!empty($request->city_id)) {
                        $query->where('rw.city_id', $request->city_id);
                    }
                    if (!empty($request->subdistrict_id)) {
                        $query->where('rw.subdistrict_id', $request->subdistrict_id);
                    }
                    if (!empty($request->kelurahan_id)) {
                        $query->where('rw.kelurahan_id', $request->kelurahan_id);
                    }
                })
                ->orderBy('rw', 'ASC')
                ->get();
        }
        
        $datatableButtons = method_exists(new RW, 'datatableButtons') ? RW::datatableButtons() : ['show', 'edit', 'destroy'];

        return \DataTables::of($model)
                          ->addIndexColumn()
                          ->addColumn('action',function($model) use($datatableButtons){
                            return view('partials.buttons.cust-datatable',[
                                'show2'         => ['name' => 'Detail', 'route' => route('Master.rw'.'.show', \Crypt::encryptString($model->rw_id))],
                                'edit2'         => ['name' => 'Edit', 'route' => route('Master.rw'.'.edit', \Crypt::encryptString($model->rw_id))],
                                'ajaxDestroy2'  => ['name' => 'Delete', 'id' => $model->rw_id]
                            ]);
                          })
                          ->rawColumns(['action'])
                          ->make(true);
    }

    public function store($request)
    {
        try {
            RW::create($request->except('proengsoft_jsvalidation'));
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        // return RW::select(
        //     'rw.rw_id',
        //     'rw.rw',
        //     'kelurahan.kelurahan_id',
        //     'kelurahan.nama as kelurahan',
        // )
        // ->join('kelurahan', 'kelurahan.kelurahan_id', 'rw.kelurahan_id')
        // ->findOrFail($id);

        return collect(\DB::select("SELECT rwTbl.rw_id, rwTbl.rw, kelurahanTbl.kelurahan_id, kelurahanTbl.nama FROM rw as rwTbl LEFT OUTER JOIN kelurahan as kelurahanTbl ON kelurahanTbl.kelurahan_id = rwTbl.kelurahan_id WHERE rwTbl.rw_id = '$id'"))->first();
    }

    public function update($request,$id)
    {
        $data = RW::findOrFail($id);

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
            RW::destroy($id);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function getAlamat($id)
    {
        $rwGetAlamat = \DB::table('rw')->where('rw_id', $id)->first();
        $rw = [
            "subdistrict_id"   => "",
            "province_id"      => "",
            "province"         => "",
            "city_id"          => "",
            "city"             => "",
            "type"             => "",
            "subdistrict_name" => "",
            "rw"           => ""
        ];

        if($rwGetAlamat)
        {
            if (empty($rwGetAlamat->subdistrict_id)) {
                $rw = json_decode($this->rajaOngkir->getSubdistrictDetailById(1579), true);
            } else {
                $rw = json_decode($this->rajaOngkir->getSubdistrictDetailById($rwGetAlamat->subdistrict_id), true);
            }
            $rw['rw'] = $rwGetAlamat->rw;
        }

        return $rw;
    }
}