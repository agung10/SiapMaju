<?php

namespace App\Http\Controllers\UMKM;

use App\Http\Controllers\Controller;
use App\Repositories\Master\AnggotaKeluargaRepository;
use App\Repositories\UMKM\{UmkmRepository, MedsosRepository};
use Illuminate\Http\Request;

class UmkmController extends Controller
{
    public function __construct(UmkmRepository $_UmkmRepository, AnggotaKeluargaRepository $_AnggotaKeluargaRepository, MedsosRepository $_MedsosRepository)
    {
        $route_name = explode('.',\Route::currentRouteName());
        $this->route1 = ((isset($route_name[0])) ? $route_name[0] : (''));
        $this->route2 = ((isset($route_name[1])) ? $route_name[1] : (''));
        $this->route3 = ((isset($route_name[2])) ? $route_name[2] : (''));

        $this->umkm = $_UmkmRepository;
        $this->anggotaKeluarga = $_AnggotaKeluargaRepository;
        $this->medsos  = $_MedsosRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->route1.'.'.$this->route2.'.'.$this->route3);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $anggotaKeluarga = $this->anggotaKeluarga->selectOwner();
        $medsos  = $this->medsos->selectMedsos();
        
        $resultOwner = '<option></option>';
        foreach ($anggotaKeluarga as $anggota_keluarga_id => $nama) {
            $resultOwner .= '<option value="'.$anggota_keluarga_id.'">'.$nama.'</option>';
        }

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('resultOwner', 'medsos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->umkm->storeUmkm($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = \Crypt::decryptString($id);
        
        $data = $this->umkm->showUmkm($id); 
        $umkmMedsos = $this->umkm->umkmMedsos($id);

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('data','umkmMedsos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = \Crypt::decryptString($id);

        $data = $this->umkm->showUmkm($id);
        $anggotaKeluarga = $this->anggotaKeluarga->selectOwner();
        $medsos  = $this->medsos->selectMedsos();
        $umkmMedsos = $this->umkm->umkmMedsos($id);
        
        $resultOwner = '<option disabled selected></option>';
        foreach ($anggotaKeluarga as $anggota_keluarga_id => $nama) {
            $resultOwner .= '<option value="'.$anggota_keluarga_id.'"'.((!empty($anggota_keluarga_id)) ? ((!empty($anggota_keluarga_id == $data->anggota_keluarga_id)) ? ('selected') : ('')) : ('')).'>'.$nama.'</option>';
        }

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('data', 'anggotaKeluarga', 'medsos', 'resultOwner', 'umkmMedsos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = \Crypt::decryptString($id);
        
        return $this->umkm->updateUmkm($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = \Crypt::decryptString($id);
        
        return $this->umkm->deleteUmkm($id);
    }

    public function dataTables(Request $request)
    {
        return $this->umkm->dataTables($request);
    }
}
