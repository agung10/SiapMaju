<?php

namespace App\Http\Controllers\Master\Keluarga;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\helper;
use App\Http\Requests\Master\AddAnggotaKeluargaRequest;
use App\Models\Master\Agama;
use App\Repositories\Master\MasterRepository;
use App\Models\Master\Keluarga\HubKeluarga;
use App\Models\Master\Keluarga\Keluarga;
use App\Models\Master\Keluarga\AnggotaKeluarga;
use App\Models\Master\Keluarga\MutasiWarga;
use App\Models\Master\Kelurahan;
use App\Models\Master\RT;
use App\Models\Master\RW;
use App\Repositories\Master\AnggotaKeluargaAlamatRepository;
use App\Repositories\Master\AnggotaKeluargaRepository;
use App\Repositories\RajaOngkir\RajaOngkirRepository;

class AnggotaKeluargaController extends Controller
{
    public function __construct(MasterRepository $_Master, AnggotaKeluargaRepository $_AnggotaKeluargaRepository, RajaOngkirRepository $rajaOngkir, AnggotaKeluargaAlamatRepository $_AnggotaKeluargaAlamatRepo)
    {
        $route_name  = explode('.',\Route::currentRouteName());
        $this->route1 = ((isset($route_name[0])) ? $route_name[0] : (''));
        $this->route2 = ((isset($route_name[1])) ? $route_name[1] : (''));
        $this->route3 = ((isset($route_name[2])) ? $route_name[2] : (''));

        $this->master = $_Master;
        $this->anggota = $_AnggotaKeluargaRepository;
        $this->rajaOngkir = $rajaOngkir;
        $this->anggotaKeluargaAlamat = $_AnggotaKeluargaAlamatRepo;
    }

    public function index()
    {   
        $user = \DB::table('users')
                    ->select('anggota_keluarga.hub_keluarga_id')
                    ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                    ->where('users.user_id',\Auth::user()->user_id)
                    ->first();
        
        $isKepalaKeluarga = $this->anggota->checkKepalaKeluarga();
        $isAdmin = $user->hub_keluarga_id === null; 

        $createBtnPermission = ($isKepalaKeluarga || $isAdmin) ? true : false;

        $provinces = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');

        return view($this->route1.'.'.'Keluarga'.'.'.$this->route2.'.'.$this->route3,compact('createBtnPermission', 'isAdmin', 'provinces'));
    }

    public function create()
    {
        $keluarga = Keluarga::select('keluarga.keluarga_id', 
                                    'blok.nama_blok',
                                    'anggota_keluarga.nama',
                                    'anggota_keluarga.province_id',
                                    'anggota_keluarga.city_id',
                                    'anggota_keluarga.subdistrict_id',
                                    'anggota_keluarga.kelurahan_id',
                                    'anggota_keluarga.rw_id',
                                    'anggota_keluarga.rt_id',
                            )
                            ->join('blok', 'blok.blok_id', 'keluarga.blok_id')
                            ->join('anggota_keluarga','anggota_keluarga.keluarga_id','keluarga.keluarga_id')
                            ->orWhere(function($query){
                                if(\Auth::user()->anggota_keluarga_id){
                                    $query->where('anggota_keluarga_id',\Auth::user()->anggota_keluarga_id);
                                } else{
                                    $query->where('anggota_keluarga.hub_keluarga_id', 1);
                                }
                            })
                            ->orderBy('nama_blok', 'ASC')
                            ->get();

        $hubungan = HubKeluarga::select( 'hub_keluarga.hub_keluarga_id',
                                        'hub_keluarga.hubungan_kel')
                    ->get();

                
        $resultKeluarga = '<option></option>';
        foreach ($keluarga as $myKeluarga) {
            $resultKeluarga .= '<option data-keluarga_id="'. $myKeluarga->keluarga_id .'" value="'.$myKeluarga->keluarga_id.'">Blok '.$myKeluarga->nama_blok.' /  '.$myKeluarga->nama.'</option>';
        }

        $resultHub = '<option></option>';
        foreach ($hubungan as $myHub) {
            $resultHub .= '<option value="'.$myHub->hub_keluarga_id.'">'.$myHub->hubungan_kel.'</option>';
        }

        $provinces = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');

        $rt = RT::select(
            'rt.rt',
            'rt.rt_id',
            'rw.rw',
            'kelurahan.nama'
        )
            ->join('rw', 'rw.rw_id', 'rt.rw_id')
            ->join('kelurahan', 'kelurahan.kelurahan_id', 'rw.kelurahan_id')
            ->orderBy('rt', 'ASC')
            ->get();
        $resultRT = '<option></option>';
        foreach ($rt as $res) {
            $resultRT .= '<option value="' . $res->rt_id . '">' . $res->rt . ' - ' . $res->rw . ' - ' . $res->nama . '</option>';
        }

        $rw = RW::select(
            'rw.rw',
            'rw.rw_id',
            'kelurahan.nama'
        )
            ->join('kelurahan', 'kelurahan.kelurahan_id', 'rw.kelurahan_id')
            ->orderBy('rw', 'ASC')
            ->get();
        $resultRW = '<option></option>';
        foreach ($rw as $res) {
            $resultRW .= '<option value="' . $res->rw_id . '">' . $res->rw . ' - ' . $res->nama . '</option>';
        }

        $kelurahan = Kelurahan::select(
            'kelurahan.nama',
            'kelurahan.kelurahan_id'
        )
            ->orderBy('nama', 'ASC')
            ->get();
        $resultKelurahan = '<option></option>';
        foreach ($kelurahan as $res) {
            $resultKelurahan .= '<option value="' . $res->kelurahan_id . '">' . $res->nama . '</option>';
        }

        $agama = Agama::orderBy('agama_id', 'ASC')->pluck('nama_agama', 'agama_id');

        return view($this->route1.'.'.'Keluarga'.'.'.$this->route2.'.'.$this->route3, compact('resultKeluarga', 'resultHub', 'provinces', 'resultRT', 'resultRW', 'resultKelurahan', 'agama'));
    }

