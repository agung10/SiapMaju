<?php

namespace App\Http\Controllers\Master\Keluarga;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\KeluargaRequest;
use Illuminate\Http\Request;
use App\Helpers\helper;
use App\Repositories\Master\KeluargaRepository;
use App\Models\Master\Keluarga\{AnggotaKeluarga, Keluarga, HubKeluarga};
use App\Models\Master\Blok;
use App\Models\Master\Kelurahan;
use App\Models\Master\RT;
use App\Models\Master\RW;
use App\Repositories\RajaOngkir\RajaOngkirRepository;
use App\Repositories\RoleManagement\UserRepository;

class ListKeluargaController extends Controller
{
    public function __construct(KeluargaRepository $_KeluargaRepository, RajaOngkirRepository $rajaOngkir, UserRepository $user)
    {
        $route_name  = explode('.', \Route::currentRouteName());
        $this->route1 = ((isset($route_name[0])) ? $route_name[0] : (''));
        $this->route2 = ((isset($route_name[1])) ? $route_name[1] : (''));
        $this->route3 = ((isset($route_name[2])) ? $route_name[2] : (''));

        $this->keluarga = $_KeluargaRepository;
        $this->rajaOngkir = $rajaOngkir;
        $this->user = $user;
    }

    public function index()
    {
        $isAdmin = \helper::checkUserRole('admin');
        $provinces = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');

        return view($this->route1 . '.' . 'Keluarga' . '.' . $this->route2 . '.' . $this->route3, compact('isAdmin', 'provinces'));
    }

