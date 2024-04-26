<?php

namespace App\Http\Controllers\Master\Kelurahan;

use App\Http\Controllers\Controller;
use App\Models\Master\Kelurahan;
use App\Repositories\Master\KelurahanRepository;
use App\Repositories\RajaOngkir\RajaOngkirRepository;
use Illuminate\Http\Request;

class KelurahanController extends Controller
{
    public function __construct(KelurahanRepository $_KelurahanRepository, RajaOngkirRepository $rajaOngkir)
    {
        $route_name = explode('.', \Route::currentRouteName());
        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

        $this->kelurahan = $_KelurahanRepository;
        $this->rajaOngkir = $rajaOngkir;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $provinces = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');
        $isAdmin = \helper::checkUserRole('admin');

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('isAdmin', 'provinces'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $provinces = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');
        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->kelurahan->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = \Crypt::decryptString($id);
        $data = Kelurahan::select('kelurahan_id', 'nama', 'alamat', 'kode_pos')->findOrFail($id);
        $kelurahanGetAlamat = $this->kelurahan->getAlamat($id);

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data', 'kelurahanGetAlamat'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = \Crypt::decryptString($id);
        $data = Kelurahan::select('kelurahan_id', 'nama', 'alamat', 'kode_pos')->findOrFail($id);

        $kelurahanGetAlamat = $this->kelurahan->getAlamat($id);
        
        $provinces = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');
        $resultProvince = '<option disabled selected>Pilih Provinsi</option>';
        foreach ($provinces as $province_id => $province) {
            $resultProvince .= '<option value="' . $province_id . '"' . ((!empty($province_id)) ? ((!empty($province_id == $kelurahanGetAlamat['province_id'])) ? ('selected') : ('')) : ('')) . '>' . $province . '</option>';
        }

        $cities = $kelurahanGetAlamat['province_id'] != ''
        ? $this->rajaOngkir->getCitiesByProvince($kelurahanGetAlamat['province_id'])
        ->map(function ($value) {
            $value->city_name = $value->type . ' ' . $value->city_name;

            return $value;
        })
        ->pluck('city_name', 'city_id') : [];
        $resultCity = '<option disabled selected>Pilih Kota/Kabupaten</option>';
        foreach ($cities as $city_id => $city_name) {
            $resultCity .= '<option value="' . $city_id . '"' . ((!empty($city_id)) ? ((!empty($city_id == $kelurahanGetAlamat['city_id'])) ? ('selected') : ('')) : ('')) . '>' . $city_name . '</option>';
        }

        $subdistricts = $kelurahanGetAlamat['city_id'] != ''
            ? $this->rajaOngkir->getSubdistrictsByCity($kelurahanGetAlamat['city_id'])
            ->pluck('subdistrict_name', 'subdistrict_id') : [];
        $resultSubdistrict = '<option disabled selected>Pilih Kecamatan</option>';
        foreach ($subdistricts as $subdistrict_id => $subdistrict_name) {
            $resultSubdistrict .= '<option value="' . $subdistrict_id . '"' . ((!empty($subdistrict_id)) ? ((!empty($subdistrict_id == $kelurahanGetAlamat['subdistrict_id'])) ? ('selected') : ('')) : ('')) . '>' . $subdistrict_name . '</option>';
        }

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data', 'resultProvince', 'resultCity', 'resultSubdistrict'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = \Crypt::decryptString($id);

        return $this->kelurahan->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->kelurahan->delete($id);
    }

    public function dataTables(Request $request)
    {
        return $this->kelurahan->dataTables($request);
    }
}
