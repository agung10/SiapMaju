<?php

namespace App\Repositories;

use App\Models\Pengumuman;
use App\Helpers\helper;
use App\Repositories\RajaOngkir\RajaOngkirRepository;
use App\Repositories\RoleManagement\UserRepository;
use App\Events\PengumumanCreated;

class PengumumanRepository
{
    public function __construct(Pengumuman $_Pengumuman, RajaOngkirRepository $rajaOngkir, UserRepository $_user)
    {
        $this->pengumuman = $_Pengumuman;
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

        $datatableButtons = method_exists(new $this->pengumuman, 'datatableButtons') ? $this->pengumuman->datatableButtons() : ['show', 'edit', 'destroy'];
        
        if (request()->ajax()) {
            $model  = \DB::table('pengumuman')
                ->select(
                    'pengumuman.pengumuman_id',
                    'pengumuman.pengumuman',
                    'pengumuman.image1',
                    'pengumuman.image2',
                    'pengumuman.image3',
                    'pengumuman.tanggal',
                    'pengumuman.aktif'
                )
                ->when($isRw, function ($query) use ($rwID) {
                    $query->where('pengumuman.rw_id', $rwID);
                })
                ->when($isRt, function ($query) use ($rtID) {
                    $query->where('pengumuman.rt_id', $rtID);
                })
                ->when(!empty($request->kelurahan_id), function ($query) use ($request) {
                    $query->where('pengumuman.kelurahan_id', $request->kelurahan_id);
                    if (!empty($request->rw_id)) {
                        $query->where('pengumuman.rw_id', $request->rw_id);
                    }
                    if (!empty($request->rt_id)) {
                        $query->where('pengumuman.rt_id', $request->rt_id);
                    }
                })
                ->get();
        }

        return \DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('pengumuman', function ($row) {
                return \Str::limit($row->pengumuman, 50);
            })
            ->editColumn('image1', function ($model) {
                return view('layouts.partials.image_column', ['src' => \helper::loadImgUpload('pengumuman', $model->image1)]);
            })
            ->editColumn('image2', function ($model) {
                return view('layouts.partials.image_column', ['src' => \helper::loadImgUpload('pengumuman', $model->image2)]);
            })
            ->editColumn('image3', function ($model) {
                return view('layouts.partials.image_column', ['src' => \helper::loadImgUpload('pengumuman', $model->image3)]);
            })
            ->editColumn('tanggal', function ($row) {
                return [
                    'display' => \helper::tglIndo($row->tanggal),
                    'raw' => $row->tanggal
                ];
            })
            ->addColumn('action', function ($data) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show2'         => ['name' => 'Detail', 'route' => route('Pengumuman.List' . '.show', \Crypt::encryptString($data->pengumuman_id))],
                    'edit2'         => ['name' => 'Edit', 'route' => route('Pengumuman.List' . '.edit', \Crypt::encryptString($data->pengumuman_id))],
                    'ajaxDestroy2'  => ['name' => 'Delete', 'id' => $data->pengumuman_id]
                ]);
            })
            ->addColumn('status', function ($model) {
                return view('partials.buttons.datatable', ['activePill' => $model->aktif]);
            })
            ->rawColumns(['image1', 'image2', 'image3', 'action', 'status'])
            ->make(true);
    }

    public function show($id)
    {
        $data =  collect(
            \DB::select(
                "SELECT pengumumanTbl.pengumuman_id, pengumumanTbl.pengumuman, pengumumanTbl.image1, pengumumanTbl.image2, pengumumanTbl.image3, pengumumanTbl.tanggal, pengumumanTbl.aktif, rtTbl.rt_id, rtTbl.rt, rwTbl.rw_id, rwTbl.rw, kelurahanTbl.kelurahan_id, kelurahanTbl.nama 
                FROM pengumuman as pengumumanTbl 
                LEFT OUTER JOIN rt as rtTbl ON rtTbl.rt_id = pengumumanTbl.rt_id 
                LEFT OUTER JOIN rw as rwTbl ON rwTbl.rw_id = pengumumanTbl.rw_id 
                LEFT OUTER JOIN kelurahan as kelurahanTbl ON kelurahanTbl.kelurahan_id = pengumumanTbl.kelurahan_id 
                WHERE pengumumanTbl.pengumuman_id = '$id'"
            )
        )->first();

        return $data;
    }

    public function store($request)
    {
        $input = $request->except('proengsoft_jsvalidation');

        \DB::beginTransaction();

        try {
            $input['date_created'] = date('Y-m-d H:i:s');

            if ($request->hasFile('image1')) {
                $input['image1'] = 'pengumuman_img1_' . rand() . '.' . $request->image1->getClientOriginalExtension();
                $request->image1->move(public_path('upload/pengumuman'), $input['image1']);
            }

            if ($request->hasFile('image2')) {
                $input['image2'] = 'pengumuman_img2_' . rand() . '.' . $request->image2->getClientOriginalExtension();
                $request->image2->move(public_path('upload/pengumuman'), $input['image2']);
            }

            if ($request->hasFile('image3')) {
                $input['image3'] = 'pengumuman_img3_' . rand() . '.' . $request->image3->getClientOriginalExtension();
                $request->image3->move(public_path('upload/pengumuman'), $input['image3']);
            }

            $newPengumuman = $this->pengumuman->create($input);

            /** send notification **/
            PengumumanCreated::dispatch($newPengumuman);

            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function update($request, $id)
    {
        $model = $this->pengumuman->findOrFail($id);

        $inputs = $request->except('proengsoft_jsvalidation');
        $inputs['date_updated'] = date('Y-m-d H:i:s');
        $inputs['user_updated'] = \Auth::user()->user_id;

        \DB::beginTransaction();

        try {
            if ($request->hasFile('image1')) {
                $inputs['image1'] = 'pengumuman_img1_' . rand() . '.' . $request->image1->getClientOriginalExtension();
                $request->image1->move(public_path('upload/pengumuman'), $inputs['image1']);

                \File::delete(public_path('upload/pengumuman/' . $model->image1));
            }

            if ($request->hasFile('image2')) {
                $inputs['image2'] = 'pengumuman_img2_' . rand() . '.' . $request->image2->getClientOriginalExtension();
                $request->image2->move(public_path('upload/pengumuman'), $inputs['image2']);

                \File::delete(public_path('upload/pengumuman/' . $model->image2));
            }

            if ($request->hasFile('image3')) {
                $inputs['image3'] = 'pengumuman_img3_' . rand() . '.' . $request->image3->getClientOriginalExtension();
                $request->image3->move(public_path('upload/pengumuman'), $inputs['image3']);

                \File::delete(public_path('upload/pengumuman/' . $model->image3));
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
        $model = $this->pengumuman->findOrFail($id);

        try {

            if ($model->image1) {
                \File::delete(public_path('upload/pengumuman/' . $model->image1));
            }

            if ($model->image2) {
                \File::delete(public_path('upload/pengumuman/' . $model->image2));
            }

            if ($model->image3) {
                \File::delete(public_path('upload/pengumuman/' . $model->image3));
            }

            $this->pengumuman->destroy($id);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function getAlamat($id)
    {
        $pengumumanGetAlamat = \DB::table('pengumuman')->where('pengumuman_id', $id)->first();
        $pengumuman = [
            "subdistrict_id"   => "",
            "province_id"      => "",
            "province"         => "",
            "city_id"          => "",
            "city"             => "",
            "type"             => "",
            "subdistrict_name" => "",
            "pengumuman"        => ""
        ];

        if ($pengumumanGetAlamat) {
            if (empty($pengumumanGetAlamat->subdistrict_id)) {
                $pengumuman = json_decode($this->rajaOngkir->getSubdistrictDetailById(1579), true);
            } else {
                $pengumuman = json_decode($this->rajaOngkir->getSubdistrictDetailById($pengumumanGetAlamat->subdistrict_id), true);
            }
            $pengumuman['pengumuman'] = $pengumumanGetAlamat->pengumuman;
        }

        return $pengumuman;
    }
}
