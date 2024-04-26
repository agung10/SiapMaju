<?php

namespace App\Repositories;

use App\Models\Kontak;
use App\Helpers\helper;
use App\Repositories\RajaOngkir\RajaOngkirRepository;
use App\Repositories\RoleManagement\UserRepository;

class KontakRepository
{
    public function __construct(Kontak $_Kontak, RajaOngkirRepository $rajaOngkir, UserRepository $_user)
    {
        $this->kontak = $_Kontak;
        $this->rajaOngkir =  $rajaOngkir;
        $this->user = $_user;
    }

    public function dataTables($request)
    {
        $checkRole = \helper::checkUserRole('all');
        $isRw = $checkRole['isRw'];
        $isRt = $checkRole['isRt'];

        $getData = $this->user->currentUser();
        $rwID = $getData->rw_id;
        $rtID = $getData->rt_id;

        $datatableButtons = method_exists(new $this->kontak, 'datatableButtons') ? $this->kontak->datatableButtons() : ['show', 'edit', 'destroy'];

        if (request()->ajax()) {
            $model = \DB::table('kontak')
                ->select('kontak.alamat', 'kontak.no_telp', 'kontak.nama_lokasi', 'kontak.mobile', 'kontak.email', 'kontak.web', 'kontak.rekening', 'kontak.kontak_id')
                ->when($isRw, function ($query) use ($rwID) {
                    $query->where('kontak.rw_id', $rwID);
                })
                ->when($isRt, function ($query) use ($rtID) {
                    $query->where('kontak.rt_id', $rtID);
                })
                ->when(!empty($request->kelurahan_id), function ($query) use ($request) {
                    $query->where('kontak.kelurahan_id', $request->kelurahan_id);
                    if (!empty($request->rw_id)) {
                        $query->where('kontak.rw_id', $request->rw_id);
                    }
                    if (!empty($request->rt_id)) {
                        $query->where('kontak.rt_id', $request->rt_id);
                    }
                })
                ->orderBy('kontak.updated_at', 'DESC')
                ->get();
        }

        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show2'         => ['name' => 'Detail', 'route' => route('Beranda.Kontak' . '.show', \Crypt::encryptString($data->kontak_id))],
                    'edit2'         => ['name' => 'Edit', 'route' => route('Beranda.Kontak' . '.edit', \Crypt::encryptString($data->kontak_id))],
                    'ajaxDestroy2'  => ['name' => 'Delete', 'id' => $data->kontak_id]
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show($id)
    {
        $data =  collect(
            \DB::select(
                "SELECT kontakTbl.kontak_id, kontakTbl.alamat, kontakTbl.no_telp, kontakTbl.mobile, kontakTbl.nama_lokasi, kontakTbl.email, kontakTbl.web, kontakTbl.rekening, rtTbl.rt_id, rtTbl.rt, rwTbl.rw_id, rwTbl.rw, kelurahanTbl.kelurahan_id, kelurahanTbl.nama 
                FROM kontak as kontakTbl 
                LEFT OUTER JOIN rt as rtTbl ON rtTbl.rt_id = kontakTbl.rt_id 
                LEFT OUTER JOIN rw as rwTbl ON rwTbl.rw_id = kontakTbl.rw_id 
                LEFT OUTER JOIN kelurahan as kelurahanTbl ON kelurahanTbl.kelurahan_id = kontakTbl.kelurahan_id 
                WHERE kontakTbl.kontak_id = '$id'"
            )
        )->first();

        return $data;
    }

    public function store($request)
    {
        $input = $request->except('proengsoft_jsvalidation');
        $transaction = false;

        $rules = [
            'alamat' => 'required',
            'no_telp' => 'required',
            'mobile' => 'required',
            'nama_lokasi' => 'required',
            'email' => 'required|email',
            'web' => 'required',
            'rekening' => 'required',
        ];

        $validator = helper::validation($request->all(), $rules);
        \DB::beginTransaction();

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'errors' => $validator->getMessageBag()->toArray()]);
        }

        try {
            $input['date_created'] = date('Y-m-d H:i:s');

            $this->kontak->create($input);

            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function update($request, $id)
    {
        $model = $this->kontak->findOrFail($id);

        $inputs = $request->except('proengsoft_jsvalidation');
        $inputs['date_updated'] = date('Y-m-d H:i:s');
        $inputs['user_updated'] = \Auth::user()->user_id;

        $rules = [
            'alamat' => 'required',
            'no_telp' => 'required',
            'mobile' => 'required',
            'nama_lokasi' => 'required',
            'email' => 'required|email',
            'web' => 'required',
            'rekening' => 'required',
        ];

        $validator = helper::validation($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'errors' => $validator->getMessageBag()->toArray()]);
        }

        \DB::beginTransaction();

        try {

            $model->update($inputs);

            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function delete($id)
    {
        $model = $this->kontak->findOrFail($id);

        try {

            $this->kontak->destroy($id);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function getAlamat($id)
    {
        $kontakGetAlamat = \DB::table('kontak')->where('kontak_id', $id)->first();
        $alamat = [
            "subdistrict_id"   => "",
            "province_id"      => "",
            "province"         => "",
            "city_id"          => "",
            "city"             => "",
            "type"             => "",
            "subdistrict_name" => "",
            "alamat"        => ""
        ];

        if ($kontakGetAlamat) {
            if (empty($kontakGetAlamat->subdistrict_id)) {
                $alamat = json_decode($this->rajaOngkir->getSubdistrictDetailById(1579), true);
            } else {
                $alamat = json_decode($this->rajaOngkir->getSubdistrictDetailById($kontakGetAlamat->subdistrict_id), true);
            }
            $alamat['alamat'] = $kontakGetAlamat->alamat;
        }

        return $alamat;
    }
}
