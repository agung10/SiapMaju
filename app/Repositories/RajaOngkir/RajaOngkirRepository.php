<?php

namespace App\Repositories\RajaOngkir;

use App\Services\RajaOngkir\RajaOngkirService;
use Illuminate\Support\Facades\Redis;

class RajaOngkirRepository
{
    protected $sixHour      = 21600; // default expiration time cache

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir     = $rajaOngkir;
        $this->redisNotActive = !(config('rajaongkir.use_redis') === true);
    }

    public function getProvinces()
    {
        if ($this->redisNotActive) {
            return $this->rajaOngkir->provinces()->get();
        }

        $provinces = Redis::get('provinces');

        if(!isset($provinces)) 
        {
            $provinces = $this->rajaOngkir->provinces()->get();
            Redis::set('provinces', $provinces, 'EX', $this->sixHour);
        }
        else
        {
            $provinces = collect(json_decode($provinces));
        }

        return $provinces;
    }

    public function getProvinceById($id)
    {
        if ($this->redisNotActive) {
            return $this->rajaOngkir->province($id)->get()['province'];
        }

        if(is_null($id)) return $id;
        
        $province = Redis::get('province_' . $id);

        if(!isset($province)) 
        {
            $province = $this->rajaOngkir->province($id)->get()['province'];
            Redis::set('province_' . $id, $province, 'EX', $this->sixHour);
        }

        return $province;
    }

    public function getCities()
    {
        if ($this->redisNotActive) {
            return $this->rajaOngkir->cities()->get();
        }

        $cities = Redis::get('cities');

        if(!isset($cities)) 
        {
            $cities = $this->rajaOngkir->cities()->get();
            Redis::set('cities', $cities, 'EX', $this->sixHour);
        }
        else
        {
            $cities = collect(json_decode($cities));
        }

        return $cities;
    }

    public function getCityById($id)
    {
        if ($this->redisNotActive) {
            return $this->rajaOngkir->city($id)->get()['city_name'];
        }

        if(is_null($id)) return $id;

        $city = Redis::get('city_' . $id);

        if(!isset($city)) 
        {
            $city = $this->rajaOngkir->city($id)->get()['city_name'];
            Redis::set('city_' . $id, $city, 'EX', $this->sixHour);
        }

        return $city;
    }

    public function getSubdistrictById($id)
    {
        if ($this->redisNotActive) {
            return $this->rajaOngkir->subdistrict($id)->get()['subdistrict_name'];
        }

        $subdistrict = Redis::get('subdistrict_' . $id);

        if(!isset($subdistrict)) 
        {
            $subdistrict = $this->rajaOngkir->subdistrict($id)->get()['subdistrict_name'];
            Redis::set('subdistrict_' . $id, $subdistrict, 'EX', $this->sixHour);
        }

        return $subdistrict;
    }

    public function getSubdistrictDetailById($id)
    {
        if ($this->redisNotActive) {
            return $this->rajaOngkir->subdistrict($id)->get();
        }

        $subdistrict = Redis::get('subdistrict_detail_' . $id);

        if(!isset($subdistrict)) 
        {
            $subdistrict = $this->rajaOngkir->subdistrict($id)->get();
            Redis::set('subdistrict_detail_' . $id, $subdistrict, 'EX', $this->sixHour);
        }

        return $subdistrict;
    }

    public function getCouriers()
    {
        return $this->rajaOngkir->courier()->get();
    }

    public function getCourierById($id)
    {
        if ($this->redisNotActive) {
            return $this->rajaOngkir->courier()->get()[$id];
        }

        $courier = Redis::get('courier_' . $id);

        if(!isset($courier)) 
        {
            $courier = $this->rajaOngkir->courier()->get()[$id];
            Redis::set('courier_' . $id, $courier, 'EX', $this->sixHour);
        }

        return $courier;
    }

    public function getCitiesByProvince($provinceId)
    {
        if ($this->redisNotActive) {
            return $this->rajaOngkir->cityByProvince($provinceId)->get();
        }

        $cities = Redis::get('cities_province_'. $provinceId);

        if(!isset($cities)) 
        {
            $cities = $this->rajaOngkir->cityByProvince($provinceId)->get();
            Redis::set('cities_province_' . $provinceId, $cities, 'EX', $this->sixHour);
        }
        else
        {
            $cities = collect(json_decode($cities));
        }

        return $cities;
    }

    public function getSubdistrictsByCity($cityId)
    {
        if ($this->redisNotActive) {
            return $this->rajaOngkir->subdistrictByCity($cityId)->get();
        }

        $subdistricts = Redis::get('subdistricts_city_'. $cityId);

        if(!isset($subdistricts)) 
        {
            $subdistricts = $this->rajaOngkir->subdistrictByCity($cityId)->get();
            Redis::set('subdistricts_city_' . $cityId, $subdistricts, 'EX', $this->sixHour);
        }
        else
        {
            $subdistricts = collect(json_decode($subdistricts));
        }

        return $subdistricts;
    }

    public function getAddress($subdistrictId)
    {
        if(is_null($subdistrictId)) return '-';

        $address = $this->rajaOngkir->subdistrict($subdistrictId)->get();

        return $address['subdistrict_name'] .', '. $address['city'] .', '. $address['province'];
    }

    public function getCost($subdistrictId, $subdistrictDestination, $berat, $kurir)
    {
        return $this->rajaOngkir->getCost($subdistrictId, $subdistrictDestination, $berat, $kurir);
    }
}