    public function store(AddAnggotaKeluargaRequest $request)
    {   
        return $this->anggota->store($request);
    }

    public function show($id)
    {   
        $id = \Crypt::decryptString($id);

        $data = AnggotaKeluarga::select('anggota_keluarga.anggota_keluarga_id',
                                        'anggota_keluarga.nama',
                                        'anggota_keluarga.alamat',
                                        'anggota_keluarga.email',
                                        'anggota_keluarga.mobile',
                                        'anggota_keluarga.password',
                                        'anggota_keluarga.tgl_lahir',
                                        'anggota_keluarga.nama_umkm',
                                        'anggota_keluarga.jenis_kelamin',
                                        'anggota_keluarga.is_active',
                                        'anggota_keluarga.rt_id',
                                        'anggota_keluarga.rw_id',
                                        'anggota_keluarga.kelurahan_id',
                                        'anggota_keluarga.agama_id',
                                        'blok.nama_blok as nama_blok', 
                                        'hub_keluarga.hubungan_kel as hubungan_kel',
                                        'keluarga.keluarga_id')
            ->join('keluarga', 'keluarga.keluarga_id', 'anggota_keluarga.keluarga_id')
            ->join('blok', 'blok.blok_id', 'keluarga.blok_id')
            ->join('hub_keluarga', 'hub_keluarga.hub_keluarga_id', 'anggota_keluarga.hub_keluarga_id')
            ->findOrFail($id);
        
        $kepalaKeluarga = Keluarga::select('keluarga.keluarga_id', 'anggota_keluarga.nama')
                            ->join('anggota_keluarga','anggota_keluarga.keluarga_id','keluarga.keluarga_id')
                            ->where('anggota_keluarga.hub_keluarga_id', 1)
                            ->where('keluarga.keluarga_id', $data->keluarga_id)
                            ->first();
                                
        $anggota_keluarga = AnggotaKeluarga::select('hub_keluarga.hubungan_kel as hubungan_kel','anggota_keluarga.nama', 'anggota_keluarga_id', 'jenis_kelamin')
                                            ->join('hub_keluarga','hub_keluarga.hub_keluarga_id','anggota_keluarga.hub_keluarga_id')
                                            ->where('anggota_keluarga.keluarga_id',$data->keluarga_id)
                                            ->where('anggota_keluarga.is_active', true)
                                            ->orderBy('anggota_keluarga.created_at', 'asc')
                                            ->get();

        $anggotaGetAlamat = $this->anggota->getAlamat($id);

        $dat =  collect(
            \DB::select(
                "SELECT rtTbl.rt_id, rtTbl.rt, rwTbl.rw_id, rwTbl.rw, kelurahanTbl.kelurahan_id, kelurahanTbl.nama 
                FROM anggota_keluarga as anggota_keluargaTbl 
                LEFT OUTER JOIN rt as rtTbl ON rtTbl.rt_id = anggota_keluargaTbl.rt_id 
                LEFT OUTER JOIN rw as rwTbl ON rwTbl.rw_id = anggota_keluargaTbl.rw_id 
                LEFT OUTER JOIN kelurahan as kelurahanTbl ON kelurahanTbl.kelurahan_id = anggota_keluargaTbl.kelurahan_id 
                WHERE anggota_keluargaTbl.anggota_keluarga_id = '$id'"
            )
        )->first();

        $mutasi = \DB::table('mutasi_warga')->where('anggota_keluarga_id', $data->anggota_keluarga_id)->latest("mutasi_warga_id")->first();
                
        return view($this->route1.'.'.'Keluarga'.'.'.$this->route2.'.'.$this->route3, compact('data','anggota_keluarga', 'anggotaGetAlamat', 'dat', 'kepalaKeluarga', 'mutasi'));
    }

