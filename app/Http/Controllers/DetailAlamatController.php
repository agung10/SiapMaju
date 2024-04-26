<?php

namespace App\Http\Controllers;

use App\Models\Master\Blok;
use App\Models\Master\Kelurahan;
use App\Models\Master\RT;
use App\Models\Master\RW;
use App\Repositories\RajaOngkir\RajaOngkirRepository;
use Illuminate\Http\Request;

class DetailAlamatController extends Controller
{
    public function __construct(RajaOngkirRepository $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    public function getCities(Request $request)
    {
        return $this->rajaOngkir->getCitiesByProvince($request->province_id);
    }

    public function getSubdistricts(Request $request)
    {
        return $this->rajaOngkir->getSubdistrictsByCity($request->city_id);
    }

    public function getKelurahan(Request $request) {
        $kelurahan = Kelurahan::select('kelurahan_id', 'nama')->where('subdistrict_id', $request->subdistrictID)->orderBy('nama', 'ASC')->get();
        return response()->json($kelurahan);
    }

    public function getRW(Request $request) {
        $rw = RW::select('rw_id', 'rw')->where('kelurahan_id', $request->kelurahanID)->orderBy('rw', 'ASC')->get();
        return response()->json($rw);
    }

    public function getRT(Request $request) {
        $rt = RT::select('rt_id', 'rt')->where('rw_id', $request->rwID)->orderBy('rt', 'ASC')->get();
        return response()->json($rt);
    }

    public function getBlok(Request $request) {
        $blok = Blok::select('blok_id', 'nama_blok')->where('rt_id', $request->rtID)->orderBy('nama_blok', 'ASC')->get();
        return response()->json($blok);
    }
}
