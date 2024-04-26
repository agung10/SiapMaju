<?php

namespace App\Http\Controllers\Surat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Surat\SuratMasukKeluarRwRepository;
use App\Helpers\helper;

class SuratMasukKeluarRwController extends Controller
{   
    public function __construct(SuratMasukKeluarRwRepository $_SuratMasukKeluarRwRepository)
    {
        $route_name = explode('.',\Route::currentRouteName());
        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

        $this->surat = $_SuratMasukKeluarRwRepository;
    }

    public function index()
    {
        return view($this->route1.'.'.$this->route2.'.'.$this->route3);
    }

    public function create()
    {
        $jenisSurat = helper::select('jenis_surat_rw','jenis_surat');
        $sifatSurat = helper::select('sifat_surat','sifat_surat');
        $asalTujuanSurat = helper::select('sumber_surat','asal_surat');
        $warga = helper::select('anggota_keluarga','nama');
        $suratBalasan = $this->surat->selectSuratBalasan();

        return view($this->route1.'.'.$this->route2.'.'.$this->route3,compact('jenisSurat','sifatSurat','asalTujuanSurat','warga','suratBalasan'));
    }

    public function store(Request $request)
    {
        return $this->surat->store($request);
    }

    public function show($id)
    {       
        $id = \Crypt::decryptString($id);

        $data = $this->surat->show($id);
        $jenisSurat = helper::select('jenis_surat_rw','jenis_surat',$data->jenis_surat_rw_id);
        $sifatSurat = helper::select('sifat_surat','sifat_surat',$data->sifat_surat_id);
        $asalSurat = helper::select('sumber_surat','asal_surat',$data->asal_surat);
        $tujuanSurat = helper::select('sumber_surat','asal_surat',$data->tujuan_surat);
        $warga = helper::select('anggota_keluarga','nama',$data->warga_id);
        $suratBalasan = $this->surat->selectSuratBalasan($data->surat_balasan_id);
    
        return view($this->route1.'.'.$this->route2.'.'.$this->route3,compact('data','jenisSurat','sifatSurat','asalSurat','tujuanSurat','warga','suratBalasan'));
    }

    public function edit($id)
    {
        $id = \Crypt::decryptString($id);

        $data = $this->surat->show($id);
        $jenisSurat = helper::select('jenis_surat_rw','jenis_surat',$data->jenis_surat_rw_id);
        $sifatSurat = helper::select('sifat_surat','sifat_surat',$data->sifat_surat_id);
        $asalSurat = helper::select('sumber_surat','asal_surat',$data->asal_surat);
        $tujuanSurat = helper::select('sumber_surat','asal_surat',$data->tujuan_surat);
        $warga = helper::select('anggota_keluarga','nama',$data->warga_id);
        $suratBalasan = $this->surat->selectSuratBalasan($data->surat_balasan_id);

        return view($this->route1.'.'.$this->route2.'.'.$this->route3,compact('data','jenisSurat','sifatSurat','asalSurat','tujuanSurat','warga','suratBalasan'));
    }

    public function update(Request $request, $id)
    {
        return $this->surat->update($request,$id);
    }

    public function destroy($id)
    {
        return $this->surat->destroy($id);
    }

    public function generateNoSuratKeluar()
    {
        return $this->surat->generateNoSuratKeluar();
    }

    public function dataTables()
    {
        return $this->surat->dataTables();
    }
}
