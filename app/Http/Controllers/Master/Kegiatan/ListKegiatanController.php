<?php

namespace App\Http\Controllers\Master\Kegiatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Master\MasterRepository;
use App\Models\Master\Kegiatan\Kegiatan;
use App\Helpers\helper;
use App\Models\Master\Kelurahan;
use App\Models\Master\RT;
use App\Models\Master\RW;
use App\Repositories\RajaOngkir\RajaOngkirRepository;
use App\Repositories\RoleManagement\UserRepository;

class ListKegiatanController extends Controller
{
    public function __construct(MasterRepository $_Master, RajaOngkirRepository $rajaOngkir, UserRepository $_user)
    {
        $route_name  = explode('.', \Route::currentRouteName());
        $this->route1 = ((isset($route_name[0])) ? $route_name[0] : (''));
        $this->route2 = ((isset($route_name[1])) ? $route_name[1] : (''));
        $this->route3 = ((isset($route_name[2])) ? $route_name[2] : (''));

        $this->master = $_Master;
        $this->rajaOngkir = $rajaOngkir;
        $this->user = $_user;
    }

    public function index()
    {
        return view($this->route1 . '.' . 'Kegiatan' . '.' . $this->route2 . '.' . $this->route3);
    }

    public function create()
    {
        $kategori = \DB::table('kat_kegiatan')
            ->select('kat_kegiatan.kat_kegiatan_id', 'kat_kegiatan.nama_kat_kegiatan')
            ->get();

        $result = '<option selected disabled>Pilih Kategori</option>';
        foreach ($kategori as $myKategori) {
            $result .= '<option value="' . $myKategori->kat_kegiatan_id . '">' . $myKategori->nama_kat_kegiatan . '</option>';
        }

        $checkRole = \helper::checkUserRole('all');
        $isRw = $checkRole['isRw'];
        $isRt = $checkRole['isRt'];
        $isAdmin = $checkRole['isAdmin'];

        $checkRW = $isRw == true;
        $checkRT = $isRt == true;

        $getData = $this->user->currentUser();
        $isKelurahan = $getData->kelurahan_id;
        $rwID = $getData->rw_id;
        $rtID = $getData->rt_id;
        $provinceID = $getData->province_id;
        $cityID = $getData->city_id;
        $subdistrictID = $getData->subdistrict_id;

        $provinces = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');
        $rt = RT::select(
            'rt.rt',
            'rt.rt_id',
            'rt.rw_id',
        )
            ->join('rw', 'rw.rw_id', 'rt.rw_id')
            ->join('kelurahan', 'kelurahan.kelurahan_id', 'rw.kelurahan_id')
            ->when($checkRW, function ($query) use ($rwID) {
                $query->where('rt.rw_id', $rwID);
            })
            ->when($checkRT, function ($query) use ($rtID) {
                $query->where('rt.rt_id', $rtID);
            })
            ->orderBy('rt', 'ASC')
            ->get();

        $resultRT = '<option></option>';
        foreach ($rt as $res) {
            if ($checkRT) {
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
            ->when($checkRW, function ($query) use ($rwID) {
                $query->where('rw.rw_id', $rwID);
            })
            ->when($checkRT, function ($query) use ($rwID) {
                $query->where('rw.rw_id', $rwID);
            })
            ->orderBy('rw', 'ASC')
            ->get();

        $resultRW = '<option></option>';
        foreach ($rw as $res) {
            if ($checkRW) {
                $resultRW .= '<option value="' . $res->rw_id . '"' . ((!empty($res->rw_id)) ? ((!empty($res->rw_id == $rwID)) ? ('selected') : ('')) : ('')) . '>' . $res->rw . '</option>';
            } else if ($checkRT) {
                $resultRW .= '<option value="' . $res->rw_id . '"' . ((!empty($res->rw_id)) ? ((!empty($res->rw_id == $rwID)) ? ('selected') : ('')) : ('')) . '>' . $res->rw . '</option>';
            } else {
                $resultRW .= '<option value="' . $res->rw_id . '">' . $res->rw . '</option>';
            }
        }

        $kelurahan = Kelurahan::select(
            'kelurahan.nama',
            'kelurahan.kelurahan_id'
        )
            ->when($checkRW, function ($query) use ($isKelurahan) {
                $query->where('kelurahan.kelurahan_id', $isKelurahan);
            })
            ->when($checkRT, function ($query) use ($isKelurahan) {
                $query->where('kelurahan.kelurahan_id', $isKelurahan);
            })
            ->orderBy('nama', 'ASC')
            ->get();

        $resultKelurahan = '<option></option>';
        foreach ($kelurahan as $res) {
            if ($checkRW) {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '"' . ((!empty($res->kelurahan_id)) ? ((!empty($res->kelurahan_id == $isKelurahan)) ? ('selected') : ('')) : ('')) . '>' . $res->nama . '</option>';
            } else if ($checkRT) {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '"' . ((!empty($res->kelurahan_id)) ? ((!empty($res->kelurahan_id == $isKelurahan)) ? ('selected') : ('')) : ('')) . '>' . $res->nama . '</option>';
            } else {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '">' . $res->nama . '</option>';
            }
        }

        return view($this->route1 . '.' . 'Kegiatan' . '.' . $this->route2 . '.' . $this->route3, compact('result', 'provinces', 'resultRT', 'resultRW', 'resultKelurahan', 'isAdmin'));
    }

    public function store(Request $request, Kegiatan $model)
    {
        \DB::beginTransaction();

        try {
            $model->create($request->except('proengsoft_jsvalidation'));
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        $id = \Crypt::decryptString($id);
        $data = Kegiatan::select(
            'kegiatan.kegiatan_id',
            'kegiatan.nama_kegiatan',
            'kat_kegiatan.nama_kat_kegiatan as nama_kat_kegiatan'
        )
            ->join('kat_kegiatan', 'kat_kegiatan.kat_kegiatan_id', 'kegiatan.kat_kegiatan_id')
            ->findOrFail($id);

        return view($this->route1 . '.' . 'Kegiatan' . '.' . $this->route2 . '.' . $this->route3, compact('data'));
    }

    public function edit($id)
    {
        $id = \Crypt::decryptString($id);
        $data = Kegiatan::findOrFail($id);

        $kategori = \DB::table('kat_kegiatan')
            ->select('kat_kegiatan.kat_kegiatan_id', 'kat_kegiatan.nama_kat_kegiatan')
            ->get();

        $result = '<option selected disabled>Pilih Kategori</option>';
        foreach ($kategori as $myKategori) {
            $result .= '<option value="' . $myKategori->kat_kegiatan_id . '"' . ((!empty($myKategori->kat_kegiatan_id)) ? ((!empty($myKategori->kat_kegiatan_id == $data->kat_kegiatan_id)) ? ('selected') : ('')) : ('')) . '>' . $myKategori->nama_kat_kegiatan . '</option>';
        }

        $kegiatanGetAlamat = app('App\Http\Controllers\Master\Kegiatan\ListKegiatanController')->getAlamat($id);

        $provinces   = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');
        $resultProvince = '<option disabled selected>Pilih Provinsi</option>';
        foreach ($provinces as $province_id => $province) {
            $resultProvince .= '<option value="' . $province_id . '"' . ((!empty($province_id)) ? ((!empty($province_id == $kegiatanGetAlamat['province_id'])) ? ('selected') : ('')) : ('')) . '>' . $province . '</option>';
        }

        $cities = $kegiatanGetAlamat['province_id'] != ''
            ? $this->rajaOngkir->getCitiesByProvince($kegiatanGetAlamat['province_id'])
            ->map(function ($value) {
                $value->city_name = $value->type . ' ' . $value->city_name;
                return $value;
            })
            ->pluck('city_name', 'city_id') : [];
        $resultCity = '<option disabled selected>Pilih Kota/Kabupaten</option>';
        foreach ($cities as $city_id => $city_name) {
            $resultCity .= '<option value="' . $city_id . '"' . ((!empty($city_id)) ? ((!empty($city_id == $kegiatanGetAlamat['city_id'])) ? ('selected') : ('')) : ('')) . '>' . $city_name . '</option>';
        }

        $subdistricts = $kegiatanGetAlamat['city_id'] != ''
            ? $this->rajaOngkir->getSubdistrictsByCity($kegiatanGetAlamat['city_id'])
            ->pluck('subdistrict_name', 'subdistrict_id') : [];
        $resultSubdistrict = '<option disabled selected>Pilih Kecamatan</option>';
        foreach ($subdistricts as $subdistrict_id => $subdistrict_name) {
            $resultSubdistrict .= '<option value="' . $subdistrict_id . '"' . ((!empty($subdistrict_id)) ? ((!empty($subdistrict_id == $kegiatanGetAlamat['subdistrict_id'])) ? ('selected') : ('')) : ('')) . '>' . $subdistrict_name . '</option>';
        }

        $checkRole = \helper::checkUserRole('all');
        $isRw = $checkRole['isRw'];
        $isRt = $checkRole['isRt'];

        $checkRW = $isRw == true;
        $checkRT = $isRt == true;

        $getData = $this->user->currentUser();
        $isKelurahan = $getData->kelurahan_id;
        $rwID = $getData->rw_id;
        $rtID = $getData->rt_id;

        $rt = RT::select(
            'rt.rt',
            'rt.rt_id',
            'rt.rw_id',
            'rt.kelurahan_id'
        )
            ->where('rt.rw_id', $data->rw_id)
            ->join('rw', 'rw.rw_id', 'rt.rw_id')
            ->join('kelurahan', 'kelurahan.kelurahan_id', 'rw.kelurahan_id')
            ->when($checkRW, function ($query) use ($rwID) {
                $query->where('rw.rw_id', $rwID);
            })
            ->when($checkRT, function ($query) use ($rtID) {
                $query->where('rt.rt_id', $rtID);
            })
            ->orderBy('rt', 'ASC')
            ->get();
        $resultRT = '<option disabled selected></option>';
        foreach ($rt as $res) {
            if ($checkRT) {
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
            ->when($checkRW, function ($query) use ($rwID) {
                $query->where('rw.rw_id', $rwID);
            })
            ->when($checkRT, function ($query) use ($rwID) {
                $query->where('rw.rw_id', $rwID);
            })
            ->orderBy('rw', 'ASC')
            ->get();
        $resultRW = '<option disabled selected></option>';
        foreach ($rw as $res) {
            if ($checkRW) {
                $resultRW .= '<option value="' . $res->rw_id . '"' . ((!empty($res->rw_id)) ? ((!empty($res->rw_id == $rwID)) ? ('selected') : ('')) : ('')) . '>' . $res->rw . '</option>';
            } else if ($checkRT) {
                $resultRW .= '<option value="' . $res->rw_id . '"' . ((!empty($res->rw_id)) ? ((!empty($res->rw_id == $rwID)) ? ('selected') : ('')) : ('')) . '>' . $res->rw . '</option>';
            } else {
                $resultRW .= '<option value="' . $res->rw_id . '"' . ((!empty($res->rw_id)) ? ((!empty($res->rw_id == $data->rw_id)) ? ('selected') : ('')) : ('')) . '>' . $res->rw . '</option>';
            }
        }

        $kelurahan = Kelurahan::select(
            'kelurahan.nama',
            'kelurahan.kelurahan_id'
        )
            ->when($checkRW, function ($query) use ($isKelurahan) {
                $query->where('kelurahan.kelurahan_id', $isKelurahan);
            })
            ->when($checkRT, function ($query) use ($isKelurahan) {
                $query->where('kelurahan.kelurahan_id', $isKelurahan);
            })
            ->orderBy('nama', 'ASC')
            ->get();
        $resultKelurahan = '<option disabled selected></option>';
        foreach ($kelurahan as $res) {
            if ($checkRW) {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '"' . ((!empty($res->kelurahan_id)) ? ((!empty($res->kelurahan_id == $isKelurahan)) ? ('selected') : ('')) : ('')) . '>' . $res->nama . '</option>';
            } else if ($checkRT) {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '"' . ((!empty($res->kelurahan_id)) ? ((!empty($res->kelurahan_id == $isKelurahan)) ? ('selected') : ('')) : ('')) . '>' . $res->nama . '</option>';
            } else {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '"' . ((!empty($res->kelurahan_id)) ? ((!empty($res->kelurahan_id == $data->kelurahan_id)) ? ('selected') : ('')) : ('')) . '>' . $res->nama . '</option>';
            }
        }

        return view($this->route1 . '.' . 'Kegiatan' . '.' . $this->route2 . '.' . $this->route3, compact('data', 'result', 'resultProvince', 'resultCity', 'resultSubdistrict', 'resultRT', 'resultRW', 'resultKelurahan'));
    }

    public function update(Request $request, $id, Kegiatan $model)
    {
        $id = \Crypt::decryptString($id);

        $data = $model->findOrFail($id);
        $input = $request->except('proengsoft_jsvalidation');
        $input['updated_at'] = date('Y-m-d H:i:s');
        $input['user_updated'] = \Auth::user()->user_id;

        \DB::beginTransaction();

        try {
            $data->update($input);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function destroy($id, Kegiatan $model)
    {
        return $this->master->destroy($id, $model);
    }

    public function dataTables()
    {
        $datatableButtons = method_exists(new Kegiatan, 'datatableButtons') ? Kegiatan::datatableButtons() : ['show', 'edit', 'destroy'];
        $data = Kegiatan::select(
            'kegiatan.kegiatan_id',
            'kegiatan.nama_kegiatan',
            'kat_kegiatan.nama_kat_kegiatan as nama_kat_kegiatan'
        )
            ->join('kat_kegiatan', 'kat_kegiatan.kat_kegiatan_id', 'kegiatan.kat_kegiatan_id')
            ->orderBy('kegiatan_id', 'desc')
            ->get();
        return \DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show'         => in_array("show", $datatableButtons) ? route('Master.ListKegiatan' . '.show', \Crypt::encryptString($data->kegiatan_id)) : null,
                    'edit'         => in_array("edit", $datatableButtons) ? route('Master.ListKegiatan' . '.edit', \Crypt::encryptString($data->kegiatan_id)) : null,
                    'ajax_destroy' => in_array("destroy", $datatableButtons) ? $data->kegiatan_id : null
                ]);
            })->rawColumns(['action'])
            ->make(true);
    }

    public function getAlamat($id)
    {
        $kegiatanGetAlamat = \DB::table('kegiatan')->where('kegiatan_id', $id)->first();
        $nama_kegiatan = [
            "subdistrict_id"   => "",
            "province_id"      => "",
            "province"         => "",
            "city_id"          => "",
            "city"             => "",
            "type"             => "",
            "subdistrict_name" => "",
            "nama_kegiatan"        => ""
        ];

        if ($kegiatanGetAlamat) {
            if (empty($kegiatanGetAlamat->subdistrict_id)) {
                $nama_kegiatan = json_decode($this->rajaOngkir->getSubdistrictDetailById(1579), true);
            } else {
                $nama_kegiatan = json_decode($this->rajaOngkir->getSubdistrictDetailById($kegiatanGetAlamat->subdistrict_id), true);
            }
            $nama_kegiatan['nama_kegiatan'] = $kegiatanGetAlamat->nama_kegiatan;
        }

        return $nama_kegiatan;
    }
}
