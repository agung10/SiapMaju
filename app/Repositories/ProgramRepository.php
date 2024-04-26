<?php

namespace App\Repositories;

use App\Models\Program;
use App\Helpers\helper;
use App\Repositories\RajaOngkir\RajaOngkirRepository;
use App\Repositories\RoleManagement\UserRepository;

class ProgramRepository
{
    public function __construct(Program $_Program, RajaOngkirRepository $rajaOngkir, UserRepository $_user)
    {
        $this->program =  $_Program;
        $this->rajaOngkir =  $rajaOngkir;
        $this->user =  $_user;
    }

    public function dataTables($request)
    {
        $checkRole = \helper::checkUserRole('all');
        $isRw = $checkRole['isRw'];
        $isRt = $checkRole['isRt'];

        $getData = $this->user->currentUser();
        $rwID = $getData->rw_id;
        $rtID = $getData->rt_id;

        $datatableButtons = method_exists(new $this->program, 'datatableButtons') ? $this->program->datatableButtons() : ['show', 'edit', 'destroy'];
        
        if (request()->ajax()) {
            $model = \DB::table('program')
            ->select('program.program_id', 'program.nama_program', 'program.program', 'program.pic', 'program.tanggal', 'program.image')
            ->when($isRw, function ($query) use ($rwID) {
                $query->where('program.rw_id', $rwID);
            })
            ->when($isRt, function ($query) use ($rtID) {
                $query->where('program.rt_id', $rtID);
            })
            ->when(!empty($request->kelurahan_id), function ($query) use ($request) {
                $query->where('program.kelurahan_id', $request->kelurahan_id);
                if (!empty($request->rw_id)) {
                    $query->where('program.rw_id', $request->rw_id);
                }
                if (!empty($request->rt_id)) {
                    $query->where('program.rt_id', $request->rt_id);
                }
            })
            ->get();
        }

        return \DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('image', function ($row) {
                return view('layouts.partials.image_column', ['src' => \helper::loadImgUpload('program', $row->image)]);
            })
            ->editColumn('tanggal', function ($row) {
                return [
                    'display' => \helper::tglIndo($row->tanggal),
                    'raw' => $row->tanggal
                ];
            })
            ->addColumn('action', function ($data) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show2'         => ['name' => 'Detail', 'route' => route('Program.Kegiatan' . '.show', \Crypt::encryptString($data->program_id))],
                    'edit2'         => ['name' => 'Edit', 'route' => route('Program.Kegiatan' . '.edit', \Crypt::encryptString($data->program_id))],
                    'ajaxDestroy2'  => ['name' => 'Delete', 'id' => $data->program_id]
                ]);
            })
            ->rawColumns(['image', 'action'])
            ->make(true);
    }

    public function show($id)
    {
        $data =  collect(
            \DB::select(
                "SELECT programTbl.program_id, programTbl.nama_program, programTbl.program, programTbl.pic, programTbl.tanggal, programTbl.image, rtTbl.rt_id, rtTbl.rt, rwTbl.rw_id, rwTbl.rw, kelurahanTbl.kelurahan_id, kelurahanTbl.nama 
                FROM program as programTbl 
                LEFT OUTER JOIN rt as rtTbl ON rtTbl.rt_id = programTbl.rt_id 
                LEFT OUTER JOIN rw as rwTbl ON rwTbl.rw_id = programTbl.rw_id 
                LEFT OUTER JOIN kelurahan as kelurahanTbl ON kelurahanTbl.kelurahan_id = programTbl.kelurahan_id 
                WHERE programTbl.program_id = '$id'"
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
                $input['image'] = 'program_' . rand() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('upload/program'), $input['image']);
            }

            $this->program->create($input);

            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function update($request, $id)
    {
        $model = $this->program->findOrFail($id);

        $inputs = $request->except('proengsoft_jsvalidation');
        $inputs['date_updated'] = date('Y-m-d H:i:s');
        $inputs['user_updated'] = \Auth::user()->user_id;

        \DB::beginTransaction();

        try {
            if ($request->hasFile('image')) {
                $inputs['image'] = 'program_' . rand() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('upload/program'), $inputs['image']);

                \File::delete(public_path('upload/program/' . $model->image));
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
        $model = $this->program->findOrFail($id);

        try {

            if ($model->image) {
                \File::delete(public_path('upload/program/' . $model->image));
            }

            $this->program->destroy($id);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function getAlamat($id)
    {
        $programGetAlamat = \DB::table('program')->where('program_id', $id)->first();
        $nama_program = [
            "subdistrict_id"   => "",
            "province_id"      => "",
            "province"         => "",
            "city_id"          => "",
            "city"             => "",
            "type"             => "",
            "subdistrict_name" => "",
            "nama_program"        => ""
        ];

        if ($programGetAlamat) {
            if (empty($programGetAlamat->subdistrict_id)) {
                $nama_program = json_decode($this->rajaOngkir->getSubdistrictDetailById(1579), true);
            } else {
                $nama_program = json_decode($this->rajaOngkir->getSubdistrictDetailById($programGetAlamat->subdistrict_id), true);
            }
            $nama_program['nama_program'] = $programGetAlamat->nama_program;
        }

        return $nama_program;
    }
}
