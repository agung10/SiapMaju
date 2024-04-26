<?php

namespace App\Repositories\Master;

use App\Models\Master\Keluarga\AnggotaKeluargaAlamat;
use App\Repositories\BaseRepository;
use App\Repositories\RajaOngkir\RajaOngkirRepository;

class AnggotaKeluargaAlamatRepository extends BaseRepository
{
    public function __construct(AnggotaKeluargaAlamat $anggotaKeluargaAlamat, RajaOngkirRepository $rajaOngkir)
    {
        $this->model      = $anggotaKeluargaAlamat;
        $this->rajaOngkir = $rajaOngkir;
    }

    public function getAlamat($anggotaKeluargaId)
    {
        $anggotaKeluargaAlamat = $this->where('anggota_keluarga_id', $anggotaKeluargaId)->first();
        $alamat = [
            "subdistrict_id"   => "",
            "province_id"      => "",
            "province"         => "",
            "city_id"          => "",
            "city"             => "",
            "type"             => "",
            "subdistrict_name" => "",
            "alamat"           => ""
        ];

        if($anggotaKeluargaAlamat)
        {
            $alamat = json_decode($this->rajaOngkir->getSubdistrictDetailById($anggotaKeluargaAlamat->subdistrict_id), true);
            $alamat['alamat'] = $anggotaKeluargaAlamat->alamat;
        }

        return $alamat;
    }
}