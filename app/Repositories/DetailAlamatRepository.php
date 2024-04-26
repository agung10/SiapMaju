<?php

namespace App\Repositories;

use App\Repositories\RajaOngkir\RajaOngkirRepository;

class DetailAlamatRepository
{
    public function __construct(RajaOngkirRepository $rajaOngkir)
    {
        $this->rajaOngkir =  $rajaOngkir;
    }
}