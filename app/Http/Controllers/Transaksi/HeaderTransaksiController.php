<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\helper;
use App\Repositories\HeaderTransaksiRepository; 

class HeaderTransaksiController extends Controller
{   
    public function __construct(HeaderTransaksiRepository $_HeaderTransaksiRepository)
    {
        $route_name = explode('.',\Route::currentRouteName());
        
        $this->route1 = ((isset($route_name[0])) ? $route_name[0] : (''));
        $this->route2 = ((isset($route_name[1])) ? $route_name[1] : (''));
        $this->route3 = ((isset($route_name[2])) ? $route_name[2] : (''));

        $this->transaksi =  $_HeaderTransaksiRepository;
    }

    public function index()
    {   
        return view($this->route1.'.'.$this->route2.'.'.$this->route3);
    }

    public function getKodeKegiatan(Request $request)
    {   
        return $this->transaksi->getKodeKegiatan($request);
    }

    public function getKepalaKeluarga(Request $request)
    {   
        return $this->transaksi->getKepalaKeluarga($request);
    }

    public function getJenisTransaksi(Request $request)
    {
        return $this->transaksi->getJenisTransaksi($request);
    }

    public function saveDetail(Request $request)
    {   
        return $this->transaksi->saveDetail($request);
    }

    public function create()
    {
        $selectTransaksi = $this->transaksi->selectTransaksi();
        $selectKatKegiatan = $this->transaksi->selectKatKegiatan();
        $selectKodeKatKegiatan = $this->transaksi->selectKodeKatKegiatan();
        $selectIDTransaksi = Helper::select('transaksi','transaksi_id');
        $selectKegiatan = Helper::select('kegiatan','nama_kegiatan');
        $selectKepalaKeluarga = $this->transaksi->selectKepalaKeluarga();

        return view($this->route1.'.'.$this->route2.'.'.$this->route3,compact('selectTransaksi','selectKatKegiatan','selectKodeKatKegiatan','selectIDTransaksi','selectKegiatan','selectKepalaKeluarga'));
    }

    public function store(Request $request)
    {   
        return $this->transaksi->store($request);
    }

    public function show($id)
    {   
        $id = \Crypt::decryptString($id);

        $data = $this->transaksi->show($id);
        $regPendaftaran = explode('/',$data->no_pendaftaran)[0];
        $noBukti= !empty($data->no_bukti) ? explode('/',$data->no_pendaftaran)[0] : '';

        $detailTransaksi = $this->transaksi->detailTransaksi($id);

        return view($this->route1.'.'.$this->route2.'.'.$this->route3,compact('data','regPendaftaran','detailTransaksi','noBukti'));
    }

    public function edit($id)
    {   
        $id = \Crypt::decryptString($id);

        $data = $this->transaksi->show($id);
        $selectTransaksi = $this->transaksi->selectTransaksi($data->transaksi_id);
        $selectKatKegiatan = $this->transaksi->selectKatKegiatan($data->kat_kegiatan_id);
        $selectKodeKatKegiatan = $this->transaksi->selectKodeKatKegiatan($data->kat_kegiatan_id);
        $selectIDTransaksi = Helper::select('transaksi','transaksi_id');
        $selectKegiatan = Helper::select('kegiatan','nama_kegiatan',$data->kegiatan_id);
        $selectKepalaKeluarga = $this->transaksi->selectKepalaKeluarga($data->anggota_keluarga_id);        
        $regPendaftaran = explode('/',$data->no_pendaftaran)[0];
        $detailTransaksi = $this->transaksi->detailTransaksi($id); 
        $detailTable = $this->transaksi->detailTable($id);
        

        return view($this->route1.'.'.$this->route2.'.'.$this->route3,compact('selectTransaksi','selectKatKegiatan','selectKodeKatKegiatan','selectIDTransaksi','selectKegiatan','selectKepalaKeluarga','data','regPendaftaran','detailTransaksi','detailTable'));
    }

    public function update(Request $request, $id)
    {   
        return $this->transaksi->update($request,$id);
    }

    public function updateDetail(Request $request,$id)
    {  
        return $this->transaksi->updateDetail($request,$id);
    }

    public function deleteDetail($id)
    {   
        return $this->transaksi->deleteDetail($id);
    }

    public function destroy($id)
    {
        return $this->transaksi->destroy($id);
    }

    public function dataTables()
    {
        return $this->transaksi->dataTables();
    }

    public function create_pdf($headerTrxKegiatanId)
    {
        return $this->transaksi->printPdf($headerTrxKegiatanId);
    }
}