    public function create()
    {
        $kepalaKeluarga = HubKeluarga::select('hub_keluarga_id', 'hubungan_kel')->first();
        
        $isRt = \helper::checkUserRole('rt');

        $getData = $this->user->currentUser();
        $rtID = $getData->rt_id;
        $rwID = $getData->rw_id;
        $kelurahanID = $getData->kelurahan_id;

        $blok = Blok::select(
            'blok.blok_id',
            'blok.nama_blok'
        )
            ->when($isRt, function ($query) use ($rtID) {
                $query->where('blok.rt_id', $rtID);
            })
            ->orderBy('nama_blok', 'ASC')
            ->get();
        $resultBlok = '<option></option>';
        foreach ($blok as $res) {
            $resultBlok .= '<option value="' . $res->blok_id . '">' . $res->nama_blok . '</option>';
        }

        $rt = RT::select(
            'rt.rt',
            'rt.rt_id',
            'rw.rw',
            'kelurahan.nama'
        )
            ->join('rw', 'rw.rw_id', 'rt.rw_id')
            ->join('kelurahan', 'kelurahan.kelurahan_id', 'rw.kelurahan_id')
            ->when($isRt, function ($query) use ($rtID) {
                $query->where('rt.rt_id', $rtID);
            })
            ->orderBy('rt', 'ASC')
            ->get();
        $resultRT = '<option></option>';
        foreach ($rt as $res) {
            if ($isRt) {
                $resultRT .= '<option value="' . $res->rt_id . '"' . ((!empty($res->rt_id)) ? ((!empty($res->rt_id == $rtID)) ? ('selected') : ('')) : ('')) . '>' . $res->rt . '</option>';
            } else {
                $resultRT .= '<option value="' . $res->rt_id . '">' . $res->rt . '</option>';
            }
        }

        $rw = RW::select(
            'rw.rw',
            'rw.rw_id',
        )
            ->join('kelurahan', 'kelurahan.kelurahan_id', 'rw.kelurahan_id')
            ->when($isRt, function ($query) use ($rwID) {
                $query->where('rw.rw_id', $rwID);
            })
            ->orderBy('rw', 'ASC')
            ->get();
        $resultRW = '<option></option>';
        foreach ($rw as $res) {
            if ($isRt) {
                $resultRW .= '<option value="' . $res->rw_id . '"' . ((!empty($res->rw_id)) ? ((!empty($res->rw_id == $rwID)) ? ('selected') : ('')) : ('')) . '>' . $res->rw . '</option>';
            } else {
                $resultRW .= '<option value="' . $res->rw_id . '">' . $res->rw . '</option>';
            }
        }

        $kelurahan = Kelurahan::select(
            'kelurahan.nama',
            'kelurahan.kelurahan_id'
        )
            ->when($isRt, function ($query) use ($kelurahanID) {
                $query->where('kelurahan.kelurahan_id', $kelurahanID);
            })
            ->orderBy('nama', 'ASC')
            ->get();
        $resultKelurahan = '<option></option>';
        foreach ($kelurahan as $res) {
            if ($isRt) {
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
            if ($isRt) {
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
                if ($isRt) {
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
                if ($isRt) {
                    $resultSubdistrict .= '<option value="' . $subdistrict_id . '"' . ((!empty($subdistrict_id)) ? ((!empty($subdistrict_id == $getData->subdistrict_id)) ? ('selected') : ('')) : ('')) . '>' . $subdistrict_name . '</option>';
                } else {
                    $resultSubdistrict .= '<option value="' . $subdistrict_id . '">' . $subdistrict_name . '</option>';
                }
            }
        }

        // Create Data Lama
        $warga = \DB::table('anggota_keluarga')
            ->select('anggota_keluarga_id', 'nama', 'rt_id')
            ->when($isRt, function ($query) use ($rtID) {
                $query->where('rt_id', $rtID);
            })
            ->where('hub_keluarga_id', '!=', 1)
            ->orderBy('nama', 'ASC')
            ->pluck('nama', 'anggota_keluarga_id');
        
        $resultWarga = '<option></option>';
        foreach($warga as $anggota_keluarga_id => $nama){
            $resultWarga .= '<option value="' . $anggota_keluarga_id . '">' . $nama . '</option>';
        }
        return view($this->route1 . '.' . 'Keluarga' . '.' . $this->route2 . '.' . $this->route3, compact('kepalaKeluarga', 'resultBlok', 'resultRT', 'resultRW', 'resultKelurahan', 'isRt', 'resultProvince', 'resultCity', 'resultSubdistrict', 'resultWarga'));
    }

    public function store(KeluargaRequest $request, Keluarga $model)
    {
        return $this->keluarga->store($request);
    }

    public function storeAnggotaKeluarga(Request $request)
    {
        return $this->keluarga->storeAnggotaKeluarga($request);
    }

    public function updateKeluarga(KeluargaRequest $request, $id)
    {
        $id = \Crypt::decryptString($id);

        return $this->keluarga->updateKeluarga($request, $id);
    }

    public function show($id)
    {
        $id = \Crypt::decryptString($id);

        $showData = $this->keluarga->show($id);
        $data = $showData['data'];
        $blok = helper::select('blok', 'nama_blok', $data->blok_id, 'nama_blok', 'ASC');
        $rt = helper::select('rt', 'rt', $data->rt_id);
        $rw = helper::select('rw', 'rw', $data->rw_id);
        $status_domisili = helper::select('status_domisili', 'keterangan', $data->status_domisili);
        
        $kelurahan = helper::select('kelurahan', 'nama', $data->kelurahan_id);
        $kepalaKeluarga = $showData['kepala_keluarga'];
        $selectKepalaKeluarga = HubKeluarga::select('hub_keluarga_id', 'hubungan_kel')->first();

        $keluargaGetAlamat = $this->keluarga->getAlamat($id);

        $mutasi = \DB::table('mutasi_warga')->where('anggota_keluarga_id', $data->anggota_keluarga_id)->latest("mutasi_warga_id")->first();

        return view($this->route1 . '.' . 'Keluarga' . '.' . $this->route2 . '.' . $this->route3, compact('data', 'blok', 'rt', 'rw', 'kelurahan', 'kepalaKeluarga', 'selectKepalaKeluarga', 'keluargaGetAlamat', 'status_domisili', 'mutasi'));
    }

    public function edit($id)
    {
        $id = \Crypt::decryptString($id);

        $showData = $this->keluarga->show($id);
        $data = $showData['data'];
        $blok = helper::select('blok', 'nama_blok', $data->blok_id, 'nama_blok', 'ASC');
        $status_domisili = helper::select('status_domisili', 'keterangan', $data->status_domisili, 'keterangan', 'ASC');

        $isRt = \helper::checkUserRole('rt');
        $getData = $this->user->currentUser();
        $kelurahanID = $getData->kelurahan_id;
        $rwID = $getData->rw_id;
        $rtID = $getData->rt_id;

        $kepalaKeluarga = $showData['kepala_keluarga'];
        $selectKepalaKeluarga = HubKeluarga::select('hub_keluarga_id', 'hubungan_kel')->first();

        $rt = RT::select(
            'rt.rt',
            'rt.rt_id',
            'rt.rw_id',
            'rt.kelurahan_id'
        )
            ->where('rt.rw_id', $data->rw_id)
            ->join('rw', 'rw.rw_id', 'rt.rw_id')
            ->join('kelurahan', 'kelurahan.kelurahan_id', 'rw.kelurahan_id')
            ->when($isRt, function ($query) use ($rtID) {
                $query->where('rt.rt_id', $rtID);
            })
            ->orderBy('rt', 'ASC')
            ->get();
        $resultRT = '<option disabled selected></option>';
        foreach ($rt as $res) {
            if ($isRt) {
                $resultRT .= '<option value="' . $res->rt_id . '"' . ((!empty($res->rt_id)) ? ((!empty($res->rt_id == $rtID)) ? ('selected') : ('')) : ('')) . '>' . $res->rt . '</option>';
            } else {
                $resultRT .= '<option value="' . $res->rt_id . '"' . ((!empty($res->rt_id)) ? ((!empty($res->rt_id == $data->rt_id)) ? ('selected') : ('')) : ('')) . '>' . $res->rt . '</option>';
            }
        }

        $rw = RW::select(
            'rw.rw',
            'rw.rw_id',
            'rw.kelurahan_id',
        )
            ->where('rw.kelurahan_id', $data->kelurahan_id)
            ->when($isRt, function ($query) use ($rwID) {
                $query->where('rw.rw_id', $rwID);
            })
            ->orderBy('rw', 'ASC')
            ->get();
        $resultRW = '<option disabled selected></option>';
        foreach ($rw as $res) {
            if ($isRt) {
                $resultRW .= '<option value="' . $res->rw_id . '"' . ((!empty($res->rw_id)) ? ((!empty($res->rw_id == $rwID)) ? ('selected') : ('')) : ('')) . '>' . $res->rw . '</option>';
            } else {
                $resultRW .= '<option value="' . $res->rw_id . '"' . ((!empty($res->rw_id)) ? ((!empty($res->rw_id == $data->rw_id)) ? ('selected') : ('')) : ('')) . '>' . $res->rw . '</option>';
            }
        }

        $kelurahan = Kelurahan::select(
            'kelurahan.nama',
            'kelurahan.kelurahan_id'
        )
            ->where('subdistrict_id', $data->subdistrict_id)
            ->when($isRt, function ($query) use ($kelurahanID) {
                $query->where('kelurahan.kelurahan_id', $kelurahanID);
            })
            ->orderBy('nama', 'ASC')
            ->get();
        $resultKelurahan = '<option disabled selected></option>';
        foreach ($kelurahan as $res) {
            if ($isRt) {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '"' . ((!empty($res->kelurahan_id)) ? ((!empty($res->kelurahan_id == $kelurahanID)) ? ('selected') : ('')) : ('')) . '>' . $res->nama . '</option>';
            } else {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '"' . ((!empty($res->kelurahan_id)) ? ((!empty($res->kelurahan_id == $data->kelurahan_id)) ? ('selected') : ('')) : ('')) . '>' . $res->nama . '</option>';
            }
        }

        if ($getData->province_id) {
            $provinces = $this->rajaOngkir->getProvinces()->where('province_id', $getData->province_id)->pluck('province', 'province_id');
        } else {
            $provinces = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');
        }
        $resultProvince = '<option></option>';
        foreach ($provinces as $province_id => $province) {
            if ($isRt) {
                $resultProvince .= '<option value="' . $province_id . '"' . ((!empty($province_id)) ? ((!empty($province_id == $getData->province_id)) ? ('selected') : ('')) : ('')) . '>' . $province . '</option>';
            } else {
                $resultProvince .= '<option value="' . $province_id . '"' . ((!empty($province_id)) ? ((!empty($province_id == $data->province_id)) ? ('selected') : ('')) : ('')) . '>' . $province . '</option>';
            }        
        }
        
        if ($getData->city_id) {
            $cities = $this->rajaOngkir->getCitiesByProvince($data->province_id)
            ->map(function ($value) {
                $value->city_name = $value->type . ' ' . $value->city_name;
                return $value;
            })
            ->where('city_id', $data->city_id)
            ->pluck('city_name', 'city_id');
        } else {
            $cities = $this->rajaOngkir->getCitiesByProvince($data->province_id)
            ->map(function ($value) {
                $value->city_name = $value->type . ' ' . $value->city_name;
                return $value;
            })
            ->pluck('city_name', 'city_id');
        }
        $resultCity = '<option></option>';
        foreach ($cities as $city_id => $city_name) {
            if ($isRt) {
                $resultCity .= '<option value="' . $city_id . '"' . ((!empty($city_id)) ? ((!empty($city_id == $getData->city_id)) ? ('selected') : ('')) : ('')) . '>' . $city_name . '</option>';
            } else {
                $resultCity .= '<option value="' . $city_id . '"' . ((!empty($city_id)) ? ((!empty($city_id == $data->city_id)) ? ('selected') : ('')) : ('')) . '>' . $city_name . '</option>';
            }        }

        if ($getData->subdistrict_id) {
            $subdistricts = $this->rajaOngkir->getSubdistrictsByCity($data->city_id)->where('subdistrict_id', $data->subdistrict_id)->pluck('subdistrict_name', 'subdistrict_id');
        } else {
            $subdistricts = $this->rajaOngkir->getSubdistrictsByCity($data->city_id)->pluck('subdistrict_name', 'subdistrict_id');
        }
        $resultSubdistrict = '<option></option>';
        foreach ($subdistricts as $subdistrict_id => $subdistrict_name) {
            if ($isRt) {
                $resultSubdistrict .= '<option value="' . $subdistrict_id . '"' . ((!empty($subdistrict_id)) ? ((!empty($subdistrict_id == $getData->subdistrict_id)) ? ('selected') : ('')) : ('')) . '>' . $subdistrict_name . '</option>';
            } else {
                $resultSubdistrict .= '<option value="' . $subdistrict_id . '"' . ((!empty($subdistrict_id)) ? ((!empty($subdistrict_id == $data->subdistrict_id)) ? ('selected') : ('')) : ('')) . '>' . $subdistrict_name . '</option>';
            }        
        }

        return view($this->route1 . '.' . 'Keluarga' . '.' . $this->route2 . '.' . $this->route3, compact('data', 'blok', 'kepalaKeluarga', 'selectKepalaKeluarga', 'resultRT', 'resultRW', 'resultKelurahan', 'resultProvince', 'resultCity', 'resultSubdistrict', 'status_domisili'));
    }

    public function updateAnggotaKeluarga(Request $request, $id)
    {
        return $this->keluarga->updateAnggotaKeluarga($request, $id);
    }

    public function destroy($id)
    {
        return $this->keluarga->destroy($id);
    }

    public function dataTables(Request $request)
    {
        return $this->keluarga->dataTables($request);
    }

    public function getDataWarga($id)
    {
        $warga = AnggotaKeluarga::select('province_id', 'subdistrict_id', 'city_id', 'kelurahan_id', 'rw_id', 'rt_id', 'alamat', 'email', 'anggota_keluarga_id', 'mobile', 'jenis_kelamin', 'tgl_lahir')->findOrFail($id);
        return response()->json(['status' => 'success', 'result' => $warga]);
    }
}
