<?php
 
namespace App\Services\RajaOngkir;
 
use Illuminate\Support\Facades\Http;

class RajaOngkirService {

    /**
     *
     * Rajaongkir courier list.
     *
     * @access  protected
     * @type array
     */
    protected $couriersList = [
        'tiki' => 'Citra Van Titipan Kilat (TIKI)'
    ];

    public function __construct()
    {
        $this->apiKey         = config('rajaongkir.api_key');
        $this->baseUrl        = config('rajaongkir.base_url');
        $this->provinceUrl    = $this->baseUrl . 'province';
        $this->cityUrl        = $this->baseUrl . 'city';
        $this->subdistrictUrl = $this->baseUrl . 'subdistrict';
        $this->costUrl        = $this->baseUrl . 'cost';
        $this->response       = collect();
    }

    /**
     * Curl request API caller.
     *
     * @param string $url
     * @param array $params
     * @param string $type
     *
     * @return  object|error Throw error if failed.
     */
    public function apiRequest(string $url, array $params = [], $type = 'get')
    {
        $request = Http::withHeaders(['key' => $this->apiKey]);
        $response = $type === 'get' ? $request->get($url, $params) : $request->post($url, $params);
        $successfulResponse = $response->successful() && $response->object()->rajaongkir->status->description === 'OK';

        if($successfulResponse) {
            $this->response = $response->object()->rajaongkir->results;

            return $this;   
        } 
    
        return $response->throw();
    }

    /**
     * @return  collection|error Throw error if failed.
     */
    public function courier()
    {
        $this->response = collect($this->couriersList)->sort();

        return $this;
    }

    /**
     * @return  collection|error Throw error if failed.
     */
    public function get()
    {
        return collect($this->response);
    }

    /**
     * Get list of provinces.
     *
     * @return  object|error Throw error if failed.
     */
    public function provinces()
    {
        return $this->apiRequest($this->provinceUrl);
    }

    /**
     * Get detail of single province.
     *
     * @param string $provinceId Province ID
     *
     * @return  object|error Throw error if failed.
     */
    public function province(string $provinceId)
    {
        $params = ['id' => $provinceId];

        return $this->apiRequest($this->provinceUrl, $params);
    }

    /**
     * Get list of cities.
     *
     * @return  object|error Throw error if failed.
     */
    public function cities()
    {
        return $this->apiRequest($this->cityUrl);
    }

    /**
     * Get detail of single city.
     *
     * @param string $cityId City ID
     *
     * @return  array|error Throw error if failed.
     */
    public function city(string $cityId)
    {
        $params = ['id' => $cityId];

        return $this->apiRequest($this->cityUrl, $params);
    }

    /**
     * Get list of province cities.
     *
     * @param string $provinceId Province ID
     *
     * @return  object|error Throw error if failed.
     */
    public function cityByProvince(string $provinceId)
    {
        $params = ['province' => $provinceId];

        return $this->apiRequest($this->cityUrl, $params);
    }

    /**
     * Get detail of single subdistrict.
     *
     * @param string $subdistrictId Subdistrict ID
     *
     * @return  array|error Throw error if failed.
     */
    public function subdistrict(string $subdistrictId)
    {
        $params = ['id' => $subdistrictId];

        return $this->apiRequest($this->subdistrictUrl, $params);
    }

    /**
     * Get list of city subdistricts.
     *
     * @param string $cityId City ID
     *
     * @return  object|error Throw error if failed.
     */
    public function subdistrictByCity(string $cityId)
    {
        $params = ['city' => $cityId];

        return $this->apiRequest($this->subdistrictUrl, $params);
    }
}