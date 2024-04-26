<?php

namespace App\Repositories;

use App\Models\Header;
use App\Helpers\helper;
use App\Repositories\RajaOngkir\RajaOngkirRepository;
use App\Repositories\RoleManagement\UserRepository;

class HeaderRepository
{
    public function __construct(Header $_Header, RajaOngkirRepository $rajaOngkir, UserRepository $_user)
    {
        $this->header = $_Header;
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

        $datatableButtons = method_exists(new $this->header, 'datatableButtons') ? $this->header->datatableButtons() : ['show', 'edit', 'destroy'];

        if (request()->ajax()) {
            $model = \DB::table('header')
                ->select('header_id', 'header.image', 'header.judul', 'header.keterangan')
                ->when($isRw, function ($query) use ($rwID) {
                    $query->where('header.rw_id', $rwID);
                })
                ->when($isRt, function ($query) use ($rtID) {
                    $query->where('header.rt_id', $rtID);
                })
                ->when(!empty($request->kelurahan_id), function ($query) use ($request) {
                    $query->where('header.kelurahan_id', $request->kelurahan_id);
                    if (!empty($request->rw_id)) {
                        $query->where('header.rw_id', $request->rw_id);
                    }
                    if (!empty($request->rt_id)) {
                        $query->where('header.rt_id', $request->rt_id);
                    }
                })
                ->orderBy('header.updated_at', 'DESC')
                ->get();
        }

        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('image', function ($model) {
                return '<img src=' . asset('upload/header/' . $model->image) . ' width="200" height="200">';
            })
            ->addColumn('action', function ($data) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show2'         => ['name' => 'Detail', 'route' => route('Beranda.Header' . '.show', \Crypt::encryptString($data->header_id))],
                    'edit2'         => ['name' => 'Edit', 'route' => route('Beranda.Header' . '.edit', \Crypt::encryptString($data->header_id))],
                    'ajaxDestroy2'  => ['name' => 'Delete', 'id' => $data->header_id]
                ]);
            })
            ->rawColumns(['image', 'action'])
            ->make(true);
    }

    public function show($id)
    {
        $data =  collect(
            \DB::select(
                "SELECT headerTbl.header_id, headerTbl.image, headerTbl.judul, headerTbl.keterangan, rtTbl.rt_id, rtTbl.rt, rwTbl.rw_id, rwTbl.rw, kelurahanTbl.kelurahan_id, kelurahanTbl.nama 
                FROM header as headerTbl 
                LEFT OUTER JOIN rt as rtTbl ON rtTbl.rt_id = headerTbl.rt_id 
                LEFT OUTER JOIN rw as rwTbl ON rwTbl.rw_id = headerTbl.rw_id 
                LEFT OUTER JOIN kelurahan as kelurahanTbl ON kelurahanTbl.kelurahan_id = headerTbl.kelurahan_id 
                WHERE headerTbl.header_id = '$id'"
            )
        )->first();

        return $data;
    }

    public function store($request)
    {
        $input = $request->except('proengsoft_jsvalidation');
        $transaction = false;

        \DB::beginTransaction();

        try {
            $input['date_created'] = date('Y-m-d H:i:s');

            if ($request->hasFile('image')) {
                $input['image'] = 'header_' . rand() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('upload/header'), $input['image']);
            }

            $this->header->create($input);

            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function update($request, $id)
    {
        $model = $this->header->findOrFail($id);

        $inputs = $request->except('proengsoft_jsvalidation');
        $inputs['date_updated'] = date('Y-m-d H:i:s');
        $inputs['user_updated'] = \Auth::user()->user_id;

        \DB::beginTransaction();

        try {
            if ($request->hasFile('image')) {
                $inputs['image'] = 'header_' . rand() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('upload/header'), $inputs['image']);

                \File::delete(public_path('upload/header/' . $model->image));
            }

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
        $model = $this->header->findOrFail($id);

        try {

            if ($model->image) {
                \File::delete(public_path('upload/header/' . $model->image));
            }

            $this->header->destroy($id);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function getAlamat($id)
    {
        $headerGetAlamat = \DB::table('header')->where('header_id', $id)->first();
        $judul = [
            "subdistrict_id"   => "",
            "province_id"      => "",
            "province"         => "",
            "city_id"          => "",
            "city"             => "",
            "type"             => "",
            "subdistrict_name" => "",
            "judul"        => ""
        ];

        if ($headerGetAlamat) {
            if (empty($headerGetAlamat->subdistrict_id)) {
                $judul = json_decode($this->rajaOngkir->getSubdistrictDetailById(1579), true);
            } else {
                $judul = json_decode($this->rajaOngkir->getSubdistrictDetailById($headerGetAlamat->subdistrict_id), true);
            }
            $judul['judul'] = $headerGetAlamat->judul;
        }

        return $judul;
    }
}
