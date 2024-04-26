<?php

namespace App\Http\Controllers\Polling;
use App\Http\Controllers\Controller;
use App\Http\Requests\Polling\{ PertanyaanRequest };
use App\Repositories\{ PollingRepository }; 
use App\Repositories\RajaOngkir\{ RajaOngkirRepository };
use Illuminate\Http\Request;

class PertanyaanController extends Controller
{   
    public function __construct(RajaOngkirRepository $rajaOngkir, PollingRepository $_PollingRepository)
    {
        $route_name = explode('.', \Route::currentRouteName());
        $this->route1 = ((isset($route_name[0])) ? $route_name[0] : (''));
        $this->route2 = ((isset($route_name[1])) ? $route_name[1] : (''));
        $this->route3 = ((isset($route_name[2])) ? $route_name[2] : (''));
        $this->polling =  $_PollingRepository;
        $this->rajaOngkir = $rajaOngkir;
    }

    public function index() {
        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3);
    }

    public function create() {
        $provinces = $this->getProvinces();
        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('provinces'));
    }

    public function store(PertanyaanRequest $request) {
        return $this->polling->storeQuestion($request);
    }

    public function show($id) {
        $data = $this->polling->getQuestion(\Crypt::decrypt($id));
        $province = $this->rajaOngkir->getProvinceById($data->province_id);
        $city = $this->rajaOngkir->getCityById($data->city_id);
        $subdistrict = $this->rajaOngkir->getSubdistrictById($data->subdistrict_id);
        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data', 'province', 'city', 'subdistrict'));
    }

    public function edit($id) {
        $data = $this->polling->getQuestion(\Crypt::decrypt($id));
        $provinces = $this->getProvinces($data->province_id);
        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data', 'provinces'));
    }

    public function update(PertanyaanRequest $request, $id) {
        return $this->polling->updateQuestion($request, $id);
    }

    public function destroy($id) {
        return $this->polling->destroyQuestion($id);
    }

    public function dataTables() {
        return $this->polling->dataTablesQuestion();
    }

    public function removeAnswer(Request $request) {
        if ($request->ajax()) {
            return $this->polling->removeAnswer($request);
        }
    }

    public function getSelectedState($key, $state) {
        return ((!empty($state)) ? (($state == $key) ? ('selected') : ('')) : (''));
    }

    public function optionSelect($data, $id = '') {
        $result = '<option></option>';
        foreach ($data as $key => $value) {
            $result .= '<option value="' . $key . '" ' . $this->getSelectedState($key, $id) . '>' . $value . '</option>';
        }
        return $result;
    }

    public function getProvinces($id = '') {
        $provinces = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');
        return $this->optionSelect($provinces, $id);
    }

    public function getCitiesByProvince(Request $request) {
        if ($request->ajax()) {
            $cities = $this->rajaOngkir->getCitiesByProvince($request->id)
                ->map(function ($value) {
                    $value->city_name = $value->type . ' ' . $value->city_name;
                    return $value;
                })->pluck('city_name', 'city_id');
            return $this->optionSelect($cities, $request->thisID);
        }
        return false;
    }

    public function getSubdistrictsByCity(Request $request) {
        if ($request->ajax()) {
            $subdistrict = $this->rajaOngkir->getSubdistrictsByCity($request->id)->pluck('subdistrict_name', 'subdistrict_id');
            return $this->optionSelect($subdistrict, $request->thisID);
        }
        return false;
    }

    public function getWardsBySubdistrict(Request $request) {
        if ($request->ajax()) {
            $wards = \DB::table('kelurahan')->where(['subdistrict_id' => $request->id])->pluck('nama', 'kelurahan_id');
            return $this->optionSelect($wards, $request->thisID);
        }
        return false;
    }

    public function getRWsByWard(Request $request) {
        if ($request->ajax()) {
            $rw = \DB::table('rw')->where(['kelurahan_id' => $request->id])->pluck('rw', 'rw_id');
            return $this->optionSelect($rw, $request->thisID);
        }
        return false;
    }

    public function getRTsByRW(Request $request) {
        if ($request->ajax()) {
            $rt = \DB::table('rt')->where(['rw_id' => $request->id])->pluck('rt', 'rt_id');
            return $this->optionSelect($rt, $request->thisID);
        }
        return false;
    }
}