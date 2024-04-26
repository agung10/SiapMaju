<?php

namespace App\Http\Controllers\Master\RT;

use App\Http\Controllers\Controller;
use App\Models\Master\Kelurahan;
use App\Models\Master\RW;
use Illuminate\Http\Request;
use App\Repositories\Master\RTRepository;
use App\Repositories\RajaOngkir\RajaOngkirRepository;

class RTController extends Controller
{
    public function __construct(RTRepository $_RTRepository, RajaOngkirRepository $rajaOngkir)
    {
        $route_name = explode('.', \Route::currentRouteName());
        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

        $this->rt = $_RTRepository;
        $this->rajaOngkir = $rajaOngkir;
    }

    public function index()
    {
        $provinces = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');
        $isAdmin = \helper::checkUserRole('admin');

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('isAdmin', 'provinces'));
    }

    public function create()
    {
        $provinces = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('provinces'));
    }

    public function store(Request $request)
    {
        return $this->rt->store($request);
    }

    public function show($id)
    {
        $id = \Crypt::decryptString($id);
        $data = $this->rt->show($id);

        $rtGetAlamat = $this->rt->getAlamat($id);

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data', 'rtGetAlamat'));
    }

    public function edit($id)
    {
        $id = \Crypt::decryptString($id);
        $data = $this->rt->show($id);

        $rtGetAlamat = $this->rt->getAlamat($id);
        $rw = RW::select(
            'rw.rw',
            'rw.rw_id',
            'rw.kelurahan_id',
        )
            ->where('rw.kelurahan_id', $data->kelurahan_id)
            ->orderBy('rw', 'ASC')
            ->get();
        $resultRW = '<option disabled selected></option>';
        foreach ($rw as $res) {
            $resultRW .= '<option value="' . $res->rw_id . '"' . ((!empty($res->rw_id)) ? ((!empty($res->rw_id == $data->rw_id)) ? ('selected') : ('')) : ('')) . '>' . $res->rw . '</option>';
        }

        $kelurahan = Kelurahan::select(
            'kelurahan.nama',
            'kelurahan.kelurahan_id'
        )
            ->where('subdistrict_id', $rtGetAlamat['subdistrict_id'])
            ->orderBy('nama', 'ASC')
            ->get();
        $resultKelurahan = '<option disabled selected></option>';
        foreach ($kelurahan as $res) {
            $resultKelurahan .= '<option value="' . $res->kelurahan_id . '"' . ((!empty($res->kelurahan_id)) ? ((!empty($res->kelurahan_id == $data->kelurahan_id)) ? ('selected') : ('')) : ('')) . '>' . $res->nama . '</option>';
        }

        $provinces   = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');
        $resultProvince = '<option disabled selected>Pilih Provinsi</option>';
        foreach ($provinces as $province_id => $province) {
            $resultProvince .= '<option value="' . $province_id . '"' . ((!empty($province_id)) ? ((!empty($province_id == $rtGetAlamat['province_id'])) ? ('selected') : ('')) : ('')) . '>' . $province . '</option>';
        }

        $cities      = $rtGetAlamat['province_id'] != ''
            ? $this->rajaOngkir->getCitiesByProvince($rtGetAlamat['province_id'])
            ->map(function ($value) {
                $value->city_name = $value->type . ' ' . $value->city_name;
                return $value;
            })
            ->pluck('city_name', 'city_id') : [];
        $resultCity = '<option disabled selected>Pilih Kota/Kabupaten</option>';
        foreach ($cities as $city_id => $city_name) {
            $resultCity .= '<option value="' . $city_id . '"' . ((!empty($city_id)) ? ((!empty($city_id == $rtGetAlamat['city_id'])) ? ('selected') : ('')) : ('')) . '>' . $city_name . '</option>';
        }

        $subdistricts           = $rtGetAlamat['city_id'] != ''
            ? $this->rajaOngkir->getSubdistrictsByCity($rtGetAlamat['city_id'])
            ->pluck('subdistrict_name', 'subdistrict_id') : [];
        $resultSubdistrict = '<option disabled selected>Pilih Kecamatan</option>';
        foreach ($subdistricts as $subdistrict_id => $subdistrict_name) {
            $resultSubdistrict .= '<option value="' . $subdistrict_id . '"' . ((!empty($subdistrict_id)) ? ((!empty($subdistrict_id == $rtGetAlamat['subdistrict_id'])) ? ('selected') : ('')) : ('')) . '>' . $subdistrict_name . '</option>';
        }

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data', 'resultRW', 'resultKelurahan', 'resultProvince', 'resultCity', 'resultSubdistrict'));
    }

    public function update(Request $request, $id)
    {
        $id = \Crypt::decryptString($id);

        return $this->rt->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->rt->delete($id);
    }

    public function dataTables(Request $request)
    {
        return $this->rt->dataTables($request);
    }
}
