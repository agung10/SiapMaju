<?php

namespace App\Http\Controllers\Surat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Surat\SuratPermohonanRepository;
use App\Helpers\helper;
use App\Models\Surat\SuratPermohonan;
use App\Models\Master\Keluarga\AnggotaKeluarga;
use App\Models\Master\RT;
use App\Models\Surat\Lampiran;
use App\Models\Surat\SuratPermohonanLampiran;
use App\Repositories\Master\AnggotaKeluargaRepository;
use App\Repositories\RajaOngkir\RajaOngkirRepository;

class SuratPermohonanController extends Controller
{
    public function __construct(SuratPermohonanRepository $_SuratPermohonanRepository, RajaOngkirRepository $rajaOngkir, AnggotaKeluargaRepository $anggota)
    {
        $route_name = explode('.', \Route::currentRouteName());

        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

        $this->surat = $_SuratPermohonanRepository;
        $this->rajaOngkir = $rajaOngkir;
        $this->anggota = $anggota;
    }

    public function index()
    {
        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3);
    }

    public function create()
    {
        $isKepalaKeluarga = \helper::checkUserRole('kepalaKeluarga');
        $isWarga = \helper::checkUserRole('warga');
        $selectNamaWarga = $this->surat->selectNamaWarga();

        $selectNamaAnggota = $this->surat->selectNamaAnggota();
        $jenisSurat = helper::select('jenis_surat', 'jenis_permohonan', false, 'jenis_permohonan', 'ASC');
        $rt = helper::select('rt', 'rt');
        $rw = helper::select('rw', 'rw');
        $kelurahan = helper::select('kelurahan', 'nama');
        $provinces = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');
        $agama = helper::select('agama', 'nama_agama');
        $pernikahan = helper::select('status_pernikahan', 'nama_status_pernikahan');

        $lampiran = Lampiran::where('status', true)->get();

        if ($isKepalaKeluarga || $isWarga) {
            $data = \DB::table('users')
            ->select('users.anggota_keluarga_id','keluarga.rt_id','anggota_keluarga.keluarga_id', 'anggota_keluarga.alamat', 'anggota_keluarga.rt_id', 'anggota_keluarga.rw_id', 'anggota_keluarga.kelurahan_id', 'anggota_keluarga.subdistrict_id', 'anggota_keluarga.city_id', 'anggota_keluarga.province_id', 'anggota_keluarga.alamat', 'anggota_keluarga.tgl_lahir')
            ->join('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
            ->join('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
            ->where('users.anggota_keluarga_id',\Auth::user()->anggota_keluarga_id)
            ->first();

            $data_tgl_lahir = $data->tgl_lahir;
            $data_alamat = $data->alamat;
            $data_rt = $data->rt_id;
            $data_rw = $data->rw_id;
            $data_kelurahan = $data->kelurahan_id;
            $data_subdistrict = $data->subdistrict_id;
            $data_city = $data->city_id;
            $data_province = $data->province_id;

            $anggotaGetAlamat = $this->anggota->getAlamat($data->anggota_keluarga_id);
            $cities = $anggotaGetAlamat['province_id'] != ''
                    ? $this->rajaOngkir->getCitiesByProvince($anggotaGetAlamat['province_id'])
                    ->map(function ($value) {
                        $value->city_name = $value->type.' '.$value->city_name;

                        return $value;
                    })
                    ->pluck('city_name', 'city_id') : [];
            $subdistricts = $anggotaGetAlamat['city_id'] != ''
                            ? $this->rajaOngkir->getSubdistrictsByCity($anggotaGetAlamat['city_id'])
                            ->pluck('subdistrict_name', 'subdistrict_id') : [];

            $resultProvince = '<option disabled selected>Pilih Provinsi</option>';
            foreach ($provinces as $province_id => $province) {
                $resultProvince .= '<option value="'.$province_id.'"'.((!empty($province_id)) ? ((!empty($province_id == $anggotaGetAlamat['province_id'])) ? ('selected') : ('')) : ('')).'>'.$province.'</option>';
            }

            $resultCity = '<option disabled selected>Pilih Kota/Kabupaten</option>';
            foreach ($cities as $city_id => $city_name) {
                $resultCity .= '<option value="'.$city_id.'"'.((!empty($city_id)) ? ((!empty($city_id == $anggotaGetAlamat['city_id'])) ? ('selected') : ('')) : ('')).'>'.$city_name.'</option>';
            }

            $resultSubdistrict = '<option disabled selected>Pilih Kecamatan</option>';
            foreach ($subdistricts as $subdistrict_id => $subdistrict_name) {
                $resultSubdistrict .= '<option value="'.$subdistrict_id.'"'.((!empty($subdistrict_id)) ? ((!empty($subdistrict_id == $anggotaGetAlamat['subdistrict_id'])) ? ('selected') : ('')) : ('')).'>'.$subdistrict_name.'</option>';
            }

            $rt = \DB::table('rt')->select(
                'rt.rt',
                'rt.rt_id',
                'rt.rw_id',
            )
                ->where('rt.rw_id', $data->rw_id)
                ->orderBy('rt', 'ASC')
                ->get();
            $resultRT = '<option disabled selected></option>';
            foreach ($rt as $res) {
                $resultRT .= '<option value="' . $res->rt_id . '"' . ((!empty($res->rt_id)) ? ((!empty($res->rt_id == $data->rt_id)) ? ('selected') : ('')) : ('')) . '>' . $res->rt . '</option>';
            }

            $rw = \DB::table('rw')->select(
                'rw.rw',
                'rw.rw_id',
                'rw.kelurahan_id',
            )
                ->where('rw.kelurahan_id', $data->kelurahan_id)
                ->orderBy('rw', 'ASC')
                ->get();
            $resultRW = '<option disabled selected></option>';
            foreach ($rw as $res) {
                $resultRW .= '<option value="' . $res->rw_id . '"' . ((!empty($res->rw_id)) ? ((!empty($res->rw_id == $data->rw_id)) ? ('selected') : ('')) : ('')) . '>' . $res->rw . '</option>';
            }

            $kelurahan = \DB::table('kelurahan')->select(
                'kelurahan.nama',
                'kelurahan.kelurahan_id'
            )
                ->where('subdistrict_id', $anggotaGetAlamat['subdistrict_id'])
                ->orderBy('nama', 'ASC')
                ->get();
            $resultKelurahan = '<option disabled selected></option>';
            foreach ($kelurahan as $res) {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '"' . ((!empty($res->kelurahan_id)) ? ((!empty($res->kelurahan_id == $data->kelurahan_id)) ? ('selected') : ('')) : ('')) . '>' . $res->nama . '</option>';
            }

            return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data', 'selectNamaWarga', 'jenisSurat', 'rt', 'rw', 'provinces', 'kelurahan', 'agama', 'pernikahan', 'selectNamaAnggota', 'resultProvince', 'resultCity', 'resultSubdistrict', 'resultRT', 'resultRW', 'resultKelurahan', 'isKepalaKeluarga', 'isWarga', 'data_alamat', 'data_tgl_lahir', 'data_rt', 'data_rw', 'data_kelurahan', 'data_subdistrict', 'data_city', 'data_province', 'lampiran'));
        }

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('selectNamaWarga', 'jenisSurat', 'rt', 'rw', 'provinces', 'kelurahan', 'agama', 'pernikahan', 'isKepalaKeluarga', 'isWarga', 'lampiran'));
    }

    public function store(Request $request)
    {
        return $this->surat->store($request);
    }

    public function getDataWarga($id)
    {
        return $this->surat->getDataWarga($id);
    }

    public function show($id)
    {
        $suratId = \Crypt::decryptString($id);
        $data = SuratPermohonan::findOrFail($suratId);
        
        $lampiranSurat = SuratPermohonanLampiran::where('surat_permohonan_id', $data->surat_permohonan_id)
            ->join('lampiran', 'lampiran.lampiran_id', 'surat_permohonan_lampiran.lampiran_id')
            ->get();

        $selectNamaWarga = $this->surat->selectNamaWarga($data->anggota_keluarga_id);

        $jenisSurat = helper::select('jenis_surat', 'jenis_permohonan', $data->jenis_surat_id);
        $agama = helper::select('agama', 'nama_agama', $data->agama_id);
        $pernikahan = helper::select('status_pernikahan', 'nama_status_pernikahan', $data->status_pernikahan_id);

        $anggota = \DB::table('anggota_keluarga')->where('anggota_keluarga_id', \Auth::user()->anggota_keluarga_id)->first();

        $anggotaGetAlamat = $this->anggota->getAlamat($data->anggota_keluarga_id);

        $dataAnggotaKel = \DB::table('anggota_keluarga')->select('rt.rt_id', 'rt.rt', 'rw.rw_id', 'rw.rw', 'kelurahan.kelurahan_id', 'kelurahan.nama as kelurahan')
            ->leftJoin('rt', 'rt.rt_id', 'anggota_keluarga.rt_id')
            ->leftJoin('rw', 'rw.rw_id', 'anggota_keluarga.rw_id')
            ->leftJoin('kelurahan', 'kelurahan.kelurahan_id', 'anggota_keluarga.kelurahan_id')
            ->where('anggota_keluarga_id', $data->anggota_keluarga_id)
            ->first();

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('dataAnggotaKel', 'data', 'selectNamaWarga', 'jenisSurat', 'agama', 'pernikahan', 'anggota', 'anggotaGetAlamat', 'lampiranSurat'));
    }

    public function edit($id)
    {
        $suratId = \Crypt::decryptString($id);
        $data = SuratPermohonan::join('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','surat_permohonan.anggota_keluarga_id')->join('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')->findOrFail($suratId);
        
        $lampiranSurat = SuratPermohonanLampiran::where('surat_permohonan_id', $data->surat_permohonan_id)
            ->join('lampiran', 'lampiran.lampiran_id', 'surat_permohonan_lampiran.lampiran_id')
            ->get();
        
        $dataAnggotaKel = \DB::table('anggota_keluarga')->select('rt.rt_id', 'rt.rt', 'rw.rw_id', 'rw.rw', 'kelurahan.kelurahan_id', 'kelurahan.nama as kelurahan')
            ->leftJoin('rt', 'rt.rt_id', 'anggota_keluarga.rt_id')
            ->leftJoin('rw', 'rw.rw_id', 'anggota_keluarga.rw_id')
            ->leftJoin('kelurahan', 'kelurahan.kelurahan_id', 'anggota_keluarga.kelurahan_id')
            ->where('anggota_keluarga_id', $data->anggota_keluarga_id)
            ->first();
        
        $selectNamaWarga = $this->surat->selectNamaWarga($data->anggota_keluarga_id);
        $resultAnggota = $this->surat->selectNamaAnggota($data->anggota_keluarga_id);
        
        $jenisSurat = helper::select('jenis_surat', 'jenis_permohonan', $data->jenis_surat_id, 'jenis_permohonan', 'ASC');
        // $jSurat = \DB::table('jenis_surat')->select('jenis_surat_id', 'jenis_permohonan')->where('jenis_surat_id', $data->jenis_surat_id)->get();
        // $jenisSurat = '<option disabled selected></option>';
        // foreach ($jSurat as $res) {
        //     $jenisSurat .= '<option value="' . $res->jenis_surat_id . '"' . ((!empty($res->jenis_surat_id)) ? ((!empty($res->jenis_surat_id == $data->jenis_surat_id)) ? ('selected') : ('')) : ('')) . '>' . $res->jenis_permohonan . '</option>';
        // }

        $agama = helper::select('agama', 'nama_agama', $data->agama_id);
        $pernikahan = helper::select('status_pernikahan', 'nama_status_pernikahan', $data->status_pernikahan_id);

        $anggotaGetAlamat = $this->anggota->getAlamat($data->anggota_keluarga_id);
            
        $provinces       = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');
        $cities          = $anggotaGetAlamat['province_id'] != ''
            ? $this->rajaOngkir->getCitiesByProvince($anggotaGetAlamat['province_id'])
            ->map(function ($value) {
                $value->city_name = $value->type . ' ' . $value->city_name;

                return $value;
            })
            ->pluck('city_name', 'city_id') : [];
        $subdistricts = $anggotaGetAlamat['city_id'] != ''
            ? $this->rajaOngkir->getSubdistrictsByCity($anggotaGetAlamat['city_id'])
            ->pluck('subdistrict_name', 'subdistrict_id') : [];

        $resultProvince = '<option disabled selected>Pilih Provinsi</option>';
        foreach ($provinces as $province_id => $province) {
            $resultProvince .= '<option value="' . $province_id . '"' . ((!empty($province_id)) ? ((!empty($province_id == $anggotaGetAlamat['province_id'])) ? ('selected') : ('')) : ('')) . '>' . $province . '</option>';
        }

        $resultCity = '<option disabled selected>Pilih Kota/Kabupaten</option>';
        foreach ($cities as $city_id => $city_name) {
            $resultCity .= '<option value="' . $city_id . '"' . ((!empty($city_id)) ? ((!empty($city_id == $anggotaGetAlamat['city_id'])) ? ('selected') : ('')) : ('')) . '>' . $city_name . '</option>';
        }

        $resultSubdistrict = '<option disabled selected>Pilih Kecamatan</option>';
        foreach ($subdistricts as $subdistrict_id => $subdistrict_name) {
            $resultSubdistrict .= '<option value="' . $subdistrict_id . '"' . ((!empty($subdistrict_id)) ? ((!empty($subdistrict_id == $anggotaGetAlamat['subdistrict_id'])) ? ('selected') : ('')) : ('')) . '>' . $subdistrict_name . '</option>';
        }

        $rtData = \DB::table('rt')->select(
                'rt.rt',
                'rt.rt_id',
                'rt.rw_id',
            )
                ->where('rt.rw_id', $dataAnggotaKel->rw_id)
                ->orderBy('rt', 'ASC')
                ->get();
        $rt = '<option disabled selected></option>';
        foreach ($rtData as $res) {
            $rt .= '<option value="' . $res->rt_id . '"' . ((!empty($res->rt_id)) ? ((!empty($res->rt_id == $dataAnggotaKel->rt_id)) ? ('selected') : ('')) : ('')) . '>' . $res->rt . '</option>';
        }

        $rwData = \DB::table('rw')->select(
            'rw.rw',
            'rw.rw_id',
            'rw.kelurahan_id',
        )
        ->where('rw.kelurahan_id', $dataAnggotaKel->kelurahan_id)
        ->orderBy('rw', 'ASC')
        ->get();
        
        $rw = '<option disabled selected></option>';
        foreach ($rwData as $res) {
            $rw .= '<option value="' . $res->rw_id . '"' . ((!empty($res->rw_id)) ? ((!empty($res->rw_id == $dataAnggotaKel->rw_id)) ? ('selected') : ('')) : ('')) . '>' . $res->rw . '</option>';
        }

        $kelurahanData = \DB::table('kelurahan')->select(
            'kelurahan.nama',
            'kelurahan.kelurahan_id'
        )
            ->where('subdistrict_id', $anggotaGetAlamat['subdistrict_id'])
            ->orderBy('nama', 'ASC')
            ->get();

        $kelurahan = '<option disabled selected></option>';
        foreach ($kelurahanData as $res) {
            $kelurahan .= '<option value="' . $res->kelurahan_id . '"' . ((!empty($res->kelurahan_id)) ? ((!empty($res->kelurahan_id == $dataAnggotaKel->kelurahan_id)) ? ('selected') : ('')) : ('')) . '>' . $res->nama . '</option>';
        }

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data', 'selectNamaWarga',  'jenisSurat', 'agama', 'pernikahan', 'resultProvince', 'resultCity', 'resultSubdistrict', 'rt', 'rw', 'kelurahan', 'anggotaGetAlamat', 'resultAnggota', 'lampiranSurat'));
    }

    public function update(Request $request, $id)
    {
        return $this->surat->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->surat->destroy($id);
    }

    public function dataTables()
    {
        return $this->surat->dataTables();
    }

    public function ApprovalDraft(Request $request, $id)
    {
        $data = SuratPermohonan::findOrFail($id);

        $input['approve_draft'] = true;

        try {
            $data->update($input);

            \DB::commit();
            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function ApprovalRT(Request $request, $id)
    {
        $data = SuratPermohonan::leftJoin('rt', 'rt.rt_id', 'surat_permohonan.rt_id')
            ->leftJoin('rw', 'rw.rw_id', 'surat_permohonan.rw_id')
            ->join('jenis_surat', 'jenis_surat.jenis_surat_id', 'surat_permohonan.jenis_surat_id')
            ->join('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','surat_permohonan.anggota_keluarga_id')
            ->findOrFail($id);

        $dataRT = RT::where('rt_id', $data->rt_id)->first();

        // $petugasRT = \DB::table('anggota_keluarga')
        //     ->select('nama')
        //     ->where('anggota_keluarga_id', \Auth::user()->anggota_keluarga_id)
        //     ->first();

        $input['petugas_rt_id'] = \Auth::user()->anggota_keluarga_id;
        $input['tgl_approve_rt'] = date('Y-m-d');

        $noAkhirSurat = \DB::table('rt')->where('rt_id', $data->rt_id)->max('no_akhir_surat');

        $input['no_surat'] = sprintf("%03s", $noAkhirSurat + 1) . '/' . $data->kd_surat . '/' . str_replace(' ', '', $data->rt) . '-'. str_replace(' ', '', $data->rw) . '/' . ($noAkhirSurat + 1) . '/' . date('m') . '/' . date('Y');

        $url = 'https://dev2.kamarkerja.com:3333/message/text';
        $response = @get_headers($url);

        // if cant reach url
        if (!$response) return redirect()->back()->with('error', 'Maaf terjadi kesalahan'); 

        $whatsappKey = \DB::table('whatsapp_key')
            ->select('whatsapp_key')
            ->first()
            ->whatsapp_key ?? null;

        if (!$whatsappKey) return redirect()->back()->with('error','No Whatsapp belum disandingkan'); 

        $tgl_permohonan = date('d M Y', strtotime($data->tgl_permohonan));
        $ketuaRW = \DB::table('anggota_keluarga')
            ->select('anggota_keluarga.mobile')
            ->where('anggota_keluarga.rw_id', $data->rw_id)
            ->where('anggota_keluarga.is_rw', true)
            ->first();

        $mobileRW = '62' . substr($ketuaRW->mobile, 1);

        $whatsapp_msgRW = "[INFORMASI SIAPMAJU], Terdapat permohonan suket/surat keterangan [$data->jenis_permohonan] atas nama $data->nama pada tanggal $tgl_permohonan. Mohon untuk memeriksa pada aplikasi";

        $inputRT = [
            'rt' => $data->rt,
            'kelurahan_id' => $data->kelurahan_id,
            'rw_id' => $data->rw_id,
            'province_id' => $data->province_id,
            'city_id' => $data->city_id,
            'subdistrict_id' => $data->subdistrict_id,
            'no_akhir_surat' => $data->no_akhir_surat + 1,
            'updated_at' => $data->updated_at,
        ];

        try {
            if ($dataRT) {
                $dataRT->update($inputRT); 
            }

            $data->update($input);

            \Http::post("$url?key=$whatsappKey",[
                'id' => $mobileRW,
                'message' => $whatsapp_msgRW
            ]);

            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function ApprovalRW(Request $request, $id)
    {
        $data = SuratPermohonan::join('jenis_surat', 'jenis_surat.jenis_surat_id', 'surat_permohonan.jenis_surat_id')->join('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','surat_permohonan.anggota_keluarga_id')->findOrFail($id);
        $encryptSuratId = \Crypt::encryptString($data->surat_permohonan_id);
        $routeCheckSurat = route('Kelurahan.SuratMasuk.show', $encryptSuratId);

        $input['petugas_rw_id'] = \Auth::user()->anggota_keluarga_id;
        $input['tgl_approve_rw'] = date('Y-m-d');
        $input['validasi'] = $routeCheckSurat;

        $url = 'https://dev2.kamarkerja.com:3333/message/text';
        $response = @get_headers($url);

        // if cant reach url
        if (!$response) return redirect()->back()->with('error','Maaf terjadi kesalahan'); 

        $whatsappKey = \DB::table('whatsapp_key')
        ->select('whatsapp_key')
        ->first()
        ->whatsapp_key ?? null;

        if (!$whatsappKey) return redirect()->back()->with('error', 'No Whatsapp belum disandingkan'); 

        $mobile = '62' . substr($data->mobile, 1);
        
        $whatsapp_msg = "[INFORMASI SIAPMAJU], Surat keterangan [$data->jenis_permohonan] sudah disetujui oleh Ketua RT dan Ketua RW. Mohon untuk melihat status surat pada aplikasi";
        
        try {
            $data->update($input);

            \DB::commit();

            // Send message to wa warga
            \Http::post("$url?key=$whatsappKey",[
                'id' => $mobile,
                'message' => $whatsapp_msg
            ]);

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
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

        $ketuaRT = \DB::table('anggota_keluarga')
            ->select('anggota_keluarga.nama')
            ->where('anggota_keluarga.rt_id', $data->rt_id)
            ->where('anggota_keluarga.is_rt', true)
            ->first();

        $ketuaRW = \DB::table('anggota_keluarga')
            ->select('anggota_keluarga.nama')
            ->where('anggota_keluarga.rw_id', $data->rw_id)
            ->where('anggota_keluarga.is_rw', true)
            ->first();

        $logo_kabupaten = \DB::table('logo_kabupaten')
            ->select('logo', 'city_id')
            ->where('city_id', $data->city_id)
            ->first();

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data', 'ketuaRT', 'ketuaRW', 'logo_kabupaten', 'anggotaGetAlamat', 'lampiranSurat'));
    }

    public function getAlamat($id)
    {
        $id = \Crypt::decryptString($id);
        $suratPGetAlamat = \DB::table('surat_permohonan')->where('surat_permohonan_id', $id)->first();
        $alamat = [
            "subdistrict_id"   => "",
            "province_id"      => "",
            "province"         => "",
            "city_id"          => "",
            "city"             => "",
            "type"             => "",
            "subdistrict_name" => "",
            "alamat"        => ""
        ];

        if ($suratPGetAlamat) {
            if (empty($suratPGetAlamat->subdistrict_id)) {
                $alamat = json_decode($this->rajaOngkir->getSubdistrictDetailById(1579), true);
            } else {
                $alamat = json_decode($this->rajaOngkir->getSubdistrictDetailById($suratPGetAlamat->subdistrict_id), true);
            }
            $alamat['alamat'] = $suratPGetAlamat->alamat;
        }

        return $alamat;
    }

    public function getDataLampiran($id)
    {
        $lampiran = Lampiran::select('lampiran_id', 'jenis_surat_id', 'nama_lampiran', 'keterangan', 'kategori')->where('jenis_surat_id', $id)->where('status', 1)->orderBy('nama_lampiran', 'ASC')->get();

        return response()->json(['status' => 'success', 'data' => $lampiran]);
    }
}