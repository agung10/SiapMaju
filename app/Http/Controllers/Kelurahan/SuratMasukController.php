<?php

namespace App\Http\Controllers\Kelurahan;
use App\Http\Controllers\Controller;
use App\Models\Surat\SuratPermohonan;
use App\Repositories\Kelurahan\{ SuratMasukRepository };
use App\Repositories\RajaOngkir\{ RajaOngkirRepository };
use App\Models\Surat\SuratPermohonanLampiran;
use App\Repositories\Surat\SuratPermohonanRepository;
use Illuminate\Http\Request;

class SuratMasukController extends Controller
{
    public function __construct(RajaOngkirRepository $rajaOngkir, SuratMasukRepository $_SuratMasukRepository, SuratPermohonanRepository $_SuratPermohonanRepository) {
        $route_name = explode('.',\Route::currentRouteName());
        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

        $this->rajaOngkir = $rajaOngkir;
        $this->surat = $_SuratMasukRepository;
        $this->suratPermohonan = $_SuratPermohonanRepository;
    }

    public function index() {
        return view("$this->route1.$this->route2.$this->route3");
    }

    public function create() {}

    public function store(Request $request) {}

    public function show($id) {
        $data = SuratPermohonan::findOrFail(\Crypt::decryptString($id));
        $lampiranSurat = SuratPermohonanLampiran::where('surat_permohonan_id', $data->surat_permohonan_id)
            ->join('lampiran', 'lampiran.lampiran_id', 'surat_permohonan_lampiran.lampiran_id')
            ->get();

        $selectNamaWarga = $this->suratPermohonan->selectNamaWarga($data->anggota_keluarga_id);
        $jenisSurat = \helper::select('jenis_surat','jenis_permohonan', $data->jenis_surat_id);
        $agama = \helper::select('agama','nama_agama', $data->agama_id);
        $pernikahan = \helper::select('status_pernikahan','nama_status_pernikahan', $data->status_pernikahan_id);
        $anggota = \DB::table('anggota_keluarga')->where('anggota_keluarga_id',\Auth::user()->anggota_keluarga_id)->first();
        $province = $this->rajaOngkir->getProvinceById($data->province_id);
        $city = $this->rajaOngkir->getCityById($data->city_id);
        $subdistrict = $this->rajaOngkir->getSubdistrictById($data->subdistrict_id);

        return view($this->route1.'.'.$this->route2.'.'.$this->route3,compact('data', 'selectNamaWarga','jenisSurat', 'agama', 'pernikahan', 'anggota', 'lampiranSurat', 'province', 'city', 'subdistrict'));
    }

    public function edit($id) {}

    public function update(Request $request, $id) {
        return $this->surat->updateHelpdesk($request, \Crypt::decryptString($id));
    }

    public function destroy($id) {}

    public function storeLetterContent(Request $request, $id) {
        if ($request->ajax()) {
            return $this->surat->storeLetterContent($request, \Crypt::decryptString($id));
        }
    }

    public function dataTablesNoSearch() {
        return $this->surat->dataTablesNoSearch();
    }

    public function dataTables(Request $request) {
        $no_surat = $request->all()['no_surat'];
        return $this->surat->dataTables($no_surat);
    }
}
