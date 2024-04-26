<?php

namespace App\Http\Controllers\Kelurahan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Kelurahan\SuratRepository;
use App\Models\Surat\SuratPermohonan;
use App\Models\Surat\SuratPermohonanLampiran;
use App\Repositories\Master\AnggotaKeluargaRepository;
use App\Repositories\Surat\SuratPermohonanRepository;

class SuratController extends Controller
{
    public function __construct(SuratRepository $_SuratRepository,SuratPermohonanRepository $_SuratPermohonanRepository, AnggotaKeluargaRepository $_AnggotaKeluargaRepository)
    {
        $route_name = explode('.',\Route::currentRouteName());
        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

        $this->surat = $_SuratRepository;
        $this->suratPermohonan = $_SuratPermohonanRepository;
        $this->anggota = $_AnggotaKeluargaRepository;
    }

    public function index()
    {
        return view("$this->route1.$this->route2.$this->route3");
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $suratId = \Crypt::decryptString($id);
        $data = SuratPermohonan::findOrFail($suratId);

        $lampiranSurat = SuratPermohonanLampiran::where('surat_permohonan_id', $data->surat_permohonan_id)
            ->join('lampiran', 'lampiran.lampiran_id', 'surat_permohonan_lampiran.lampiran_id')
            ->get();

        $selectNamaWarga = $this->suratPermohonan->selectNamaWarga($data->anggota_keluarga_id);

        $jenisSurat = \helper::select('jenis_surat','jenis_permohonan', $data->jenis_surat_id);
        $agama = \helper::select('agama','nama_agama', $data->agama_id);
        $pernikahan = \helper::select('status_pernikahan','nama_status_pernikahan', $data->status_pernikahan_id);
        
        $anggota = \DB::table('anggota_keluarga')->where('anggota_keluarga_id',\Auth::user()->anggota_keluarga_id)->first();

        return view($this->route1.'.'.$this->route2.'.'.$this->route3,compact('data', 'selectNamaWarga','jenisSurat', 'agama', 'pernikahan', 'anggota', 'lampiranSurat'));
    }

    public function edit(Request $request, $id) {
        if ($request->ajax()) {
            $data = $this->surat->editLetter(\Crypt::decryptString($id));
            return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data'));
        }
    }

    public function update(Request $request, $id) {
        if ($request->ajax()) {
            return $this->surat->updateLetter($request, \Crypt::decryptString($id));
        }
    }

    public function destroy($id)
    {
        //
    }

    public function dataTables()
    {
        return $this->surat->dataTables();
    }

    public function previewSurat($id)
    {   
        $suratId = \Crypt::decryptString($id);
        $data = SuratPermohonan::select(
            'surat_permohonan.surat_permohonan_id',
            'surat_permohonan.anggota_keluarga_id',
            'surat_permohonan.nama_lengkap',
            'surat_permohonan.no_surat',
            'surat_permohonan.lampiran',
            'surat_permohonan.hal',
            'surat_permohonan.tempat_lahir',
            'surat_permohonan.tgl_lahir',
            'surat_permohonan.bangsa',
            'agama.nama_agama',
            'status_pernikahan.nama_status_pernikahan',
            'surat_permohonan.pekerjaan',
            'surat_permohonan.no_kk',
            'surat_permohonan.no_ktp',
            'surat_permohonan.alamat',
            'surat_permohonan.tgl_permohonan',
            'surat_permohonan.tgl_approve_rt',
            'surat_permohonan.tgl_approve_rw',
            'surat_permohonan.validasi',
            'cap_rt.cap_rt',
            'cap_rw.cap_rw',
            'tanda_tangan_rt.tanda_tangan_rt',
            'tanda_tangan_rw.tanda_tangan_rw',
            'anggota_keluarga.rt_id',
            'anggota_keluarga.rw_id',
            'anggota_keluarga.kelurahan_id',
            'anggota_keluarga.subdistrict_id',
            'anggota_keluarga.city_id',
            'anggota_keluarga.province_id',
            'rt.rt',
            'rw.rw',
            'kelurahan.nama as kelurahan',
            'kelurahan.alamat as alamat_kelurahan',
            'kelurahan.kode_pos'
        )
            ->join('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'surat_permohonan.anggota_keluarga_id')
            ->join('rt', 'rt.rt_id', 'anggota_keluarga.rt_id')
            ->join('rw', 'rw.rw_id', 'anggota_keluarga.rw_id')
            ->join('kelurahan', 'kelurahan.kelurahan_id', 'anggota_keluarga.kelurahan_id')
            ->join('agama', 'agama.agama_id', 'surat_permohonan.agama_id')
            ->join('status_pernikahan', 'status_pernikahan.status_pernikahan_id', 'surat_permohonan.status_pernikahan_id')
            ->leftJoin('cap_rt', 'cap_rt.rt_id', 'rt.rt_id')
            ->leftJoin('tanda_tangan_rt', 'tanda_tangan_rt.rt_id', 'rt.rt_id')
            ->leftJoin('cap_rw', 'cap_rw.rw_id', 'rw.rw_id')
            ->leftJoin('tanda_tangan_rw', 'tanda_tangan_rw.rw_id', 'rw.rw_id')
            ->findOrFail($suratId);

        $lampiranSurat = SuratPermohonanLampiran::where('surat_permohonan_id', $data->surat_permohonan_id)
        ->join('lampiran', 'lampiran.lampiran_id', 'surat_permohonan_lampiran.lampiran_id')
        ->count('upload_lampiran');

        $anggotaGetAlamat = $this->anggota->getAlamat($data->anggota_keluarga_id);

        $logo_kabupaten = \DB::table('logo_kabupaten')
            ->select('logo', 'city_id')
            ->where('city_id', $data->city_id)
            ->first();

        $ketuaRT = \DB::table('keluarga')
            ->select('anggota_keluarga.nama')
            ->join('anggota_keluarga', 'anggota_keluarga.keluarga_id', 'keluarga.keluarga_id')
            ->where('keluarga.rt_id', $data->rt_id)
            ->where('anggota_keluarga.is_rt', true)
            ->first();

        $ketuaRW = \DB::table('keluarga')
            ->select('anggota_keluarga.nama')
            ->join('anggota_keluarga', 'anggota_keluarga.keluarga_id', 'keluarga.keluarga_id')
            ->where('keluarga.rw_id', $data->rw_id)
            ->where('anggota_keluarga.is_rw', true)
            ->first();

        $logo_kabupaten = \DB::table('logo_kabupaten')
            ->select('logo', 'city_id')
            ->where('city_id', $data->city_id)
            ->first();

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data', 'ketuaRT', 'ketuaRW', 'logo_kabupaten', 'anggotaGetAlamat', 'lampiranSurat'));
    }

    public function previewSuratKelurahan(Request $request, $letterID) {
        $data = SuratPermohonan::findOrFail(\Crypt::decryptString($letterID));
        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data'));
    }
}