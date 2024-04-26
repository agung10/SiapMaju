<?php

namespace App\Http\Controllers\Master\Kelurahan;

use App\Http\Controllers\Controller;
use App\Models\Master\TandaTanganKelurahan;
use App\Repositories\Master\TandaTanganKelurahanRepository;
use App\Repositories\Master\KelurahanRepository;
use App\Repositories\RajaOngkir\RajaOngkirRepository;
use Illuminate\Http\Request;

class TandaTanganKelurahanController extends Controller
{
    public function __construct(TandaTanganKelurahanRepository $tandaTanganKelurahanRepository, RajaOngkirRepository $rajaOngkir, KelurahanRepository $kelurahan)
    {
        $route_name = explode('.', \Route::currentRouteName());
        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

        $this->ttdKelurahan = $tandaTanganKelurahanRepository;
        $this->rajaOngkir = $rajaOngkir;
        $this->kelurahan = $kelurahan;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $provinces = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');
        $resultProvince = '<option></option>';
        foreach ($provinces as $province_id => $province) {
            $resultProvince .= '<option value="' . $province_id . '">' . $province . '</option>';
        }
        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('resultProvince'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $provinces = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');
        $resultProvince = '<option></option>';
        foreach ($provinces as $province_id => $province) {
            $resultProvince .= '<option value="' . $province_id . '">' . $province . '</option>';
        }

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('resultProvince'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->ttdKelurahan->store($request);
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
        $data = TandaTanganKelurahan::select(
            'tanda_tangan_kelurahan.tanda_tangan_kelurahan',
            'kelurahan.nama',
        )
        ->join('kelurahan', 'kelurahan.kelurahan_id', 'tanda_tangan_kelurahan.kelurahan_id')
        ->findOrFail($id);

        $detailAlamat = $this->kelurahan->getAlamat($id);
        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data', 'detailAlamat'));
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
        $data = TandaTanganKelurahan::select(
            'tanda_tangan_kelurahan.tanda_tangan_kelurahan_id',
            'tanda_tangan_kelurahan.tanda_tangan_kelurahan',
            'kelurahan.kelurahan_id',
            'kelurahan.nama',
            'kelurahan.province_id',
            'kelurahan.city_id',
            'kelurahan.subdistrict_id',
        )
        ->join('kelurahan', 'kelurahan.kelurahan_id', 'tanda_tangan_kelurahan.kelurahan_id')
        ->findOrFail($id);

        $provinces = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');
        $resultProvince = '<option></option>';
        foreach ($provinces as $province_id => $province) {
            $resultProvince .= '<option value="' . $province_id . '"' . ((!empty($province_id)) ? ((!empty($province_id == $data->province_id)) ? ('selected') : ('')) : ('')) . '>' . $province . '</option>';
        }

        $cities = $this->rajaOngkir->getCitiesByProvince($data->province_id)
        ->map(function ($value) {
            $value->city_name = $value->type . ' ' . $value->city_name;
            return $value;
        })
        ->pluck('city_name', 'city_id');
        $resultCity = '<option></option>';
        foreach ($cities as $city_id => $city_name) {
            $resultCity .= '<option value="' . $city_id . '"' . ((!empty($city_id)) ? ((!empty($city_id == $data->city_id)) ? ('selected') : ('')) : ('')) . '>' . $city_name . '</option>';
        }

        $subdistricts = $this->rajaOngkir->getSubdistrictsByCity($data->city_id)->pluck('subdistrict_name', 'subdistrict_id');
        $resultSubdistrict = '<option></option>';
        foreach ($subdistricts as $subdistrict_id => $subdistrict_name) {
            $resultSubdistrict .= '<option value="' . $subdistrict_id . '"' . ((!empty($subdistrict_id)) ? ((!empty($subdistrict_id == $data->subdistrict_id)) ? ('selected') : ('')) : ('')) . '>' . $subdistrict_name . '</option>';
        }

        $kelurahan = \DB::table('kelurahan')->select('kelurahan_id', 'nama')->pluck('nama', 'kelurahan_id');
        $resultKelurahan = '<option disabled selected></option>';
        foreach ($kelurahan as $kelurahan_id => $nama) {
            $resultKelurahan .= '<option value="'.$kelurahan_id.'"'.((!empty($kelurahan_id)) ? ((!empty($kelurahan_id == $data->kelurahan_id)) ? ('selected') : ('')) : ('')).'>'.$nama.'</option>';
        }

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data', 'resultProvince', 'resultCity', 'resultSubdistrict', 'resultKelurahan'));
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

        return $this->ttdKelurahan->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->ttdKelurahan->delete($id);
    }

    public function dataTables(Request $request)
    {
        return $this->ttdKelurahan->dataTables($request);
    }
}