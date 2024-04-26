<?php

namespace App\Repositories\Master;
use App\Models\Master\RT;
use App\Repositories\RajaOngkir\RajaOngkirRepository;

class RTRepository
{
    public function __construct(RajaOngkirRepository $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    public function dataTables($request)
    {
        if (request()->ajax()) {
            $model = \DB::table('rt')
                ->select('rt.rt_id', 'rt.rt', 'rt.no_akhir_surat', 'rw.rw_id', 'rw.rw', 'kelurahan.kelurahan_id', 'kelurahan.nama as kelurahan')
                ->leftjoin('rw', 'rw.rw_id', 'rt.rw_id')
                ->leftjoin('kelurahan', 'kelurahan.kelurahan_id', 'rt.kelurahan_id')
                ->when(!empty($request->province_id), function ($query) use ($request) {
                    $query->where('rt.province_id', $request->province_id);
                    if (!empty($request->city_id)) {
                        $query->where('rt.city_id', $request->city_id);
                    }
                    if (!empty($request->subdistrict_id)) {
                        $query->where('rt.subdistrict_id', $request->subdistrict_id);
                    }
                    if (!empty($request->kelurahan_id)) {
                        $query->where('rt.kelurahan_id', $request->kelurahan_id);
                    }
                    if (!empty($request->rw_id)) {
                        $query->where('rt.rw_id', $request->rw_id);
                    }
                })
                ->orderBy('rt', 'ASC')
                ->get();
        }

        $datatableButtons = method_exists(new RT, 'datatableButtons') ? RT::datatableButtons() : ['show', 'edit', 'destroy'];

        return \DataTables::of($model)
                           ->addIndexColumn()
                           ->addColumn('action',function($model) use($datatableButtons){
                                return view('partials.buttons.cust-datatable',[
                                    'show2'         => ['name' => 'Detail', 'route' => route('Master.rt'.'.show', \Crypt::encryptString($model->rt_id))],
                                    'edit2'         => ['name' => 'Edit', 'route' => route('Master.rt'.'.edit', \Crypt::encryptString($model->rt_id))],
                                    'ajaxDestroy2'  => ['name' => 'Delete', 'id' => $model->rt_id]
                                ]);
                           })
                           ->rawColumns(['action'])
                           ->make(true);
    }

    public function store($request)
    {
        try {
            RT::create($request->except('proengsoft_jsvalidation'));
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        // return RT::select(
        //     'rt.rt_id',
        //     'rt.rt',
        //     'rw.rw_id',
        //     'rw.rw',
        //     'kelurahan.kelurahan_id',
        //     'kelurahan.nama as kelurahan',
        // )
        // ->join('rw', 'rw.rw_id', 'rt.rw_id')
        // ->join('kelurahan', 'kelurahan.kelurahan_id', 'rw.kelurahan_id')
        // ->findOrFail($id);

        return collect(\DB::select("SELECT rtTbl.rt_id, rtTbl.rt, rwTbl.rw_id, rwTbl.rw, kelurahanTbl.kelurahan_id, kelurahanTbl.nama FROM rt as rtTbl LEFT OUTER JOIN rw as rwTbl ON rwTbl.rw_id = rtTbl.rw_id LEFT OUTER JOIN kelurahan as kelurahanTbl ON kelurahanTbl.kelurahan_id = rtTbl.kelurahan_id WHERE rtTbl.rt_id = '$id'"))->first();
    }

    public function update($request,$id)
    {
        $data = RT::findOrFail($id);
        
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
            RT::destroy($id);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function getAlamat($id)
    {
        $rtGetAlamat = \DB::table('rt')->where('rt_id', $id)->first();
        $rt = [
            "subdistrict_id"   => "",
            "province_id"      => "",
            "province"         => "",
            "city_id"          => "",
            "city"             => "",
            "type"             => "",
            "subdistrict_name" => "",
            "rt"           => ""
        ];

        if($rtGetAlamat)
        {
            if (empty($rtGetAlamat->subdistrict_id)) {
                $rt = json_decode($this->rajaOngkir->getSubdistrictDetailById(1579), true);
            } else {
                $rt = json_decode($this->rajaOngkir->getSubdistrictDetailById($rtGetAlamat->subdistrict_id), true);
            }
            $rt['rt'] = $rtGetAlamat->rt;
        }

        return $rt;
    }
}