    public function edit($id)
    {   
        $id = \Crypt::decryptString($id);

         $data = AnggotaKeluarga::select('anggota_keluarga.anggota_keluarga_id',
                                        'anggota_keluarga.nama',
                                        'anggota_keluarga.alamat',
                                        'anggota_keluarga.email',
                                        'anggota_keluarga.mobile',
                                        'anggota_keluarga.password',
                                        'anggota_keluarga.keluarga_id',
                                        'anggota_keluarga.hub_keluarga_id',
                                        'anggota_keluarga.jenis_kelamin',
                                        'anggota_keluarga.tgl_lahir',
                                        'anggota_keluarga.nama_umkm',
                                        'anggota_keluarga.rt_id',
                                        'anggota_keluarga.rw_id',
                                        'anggota_keluarga.kelurahan_id',
                                        'anggota_keluarga.agama_id',
                                        'blok.nama_blok as nama_blok', 
                                        'hub_keluarga.hubungan_kel as hubungan_kel')
            ->join('keluarga', 'keluarga.keluarga_id', 'anggota_keluarga.keluarga_id')
            ->join('blok', 'blok.blok_id', 'keluarga.blok_id')
            ->join('hub_keluarga', 'hub_keluarga.hub_keluarga_id', 'anggota_keluarga.hub_keluarga_id')
            ->findOrFail($id);
  
         $keluarga = Keluarga::select('keluarga.keluarga_id', 
                                    'blok.nama_blok',
                                    'anggota_keluarga.nama')
                    ->join('blok', 'blok.blok_id', 'keluarga.blok_id')
                    ->join('anggota_keluarga','anggota_keluarga.keluarga_id','keluarga.keluarga_id')
                    ->where('anggota_keluarga.hub_keluarga_id', 1)
                    ->orWhere(function($query){
                        if(\Auth::user()->anggota_keluarga_id){
                            $query->where('anggota_keluarga_id',\Auth::user()->anggota_keluarga_id);
                        }
                    })
                    ->get();
        
        $hubungan = HubKeluarga::select( 'hub_keluarga.hub_keluarga_id',
                                        'hub_keluarga.hubungan_kel')
                    ->get();
        
        $anggotaGetAlamat = $this->anggota->getAlamat($id);
        $provinces             = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');
        $cities                = $anggotaGetAlamat['province_id'] != ''
                                ? $this->rajaOngkir->getCitiesByProvince($anggotaGetAlamat['province_id'])
                                ->map(function ($value) {
                                    $value->city_name = $value->type.' '.$value->city_name;

                                    return $value;
                                })
                                ->pluck('city_name', 'city_id') : [];
        $subdistricts           = $anggotaGetAlamat['city_id'] != ''
                                ? $this->rajaOngkir->getSubdistrictsByCity($anggotaGetAlamat['city_id'])
                                ->pluck('subdistrict_name', 'subdistrict_id') : [];

        $resultKeluarga = '<option disabled selected>Pilih Keluarga</option>';
        foreach ($keluarga as $myKeluarga) {
            $resultKeluarga .= '<option value="'.$myKeluarga->keluarga_id.'"'.((!empty($myKeluarga->keluarga_id)) ? ((!empty($myKeluarga->keluarga_id == $data->keluarga_id)) ? ('selected') : ('')) : ('')).'>'.'Blok '.$myKeluarga->nama_blok.' / '.$myKeluarga->nama.'</option>';
        }
        
        $resultHub = '<option disabled selected>Pilih Hubungan Keluarga</option>';
        foreach ($hubungan as $myHub) {
            $resultHub .= '<option value="'.$myHub->hub_keluarga_id.'"'.((!empty($myHub->hub_keluarga_id)) ? ((!empty($myHub->hub_keluarga_id == $data->hub_keluarga_id)) ? ('selected') : ('')) : ('')).'>'.$myHub->hubungan_kel.'</option>';
        }

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

        $rt = RT::select(
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

        $rw = RW::select(
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

        $kelurahan = Kelurahan::select(
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


        $agama = Agama::orderBy('agama_id', 'ASC')->pluck('nama_agama', 'agama_id');
        $resultAgama = '<option disabled selected>Pilih Agama</option>';
        foreach ($agama as $agama_id => $nama_agama) {  
            $resultAgama .= '<option value="'.$agama_id.'"'.((!empty($agama_id)) ? ((!empty($agama_id == $data->agama->agama_id)) ? ('selected') : ('')) : ('')).'>'.$nama_agama.'</option>';
        }

        return view($this->route1.'.'.'Keluarga'.'.'.$this->route2.'.'.$this->route3, compact('data', 'resultKeluarga', 'resultHub', 'anggotaGetAlamat', 'resultProvince', 'resultCity', 'resultSubdistrict', 'resultRT', 'resultRW', 'resultKelurahan', 'resultAgama'));
    }

    public function update(AddAnggotaKeluargaRequest $request, $id)
    {   
        $id = \Crypt::decryptString($id);
        return $this->anggota->update($request,$id);
    }

    public function getDataKeluarga($id)
    {
        $keluarga = Keluarga::findOrFail($id);

        return response()->json(['status' => 'success','result' => $keluarga]);
    }

    public function destroy($id)
    {
        return $this->anggota->destroy($id);
    }

    public function dataTables(Request $request)
    {
        return $this->anggota->dataTables($request);
    }
}