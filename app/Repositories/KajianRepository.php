<?php

namespace App\Repositories;

use App\Models\Kajian\Kajian;
use App\Helpers\helper;
use App\Repositories\RajaOngkir\RajaOngkirRepository;
use App\Repositories\RoleManagement\UserRepository;

class KajianRepository
{
    public function __construct(Kajian $_Kajian, RajaOngkirRepository $rajaOngkir, UserRepository $_user)
    {
        $this->kajian = $_Kajian;
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

        $datatableButtons = method_exists(new $this->kajian, 'datatableButtons') ? $this->kajian->datatableButtons() : ['show', 'edit', 'destroy'];

        if (request()->ajax()) {
            $model = \DB::table('kajian')
                ->select('kajian_id', 'kajian', 'upload_materi_1', 'upload_materi_2', 'upload_materi_3', 'upload_materi_4', 'upload_materi_5', 'image', 'kat_kajian.kategori', 'judul', 'author')
                ->join('kat_kajian', 'kat_kajian.kat_kajian_id', 'kajian.kat_kajian_id')
                ->when($isRw, function ($query) use ($rwID) {
                    $query->where('kajian.rw_id', $rwID);
                })
                ->when($isRt, function ($query) use ($rtID) {
                    $query->where('kajian.rt_id', $rtID);
                })
                ->when(!empty($request->kelurahan_id), function ($query) use ($request) {
                    $query->where('kajian.kelurahan_id', $request->kelurahan_id);
                    if (!empty($request->rw_id)) {
                        $query->where('kajian.rw_id', $request->rw_id);
                    }
                    if (!empty($request->rt_id)) {
                        $query->where('kajian.rt_id', $request->rt_id);
                    }
                })
                ->get();
        }

        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show2'         => ['name' => 'Detail', 'route' => route('Kajian.Konten' . '.show', \Crypt::encryptString($data->kajian_id))],
                    'edit2'         => ['name' => 'Edit', 'route' => route('Kajian.Konten' . '.edit', \Crypt::encryptString($data->kajian_id))],
                    'ajaxDestroy2'  => ['name' => 'Delete', 'id' => $data->kajian_id]
                ]);
            })
            ->editColumn('judul', function ($row) {
                return \Str::limit($row->judul, 50);
            })
            ->editColumn('image', function ($row) {
                return view('layouts.partials.image_column', ['src' => \helper::loadImgUpload('kajian/image', $row->image)]);
            })
            ->addColumn('materi', function ($model) {
                $uploads = [
                    'upload_materi_1' => !empty($model->upload_materi_1) ? $model->upload_materi_1 : '',
                    'upload_materi_2' => !empty($model->upload_materi_2) ? $model->upload_materi_2 : '',
                    'upload_materi_3' => !empty($model->upload_materi_3) ? $model->upload_materi_3 : '',
                    'upload_materi_4' => !empty($model->upload_materi_4) ? $model->upload_materi_4 : '',
                    'upload_materi_5' => !empty($model->upload_materi_5) ? $model->upload_materi_5 : '',
                ];

                if ($uploads) {

                    return view('partials.buttons.datatable', [
                        'materi1' => $uploads['upload_materi_1'],
                        'materi2' => $uploads['upload_materi_2'],
                        'materi3' => $uploads['upload_materi_3'],
                        'materi4' => $uploads['upload_materi_4'],
                        'materi5' => $uploads['upload_materi_5']
                    ]);
                } else {
                    return '-';
                }
            })
            ->rawColumns(['action', 'image'])
            ->make(true);
    }

    public function store($request)
    {
        $input = $request->except('proengsoft_jsvalidation');

        // $rules = [
        //     'kajian' => 'required',
        //     'judul' => 'required',
        //     'author' => 'required',
        //     'upload_materi_1' => 'max:10000|mimes:jpg,jpeg,png,doc,docx,pdf,mp4',
        //     'upload_materi_2' => 'max:10000|mimes:jpg,jpeg,png,doc,docx,pdf,mp4',
        //     'upload_materi_3' => 'max:10000|mimes:jpg,jpeg,png,doc,docx,pdf,mp4',
        //     'upload_materi_4' => 'max:10000|mimes:jpg,jpeg,png,doc,docx,pdf,mp4',
        //     'upload_materi_5' => 'max:10000|mimes:jpg,jpeg,png,doc,docx,pdf,mp4',
        //     'image' => 'required|max:2000|mimes:jpg,jpeg,png,'
        // ];

        // $validator = helper::validation($request->all(), $rules);
        // \DB::beginTransaction();

        // if ($validator->fails()) {
        //     return response()->json(['status' => 'failed', 'errors' => $validator->getMessageBag()->toArray()]);
        // }

        \DB::beginTransaction();

        try {
            $input['date_created'] = date('Y-m-d H:i:s');

            if ($request->hasFile('upload_materi_1')) {
                $input['upload_materi_1'] = 'upload_materi_1_' . rand() . '.' . $request->upload_materi_1->getClientOriginalExtension();
                $request->upload_materi_1->move(public_path('upload/kajian'), $input['upload_materi_1']);
            }

            if ($request->hasFile('upload_materi_2')) {
                $input['upload_materi_2'] = 'upload_materi_2_' . rand() . '.' . $request->upload_materi_2->getClientOriginalExtension();
                $request->upload_materi_2->move(public_path('upload/kajian'), $input['upload_materi_2']);
            }

            if ($request->hasFile('upload_materi_3')) {
                $input['upload_materi_3'] = 'upload_materi_3_' . rand() . '.' . $request->upload_materi_3->getClientOriginalExtension();
                $request->upload_materi_3->move(public_path('upload/kajian'), $input['upload_materi_3']);
            }

            if ($request->hasFile('upload_materi_4')) {
                $input['upload_materi_4'] = 'upload_materi_4_' . rand() . '.' . $request->upload_materi_4->getClientOriginalExtension();
                $request->upload_materi_4->move(public_path('upload/kajian'), $input['upload_materi_4']);
            }

            if ($request->hasFile('upload_materi_5')) {
                $input['upload_materi_5'] = 'upload_materi_5_' . rand() . '.' . $request->upload_materi_5->getClientOriginalExtension();
                $request->upload_materi_5->move(public_path('upload/kajian'), $input['upload_materi_5']);
            }

            if ($request->hasFile('image')) {
                $input['image'] = 'kajian_' . rand() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('upload/kajian/image'), $input['image']);
            }

            $this->kajian->create($input);

            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        $data =  collect(
            \DB::select(
                "SELECT kajianTbl.kajian_id, kajianTbl.kat_kajian_id, kajianTbl.judul, kajianTbl.kajian, kajianTbl.author, kajianTbl.upload_materi_1, kajianTbl.upload_materi_2, kajianTbl.upload_materi_3, kajianTbl.upload_materi_4, kajianTbl.upload_materi_5, kajianTbl.image, rtTbl.rt_id, rtTbl.rt, rwTbl.rw_id, rwTbl.rw, kelurahanTbl.kelurahan_id, kelurahanTbl.nama 
                FROM kajian as kajianTbl 
                LEFT OUTER JOIN rt as rtTbl ON rtTbl.rt_id = kajianTbl.rt_id 
                LEFT OUTER JOIN rw as rwTbl ON rwTbl.rw_id = kajianTbl.rw_id 
                LEFT OUTER JOIN kelurahan as kelurahanTbl ON kelurahanTbl.kelurahan_id = kajianTbl.kelurahan_id 
                WHERE kajianTbl.kajian_id = '$id'"
            )
        )->first();

        return $data;
    }

    public function update($request, $id)
    {
        $input = $request->except('proengsoft_jsvalidation');
        $model = $this->kajian->findOrFail($id);

        // $rules = [
        //     'judul' => 'required',
        //     'author' => 'required',
        //     'kajian' => 'required',
        //     'upload_materi_1' => 'max:10000|mimes:jpg,jpeg,png,doc,docx,pdf,mp4',
        //     'upload_materi_2' => 'max:10000|mimes:jpg,jpeg,png,doc,docx,pdf,mp4',
        //     'upload_materi_3' => 'max:10000|mimes:jpg,jpeg,png,doc,docx,pdf,mp4',
        //     'upload_materi_4' => 'max:10000|mimes:jpg,jpeg,png,doc,docx,pdf,mp4',
        //     'upload_materi_5' => 'max:10000|mimes:jpg,jpeg,png,doc,docx,pdf,mp4',
        //     'image' => 'max:2000|mimes:jpg,jpeg,png,'
        // ];

        // $validator = helper::validation($request->all(), $rules);

        // if ($validator->fails()) {
        //     return response()->json(['status' => 'failed', 'errors' => $validator->getMessageBag()->toArray()]);
        // }

        \DB::beginTransaction();
        try {
            $input['date_created'] = date('Y-m-d H:i:s');

            if ($request->hasFile('upload_materi_1')) {
                $input['upload_materi_1'] = 'upload_materi_1_' . rand() . '.' . $request->upload_materi_1->getClientOriginalExtension();
                $request->upload_materi_1->move(public_path('upload/kajian'), $input['upload_materi_1']);
                \File::delete(public_path('upload/kajian/' . $model->upload_materi_1));
            }

            if ($request->hasFile('upload_materi_2')) {
                $input['upload_materi_2'] = 'upload_materi_2_' . rand() . '.' . $request->upload_materi_2->getClientOriginalExtension();
                $request->upload_materi_2->move(public_path('upload/kajian'), $input['upload_materi_2']);
                \File::delete(public_path('upload/kajian/' . $model->upload_materi_2));
            }

            if ($request->hasFile('upload_materi_3')) {
                $input['upload_materi_3'] = 'upload_materi_3_' . rand() . '.' . $request->upload_materi_3->getClientOriginalExtension();
                $request->upload_materi_3->move(public_path('upload/kajian'), $input['upload_materi_3']);
                \File::delete(public_path('upload/kajian/' . $model->upload_materi_3));
            }

            if ($request->hasFile('upload_materi_4')) {
                $input['upload_materi_4'] = 'upload_materi_4_' . rand() . '.' . $request->upload_materi_4->getClientOriginalExtension();
                $request->upload_materi_4->move(public_path('upload/kajian'), $input['upload_materi_4']);
                \File::delete(public_path('upload/kajian/' . $model->upload_materi_4));
            }

            if ($request->hasFile('upload_materi_5')) {
                $input['upload_materi_5'] = 'upload_materi_5_' . rand() . '.' . $request->upload_materi_5->getClientOriginalExtension();
                $request->upload_materi_5->move(public_path('upload/kajian'), $input['upload_materi_5']);
                \File::delete(public_path('upload/kajian/' . $model->upload_materi_5));
            }

            if ($request->hasFile('image')) {
                $input['image'] = 'kajian_' . rand() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('upload/kajian/image'), $input['image']);
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
        $model = $this->kajian->findOrFail($id);

        try {

            if ($model->image) {
                \File::delete(public_path('upload/kajian/image/' . $model->image));
            }

            if ($model->upload_materi_1) {
                \File::delete(public_path('upload/kajian/' . $model->upload_materi_1));
            }

            if ($model->upload_materi_2) {
                \File::delete(public_path('upload/kajian/' . $model->upload_materi_2));
            }

            if ($model->upload_materi_3) {
                \File::delete(public_path('upload/kajian/' . $model->upload_materi_3));
            }

            if ($model->upload_materi_4) {
                \File::delete(public_path('upload/kajian/' . $model->upload_materi_4));
            }

            if ($model->upload_materi_5) {
                \File::delete(public_path('upload/kajian/' . $model->upload_materi_5));
            }

            $this->kajian->destroy($id);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function deleteMateri($request, $id)
    {
        $column = 'upload_materi_' . $request->noMateri;
        $model = $this->kajian->findOrFail($id);

        \DB::beginTransaction();

        try {

            if ($model->$column) {
                \File::delete(public_path('upload/kajian/' . $model->$column));
            }

            $model->update([$column => null]);

            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function checkMateri($id)
    {
        $model = $this->kajian
            ->select(
                'kajian.upload_materi_1',
                'kajian.upload_materi_2',
                'kajian.upload_materi_3',
                'kajian.upload_materi_4',
                'kajian.upload_materi_5'
            )
            ->findOrFail($id);
        $arr = [
            'materi1' => $model['upload_materi_1'],
            'materi2' => $model['upload_materi_2'],
            'materi3' => $model['upload_materi_3'],
            'materi4' => $model['upload_materi_4'],
            'materi5' => $model['upload_materi_5'],
        ];

        return response()->json($arr);
    }

    public function getAlamat($id)
    {
        $kajianGetAlamat = \DB::table('kajian')->where('kajian_id', $id)->first();
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

        if ($kajianGetAlamat) {
            if (empty($kajianGetAlamat->subdistrict_id)) {
                $judul = json_decode($this->rajaOngkir->getSubdistrictDetailById(1579), true);
            } else {
                $judul = json_decode($this->rajaOngkir->getSubdistrictDetailById($kajianGetAlamat->subdistrict_id), true);
            }
            $judul['judul'] = $kajianGetAlamat->judul;
        }

        return $judul;
    }
}
