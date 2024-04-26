<?php

namespace App\Http\Controllers\Master\RW;

use App\Http\Controllers\Controller;
use App\Models\Master\Kelurahan;
use Illuminate\Http\Request;
use App\Repositories\Master\RWRepository;
use App\Repositories\RajaOngkir\RajaOngkirRepository;

class RWController extends Controller
{
    public function __construct(RWRepository $_RWRepository, RajaOngkirRepository $rajaOngkir)
    {
        $route_name = explode('.', \Route::currentRouteName());
        $this->route1  = $route_name[0] ?? '';
        $this->route2  = $route_name[1] ?? '';
        $this->route3  = $route_name[2] ?? '';

        $this->rw = $_RWRepository;
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
        return $this->rw->store($request);
    }

    public function show($id)
    {
        $id = \Crypt::decryptString($id);
        $data = $this->rw->show($id);
        $rwGetAlamat = $this->rw->getAlamat($id);

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data', 'rwGetAlamat'));
    }

    public function edit($id)
    {
        $id = \Crypt::decryptString($id);
        $data = $this->rw->show($id);

        $rwGetAlamat = $this->rw->getAlamat($id);

        $kelurahan = Kelurahan::select(
            'kelurahan.nama',
            'kelurahan.kelurahan_id'
        )
            ->where('subdistrict_id', $rwGetAlamat['subdistrict_id'])
            ->orderBy('nama', 'ASC')
            ->get();
        $resultKelurahan = '<option disabled selected></option>';
        foreach ($kelurahan as $res) {
            $resultKelurahan .= '<option value="' . $res->kelurahan_id . '"' . ((!empty($res->kelurahan_id)) ? ((!empty($res->kelurahan_id == $data->kelurahan_id)) ? ('selected') : ('')) : ('')) . '>' . $res->nama . '</option>';
        }

        $provinces   = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');
        $resultProvince = '<option disabled selected>Pilih Provinsi</option>';
        foreach ($provinces as $province_id => $province) {
            $resultProvince .= '<option value="' . $province_id . '"' . ((!empty($province_id)) ? ((!empty($province_id == $rwGetAlamat['province_id'])) ? ('selected') : ('')) : ('')) . '>' . $province . '</option>';
        }

        $cities = $rwGetAlamat['province_id'] != ''
            ? $this->rajaOngkir->getCitiesByProvince($rwGetAlamat['province_id'])
            ->map(function ($value) {
                $value->city_name = $value->type . ' ' . $value->city_name;
                return $value;
            })
            ->pluck('city_name', 'city_id') : [];
        $resultCity = '<option disabled selected>Pilih Kota/Kabupaten</option>';
        foreach ($cities as $city_id => $city_name) {
            $resultCity .= '<option value="' . $city_id . '"' . ((!empty($city_id)) ? ((!empty($city_id == $rwGetAlamat['city_id'])) ? ('selected') : ('')) : ('')) . '>' . $city_name . '</option>';
        }

        $subdistricts = $rwGetAlamat['city_id'] != ''
            ? $this->rajaOngkir->getSubdistrictsByCity($rwGetAlamat['city_id'])
            ->pluck('subdistrict_name', 'subdistrict_id') : [];
        $resultSubdistrict = '<option disabled selected>Pilih Kecamatan</option>';
        foreach ($subdistricts as $subdistrict_id => $subdistrict_name) {
            $resultSubdistrict .= '<option value="' . $subdistrict_id . '"' . ((!empty($subdistrict_id)) ? ((!empty($subdistrict_id == $rwGetAlamat['subdistrict_id'])) ? ('selected') : ('')) : ('')) . '>' . $subdistrict_name . '</option>';
        }

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data', 'resultKelurahan', 'resultProvince', 'resultCity', 'resultSubdistrict'));
    }

    public function update(Request $request, $id)
    {
        $id = \Crypt::decryptString($id);

        return $this->rw->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->rw->delete($id);
    }

    public function dataTables(Request $request)
    {
        return $this->rw->dataTables($request);
    }
}
