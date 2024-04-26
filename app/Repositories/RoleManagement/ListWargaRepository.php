<?php

namespace App\Repositories\RoleManagement;
use App\Models\Master\Keluarga\AnggotaKeluarga;
use App\Models\Master\Keluarga\MutasiWarga;
use stdClass;

class ListWargaRepository
{
    public function __construct()
    {

    }

    public function dataTables($request)
    {   
        $logedUSer = \DB::table('users')
                         ->select('anggota_keluarga.is_rt','anggota_keluarga.is_rw','users.is_admin','keluarga.rt_id','keluarga.rw_id')
                         ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                         ->leftJoin('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                         ->where('user_id',\Auth::user()->user_id)
                         ->first();

        $isRt = $logedUSer->is_rt == true;
        $isRw = $logedUSer->is_rw == true;
        $isAdmin = $logedUSer->is_admin == true;
        $isPetugas = $isRt == true || $isRw == true || $isAdmin == true;
        $isWarga = $isRt != true && $isRw != true && $isAdmin != true;

        if (request()->ajax()) {
            $model = AnggotaKeluarga::select(
                'anggota_keluarga.anggota_keluarga_id',
                'anggota_keluarga.nama',
                'anggota_keluarga.jenis_kelamin',
                'anggota_keluarga.email',
                'anggota_keluarga.tgl_lahir',
                'anggota_keluarga.is_active',
                'hub_keluarga.hubungan_kel',
                'blok.nama_blok',
                'rt.rt'
            )
                ->join('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                ->join('hub_keluarga','hub_keluarga.hub_keluarga_id','anggota_keluarga.hub_keluarga_id')
                ->join('blok','blok.blok_id','keluarga.blok_id')
                ->join('rt','rt.rt_id','keluarga.rt_id')
                ->when($isRt == true,function($query)use($logedUSer){
                $query->where('keluarga.rt_id',$logedUSer->rt_id);
                })
                ->when($isRw== true,function($query)use($logedUSer){
                $query->where('keluarga.rw_id',$logedUSer->rw_id);
                })
                ->when(!empty($request->province_id), function ($query) use ($request) {
                    $query->where('keluarga.province_id', $request->province_id);
                    if (!empty($request->city_id)) {
                        $query->where('keluarga.city_id', $request->city_id);
                    }
                    if (!empty($request->subdistrict_id)) {
                        $query->where('keluarga.subdistrict_id', $request->subdistrict_id);
                    }
                    if (!empty($request->kelurahan_id)) {
                        $query->where('keluarga.kelurahan_id', $request->kelurahan_id);
                    }
                    if (!empty($request->rw_id)) {
                        $query->where('keluarga.rw_id', $request->rw_id);
                    }
                    if (!empty($request->rt_id)) {
                        $query->where('keluarga.rt_id', $request->rt_id);
                    }
                })
                ->when(!empty($request->rt_id), function ($query) use ($request) {
                    $query->where('keluarga.rt_id', $request->rt_id);
                });
        }

        return \DataTables::of($model)
                          ->addIndexColumn()
                          ->addColumn('action',function($model){
                                return view('partials.buttons.cust-datatable',[
                                    'customButton'         => ['name' => 'Detail',
                                                              'route' => route('role_management.ListWarga'.'.show', \Crypt::encryptString($model->anggota_keluarga_id))]
                                ]);
                          })
                          ->addColumn('nama', function($model) {
                            $mutasi = \DB::table('mutasi_warga')->where('anggota_keluarga_id', $model->anggota_keluarga_id)->latest("mutasi_warga_id")->first();

                            if ($mutasi != null) {
                                if ($mutasi->status_mutasi_warga_id == 1) {
                                    return $model->nama;
                                } else if ($mutasi->status_mutasi_warga_id == 2) {
                                    return $model->nama . ' (Pindah)';
                                } else if ($mutasi->status_mutasi_warga_id == 3) {
                                    if ($model->jenis_kelamin == "L") {
                                        return 'Alm. ' . $model->nama;
                                    } else if ($model->jenis_kelamin == "P") {
                                        return 'Almh. ' . $model->nama;
                                    }
                                }
                            } else {
                                return $model->nama;
                            }
                          })
                          ->addColumn('tahun_lahir',function($model){
                              return date('Y',strtotime($model->tgl_lahir));
                          })
                          ->addColumn('is_active', function ($model) {
                                if ($model->is_active) {
                                    return '<span class="badge badge-primary">Disetujui</span>';
                                } else {
                                    return '<span class="badge badge-danger">Belum Disetujui</span>';
                                }
                            })
                          ->RawColumns(['action', 'is_active'])
                          ->make(true);
    }

    public function show($id)
    {
        return AnggotaKeluarga::select('anggota_keluarga.anggota_keluarga_id',
                                         'anggota_keluarga.nama',
                                         'anggota_keluarga.jenis_kelamin',
                                         'anggota_keluarga.email',
                                         'anggota_keluarga.tgl_lahir',
                                         'anggota_keluarga.is_active',
                                         'hub_keluarga.hubungan_kel',
                                         'blok.nama_blok')
                                 ->join('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                                 ->join('hub_keluarga','hub_keluarga.hub_keluarga_id','anggota_keluarga.hub_keluarga_id')
                                 ->join('blok','blok.blok_id','keluarga.blok_id')
                                 ->findOrFail($id);
    }
}