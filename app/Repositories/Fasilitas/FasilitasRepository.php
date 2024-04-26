<?php

namespace App\Repositories\Fasilitas;

use App\Models\Fasilitas\Fasilitas;
use App\Repositories\BaseRepository;
use App\Repositories\RajaOngkir\RajaOngkirRepository;
use App\Repositories\RoleManagement\UserRepository;

class FasilitasRepository extends BaseRepository
{
    public function __construct(Fasilitas $fasilitas, RajaOngkirRepository $rajaOngkir, UserRepository $_user)
    {
        $this->model = $fasilitas;
        $this->rajaOngkir = $rajaOngkir;
        $this->user = $_user;
    }

    public function dataTablesFasilitas($request)
    {
        $checkRole = \helper::checkUserRole('all');
        $isRw = $checkRole['isRw'];
        $isRt = $checkRole['isRt'];

        $getData = $this->user->currentUser();
        $rwID = $getData->rw_id;
        $rtID = $getData->rt_id;

        if (request()->ajax()) {
            $model = $this->model->select([
                'fasilitas.fasilitas_id',
                'fasilitas.nama_fasilitas',
                'fasilitas.lokasi',
                'fasilitas.updated_at'
            ])
            ->when($isRw, function ($query) use ($rwID) {
                $query->where('fasilitas.rw_id', $rwID);
            })
            ->when($isRt, function ($query) use ($rtID) {
                $query->where('fasilitas.rt_id', $rtID);
            })
            ->when(!empty($request->kelurahan_id), function ($query) use ($request) {
                $query->where('fasilitas.kelurahan_id', $request->kelurahan_id);
                if (!empty($request->rw_id)) {
                    $query->where('fasilitas.rw_id', $request->rw_id);
                }
                if (!empty($request->rt_id)) {
                    $query->where('fasilitas.rt_id', $request->rt_id);
                }
            })
            ->get();
        }

        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return view('partials.buttons.cust-datatable', [
                    'show2'         => ['name' => 'Detail', 'route' => route('fasilitas.fasilitas' . '.show', \Crypt::encryptString($row->fasilitas_id))],
                    'edit2'         => ['name' => 'Edit', 'route' => route('fasilitas.fasilitas' . '.edit', \Crypt::encryptString($row->fasilitas_id))],
                    'ajaxDestroy2'  => ['name' => 'Delete', 'id' => $row->fasilitas_id]
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show($id)
    {
        $data =  collect(
            \DB::select(
                "SELECT fasilitasTbl.fasilitas_id, fasilitasTbl.nama_fasilitas, fasilitasTbl.keterangan, fasilitasTbl.lokasi, fasilitasTbl.pict1, fasilitasTbl.pict2, rtTbl.rt_id, rtTbl.rt, rwTbl.rw_id, rwTbl.rw, kelurahanTbl.kelurahan_id, kelurahanTbl.nama 
                FROM fasilitas as fasilitasTbl 
                LEFT OUTER JOIN rt as rtTbl ON rtTbl.rt_id = fasilitasTbl.rt_id 
                LEFT OUTER JOIN rw as rwTbl ON rwTbl.rw_id = fasilitasTbl.rw_id 
                LEFT OUTER JOIN kelurahan as kelurahanTbl ON kelurahanTbl.kelurahan_id = fasilitasTbl.kelurahan_id 
                WHERE fasilitasTbl.fasilitas_id = '$id'"
            )
        )->first();

        return $data;
    }

    public function getAlamat($id)
    {
        $fasilitasGetAlamat = \DB::table('fasilitas')->where('fasilitas_id', $id)->first();
        $nama_fasilitas = [
            "subdistrict_id"   => "",
            "province_id"      => "",
            "province"         => "",
            "city_id"          => "",
            "city"             => "",
            "type"             => "",
            "subdistrict_name" => "",
            "nama_fasilitas"        => ""
        ];

        if ($fasilitasGetAlamat) {
            if (empty($fasilitasGetAlamat->subdistrict_id)) {
                $nama_fasilitas = json_decode($this->rajaOngkir->getSubdistrictDetailById(1579), true);
            } else {
                $nama_fasilitas = json_decode($this->rajaOngkir->getSubdistrictDetailById($fasilitasGetAlamat->subdistrict_id), true);
            }
            $nama_fasilitas['nama_fasilitas'] = $fasilitasGetAlamat->nama_fasilitas;
        }

        return $nama_fasilitas;
    }
}
