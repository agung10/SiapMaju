<?php

namespace App\Http\Controllers\Geolocation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Blok;
use App\Models\Master\Keluarga\AnggotaKeluarga;
use App\Models\Master\Kelurahan;
use App\Models\Master\RT;
use App\Models\Master\RW;
use App\Repositories\RajaOngkir\RajaOngkirRepository;
use App\Repositories\RoleManagement\UserRepository;
use stdClass;

class SitemapController extends Controller
{
    public function __construct(UserRepository $user, RajaOngkirRepository $rajaOngkir)
    {
        $route_name = explode('.',\Route::currentRouteName());
        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

        $this->user = $user;
        $this->rajaOngkir = $rajaOngkir;
    }

    public function index()
    {   
        $getData = $this->user->currentUser();
        $kelurahanID = $getData->kelurahan_id;
        $rwID = $getData->rw_id;

        $isRt = $getData->is_rt == true;
        $isRw = $getData->is_rw == true;
        $isAdmin = $getData->is_admin == true;
        $isWarga = $isRt != true && $isRw != true && $isAdmin != true;

        $rt = RT::select(
            'rt.rt',
            'rt.rt_id',
            'rt.rw_id',
        )
            ->join('rw', 'rw.rw_id', 'rt.rw_id')
            ->join('kelurahan', 'kelurahan.kelurahan_id', 'rw.kelurahan_id')
            ->when($isRw, function ($query) use ($rwID) {
                $query->where('rt.rw_id', $rwID);
            })
            ->orderBy('rt', 'ASC')
            ->get();

        $resultRT = '<option></option>';
        foreach ($rt as $res) {
            $resultRT .= '<option value="' . $res->rt_id . '">' . $res->rt . '</option>';
        }

        $rw = RW::select(
            'rw.rw',
            'rw.rw_id',
        )
            ->join('kelurahan', 'kelurahan.kelurahan_id', 'rw.kelurahan_id')
            ->when($isRw, function ($query) use ($rwID) {
                $query->where('rw.rw_id', $rwID);
            })
            ->orderBy('rw', 'ASC')
            ->get();

        $resultRW = '<option></option>';
        foreach ($rw as $res) {
            if ($isRw) {
                $resultRW .= '<option value="' . $res->rw_id . '"' . ((!empty($res->rw_id)) ? ((!empty($res->rw_id == $rwID)) ? ('selected') : ('')) : ('')) . '>' . $res->rw . '</option>';
            } else {
                $resultRW .= '<option value="' . $res->rw_id . '">' . $res->rw . '</option>';
            }
        }

        $kelurahan = Kelurahan::select(
            'kelurahan.nama',
            'kelurahan.kelurahan_id'
        )
            ->when($isRw, function ($query) use ($kelurahanID) {
                $query->where('kelurahan.kelurahan_id', $kelurahanID);
            })
            ->orderBy('nama', 'ASC')
            ->get();

        $resultKelurahan = '<option></option>';
        foreach ($kelurahan as $res) {
            if ($isRw) {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '"' . ((!empty($res->kelurahan_id)) ? ((!empty($res->kelurahan_id == $kelurahanID)) ? ('selected') : ('')) : ('')) . '>' . $res->nama . '</option>';
            } else {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '">' . $res->nama . '</option>';
            }
        }

        if ($getData->province_id) {
            $provinces = $this->rajaOngkir->getProvinces()->where('province_id', $getData->province_id)->pluck('province', 'province_id');
        } else {
            $provinces = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');
        }
        $resultProvince = '<option></option>';
        foreach ($provinces as $province_id => $province) {
            if ($isRw) {
                $resultProvince .= '<option value="' . $province_id . '"' . ((!empty($province_id)) ? ((!empty($province_id == $getData->province_id)) ? ('selected') : ('')) : ('')) . '>' . $province . '</option>';
            } else {
                $resultProvince .= '<option value="' . $province_id . '">' . $province . '</option>';
            }
        }

        $resultCity = '<option></option>';
        if ($getData->city_id) {
            $cities = $this->rajaOngkir->getCitiesByProvince($getData->province_id)
            ->map(function ($value) {
                $value->city_name = $value->type . ' ' . $value->city_name;
                return $value;
            })
            ->where('city_id', $getData->city_id)
            ->pluck('city_name', 'city_id');
            
            foreach ($cities as $city_id => $city_name) {
                if ($isRw) {
                    $resultCity .= '<option value="' . $city_id . '"' . ((!empty($city_id)) ? ((!empty($city_id == $getData->city_id)) ? ('selected') : ('')) : ('')) . '>' . $city_name . '</option>';
                } else {
                    $resultCity .= '<option value="' . $city_id . '">' . $city_name . '</option>';
                }
            }
        }

        $resultSubdistrict = '<option></option>';
        if ($getData->subdistrict_id) {
            $subdistricts = $this->rajaOngkir->getSubdistrictsByCity($getData->city_id)->where('subdistrict_id', $getData->subdistrict_id)->pluck('subdistrict_name', 'subdistrict_id');
            
            foreach ($subdistricts as $subdistrict_id => $subdistrict_name) {
                if ($isRw) {
                    $resultSubdistrict .= '<option value="' . $subdistrict_id . '"' . ((!empty($subdistrict_id)) ? ((!empty($subdistrict_id == $getData->subdistrict_id)) ? ('selected') : ('')) : ('')) . '>' . $subdistrict_name . '</option>';
                } else {
                    $resultSubdistrict .= '<option value="' . $subdistrict_id . '">' . $subdistrict_name . '</option>';
                }
            }
        }

        $anggotaKeluarga = AnggotaKeluarga::select('anggota_keluarga.nama', 'blok.nama_blok','blok.long','blok.lang','blok.blok_id','rt.rt', 'blok.province_id', 'blok.city_id', 'blok.subdistrict_id', 'blok.kelurahan_id', 'blok.rw_id', 'blok.rt_id')
                                          ->join('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                                          ->join('rt','rt.rt_id','keluarga.rt_id')
                                          ->join('blok','blok.blok_id','keluarga.blok_id')
                                          ->when(!$isAdmin, function ($query) use ($getData) {
                                                $query->where('anggota_keluarga.rw_id', $getData->rw_id);
                                                $query->whereNotNull('blok.long');
                                                $query->whereNotNull('blok.lang');
                                            })
                                          ->get();

        $blok = [];

        foreach($anggotaKeluarga as $key => $val){
            $duplicate = in_array($val,$blok);

            if(!$duplicate){
                array_push($blok,$val);
            }
        }

        $personal = AnggotaKeluarga::select('anggota_keluarga.nama', 'blok.nama_blok','blok.long','blok.lang','blok.blok_id','rt.rt', 'blok.province_id', 'blok.city_id', 'blok.subdistrict_id', 'blok.kelurahan_id', 'blok.rw_id', 'blok.rt_id')
        ->join('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
        ->join('rt','rt.rt_id','keluarga.rt_id')
        ->join('blok','blok.blok_id','keluarga.blok_id')
        ->when(!$isAdmin, function ($query) use ($getData) {
              $query->where('anggota_keluarga.anggota_keluarga_id', $getData->anggota_keluarga_id);
              $query->whereNotNull('blok.long');
              $query->whereNotNull('blok.lang');
          })
        ->get();

        $blokPersonal = [];

        foreach($personal as $key => $val){
            $duplicate = in_array($val,$blokPersonal);

            if(!$duplicate){
                array_push($blokPersonal,$val);
            }
        }

        return view($this->route1.'.'.$this->route2.'.'.$this->route3,compact('blok', 'blokPersonal', 'resultRT', 'resultRW', 'resultKelurahan', 'isAdmin', 'isRw', 'isRt', 'resultProvince', 'resultCity', 'resultSubdistrict'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $anggota_keluarga = \DB::table('anggota_keluarga')
                        ->select('anggota_keluarga.anggota_keluarga_id', 'anggota_keluarga.nama','users.picture','anggota_keluarga.is_rt','anggota_keluarga.is_rw','anggota_keluarga.have_umkm')
                        ->join('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                        ->leftJoin('users','users.anggota_keluarga_id','anggota_keluarga.anggota_keluarga_id')
                        ->where('anggota_keluarga.hub_keluarga_id', 1)
                        ->where('keluarga.blok_id', $request->blok_id)
                        ->orderBy('anggota_keluarga.anggota_keluarga_id','asc')
                        ->get();

        $resultData = [];
        foreach($anggota_keluarga as $res) {
            $object = new stdClass;
            $object->nama = $res->nama;
            $object->picture = $res->picture;
            $object->is_rt = $res->is_rt;
            $object->is_rw = $res->is_rw;
            $object->have_umkm = $res->have_umkm;

            $object->umkms = \DB::table('umkm')->select('umkm.image as logo_umkm', 'umkm.nama as nama_umkm', 'umkm.aktif')->where('umkm.anggota_keluarga_id', $res->anggota_keluarga_id)->get()->toArray();

            $resultData[] = $object;
        }
        
        $response = sizeof($anggota_keluarga) > 0 ? 'success' : 'failed';

        return response()->json(['status' => $response,
                                 'result' => $resultData]);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function getBlokData(Request $request)
    { 
        $bloks = AnggotaKeluarga::select('anggota_keluarga.nama', 'blok.nama_blok','blok.long','blok.lang','blok.blok_id','rt.rt', 'blok.province_id', 'blok.city_id', 'blok.subdistrict_id', 'blok.kelurahan_id', 'blok.rw_id', 'blok.rt_id')
                                ->join('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                                ->join('rt','rt.rt_id','keluarga.rt_id')
                                ->join('blok','blok.blok_id','keluarga.blok_id')
                                ->when(!empty($request->province_id), function ($query) use ($request) {
                                    $query->where('blok.province_id', $request->province_id);
                                })
                                ->when(!empty($request->province_id) && !empty($request->city_id), function ($query) use ($request) {
                                    $query->where('blok.city_id', $request->city_id);
                                })
                                ->when(!empty($request->province_id) && !empty($request->city_id) && !empty($request->subdistrict_id), function ($query) use ($request) {
                                    $query->where('blok.subdistrict_id', $request->subdistrict_id);
                                })
                                ->when(!empty($request->province_id) && !empty($request->city_id) && !empty($request->subdistrict_id) && !empty($request->kelurahan_id), function ($query) use ($request) {
                                    $query->where('blok.kelurahan_id', $request->kelurahan_id);
                                })
                                ->when(!empty($request->province_id) && !empty($request->city_id) && !empty($request->subdistrict_id) && !empty($request->kelurahan_id) && !empty($request->rw_id), function ($query) use ($request) {
                                    $query->where('blok.rw_id', $request->rw_id);
                                })
                                ->when(!empty($request->province_id) && !empty($request->city_id) && !empty($request->subdistrict_id) && !empty($request->kelurahan_id) && !empty($request->rw_id) && !empty($request->rt_id), function ($query) use ($request) {
                                    $query->where('blok.rt_id', $request->rt_id);
                                })
                                ->when(!empty($request->province_id) && !empty($request->city_id) && !empty($request->subdistrict_id) && !empty($request->kelurahan_id) && !empty($request->rw_id) && !empty($request->rt_id) && !empty($request->blok_id), function ($query) use ($request) {
                                    $query->where('blok.blok_id', $request->blok_id);
                                })
                                ->get();

        return response()->json(['result' => $bloks]);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
