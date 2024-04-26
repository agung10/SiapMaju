<?php

namespace App\Http\Controllers\Kelurahan;
use App\Http\Controllers\Controller;
use App\Models\Surat\SuratPermohonanLampiran;
use App\Repositories\Kelurahan\{ SuratMasukRepository };
use App\Repositories\RajaOngkir\{ RajaOngkirRepository };
use Illuminate\Http\Request;

class LurahController extends Controller
{
    public function __construct(RajaOngkirRepository $rajaOngkir, SuratMasukRepository $_SuratMasukRepository) {
        $route_name = explode('.', \Route::currentRouteName());
        $this->route1 = (($route_name[0]) ?? (''));
        $this->route2 = (($route_name[1]) ?? (''));
        $this->route3 = (($route_name[2]) ?? (''));
        $this->rajaOngkir = $rajaOngkir;
        $this->surat = $_SuratMasukRepository;
    }

    public function index() {
        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3);
    }

    public function create() {}

    public function store(Request $request) {}

    public function show($id) {
        $data = $this->surat->getRequestLetterData(\Crypt::decryptString($id));
        $lampiranSurat = SuratPermohonanLampiran::where('surat_permohonan_id', $data->surat_permohonan_id)
        ->join('lampiran', 'lampiran.lampiran_id', 'surat_permohonan_lampiran.lampiran_id')
        ->get();
        $province = $this->rajaOngkir->getProvinceById($data->province_id);
        $city = $this->rajaOngkir->getCityById($data->city_id);
        $subdistrict = $this->rajaOngkir->getSubdistrictById($data->subdistrict_id);
        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data', 'province', 'city', 'subdistrict', 'lampiranSurat'));
    }

    public function edit($id) {}

    public function update(Request $request, $id) {
        return $this->surat->updateGroove($request, \Crypt::decryptString($id));
    }

    public function destroy($id) {}

    public function dataTablesGrooveNoSearch() {
        return $this->surat->dataTablesGrooveNoSearch();
    }

    public function dataTables(Request $request) {
        return $this->surat->dataTablesGroove($request);
    }
}
