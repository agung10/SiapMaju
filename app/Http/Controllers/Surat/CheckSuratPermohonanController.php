<?php

namespace App\Http\Controllers\Surat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Surat\SuratPermohonan;
use App\Repositories\RajaOngkir\RajaOngkirRepository;

class CheckSuratPermohonanController extends Controller
{
    public function __construct(RajaOngkirRepository $rajaOngkir)
    {
        $route_name = explode('.',\Route::currentRouteName());

        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

        $this->rajaOngkir = $rajaOngkir;
    }

    public function index()
    {
        //
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
        $id = \Crypt::decryptString($id);
        $isLurah = \helper::checkUserRole('kelurahan');
        $isAdmin = \helper::checkUserRole('admin');
        $authorize = $isLurah || $isAdmin;

        $surat = SuratPermohonan::select('surat_permohonan.no_surat',
                                         'surat_permohonan.anggota_keluarga_d',
                                         'surat_permohonan.nama_lengkap',
                                         'surat_permohonan.rt_id',
                                         'surat_permohonan.rw_id',
                                         'surat_permohonan.kelurahan_id',
                                         'surat_permohonan.subdistrict_id',
                                         'surat_permohonan.city_id',
                                         'surat_permohonan.province_id',
                                         'rt.rt',
                                         'rw.rw',
                                         'kelurahan.nama as kelurahan',
                                         'kelurahan.alamat as alamat_kelurahan',
                                         'kelurahan.kode_pos',
                                         'surat_permohonan.tgl_approve_rw',
                                         'surat_permohonan.surat_permohonan_id')
                                 ->join('rt','rt.rt_id','surat_permohonan.rt_id')
                                 ->join('rw','rw.rw_id','surat_permohonan.rw_id')
                                 ->join('kelurahan', 'kelurahan.kelurahan_id', 'surat_permohonan.kelurahan_id')
                                 ->findOrFail($id);
                                 
        $anggotaGetAlamat = $this->getAlamat($surat->anggota_keluarga_id);

        $logo_kabupaten = \DB::table('logo_kabupaten')
            ->select('logo', 'city_id')
            ->where('city_id', $surat->city_id)
            ->first();
    
        return view($this->route1.'.'.$this->route2.'.'.$this->route3,compact('surat','authorize', 'anggotaGetAlamat', 'logo_kabupaten'));
        
    }

    public function getAlamat($surat)
    {
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

        if ($surat) {
            if (empty($surat->subdistrict_id)) {
                $alamat = json_decode($this->rajaOngkir->getSubdistrictDetailById(1579), true);
            } else {
                $alamat = json_decode($this->rajaOngkir->getSubdistrictDetailById($surat->subdistrict_id), true);
            }
            $alamat['alamat'] = $surat->alamat_kelurahan;
        }

        return $alamat;
    }

    public function approveLurah($id)
    {
        $id = \Crypt::decryptString($id);

        $updateData = [
            'petugas_kelurahan_id' => \Auth::user()->user_id,
            'tgl_approve_kelurahan' => date('Y-m-d')
        ];

        SuratPermohonan::findOrFail($id)
                        ->update($updateData);

        return redirect()->route('Kelurahan.Surat.index');
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
