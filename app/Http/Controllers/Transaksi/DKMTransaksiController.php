<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\DKMTransakasiRepository;
use App\Repositories\HeaderTransaksiRepository; 

class DKMTransaksiController extends Controller
{
    public function __construct(DKMTransakasiRepository $_DKMTransakasiRepository,HeaderTransaksiRepository $_HeaderTransaksiRepository)
    {
        $route_name = explode('.',\Route::currentRouteName());
        
        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

        $this->transaksi = $_DKMTransakasiRepository;
        $this->transaksiHeader =  $_HeaderTransaksiRepository;
    }

    public function index()
    {
        $months = $this->monthOption();
        $years = $this->yearOption();

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('months', 'years'));
    }

    public function create()
    {
        $selectTransaksi = $this->transaksiHeader->selectTransaksi(false,false,false,'dkm');
        $selectKatKegiatan = $this->transaksiHeader->selectKatKegiatan(false,'dkm');
        $selectKodeKatKegiatan = $this->transaksiHeader->selectKodeKatKegiatan();
        $selectIDTransaksi = \helper::select('transaksi','transaksi_id');
        $selectKegiatan = \helper::select('kegiatan','nama_kegiatan');
        $selectKepalaKeluarga = $this->transaksiHeader->selectKepalaKeluarga(false,'dkm');

        return view($this->route1.'.'.$this->route2.'.'.$this->route3,compact('selectTransaksi','selectKatKegiatan','selectKodeKatKegiatan','selectIDTransaksi','selectKegiatan','selectKepalaKeluarga'));
    }


    public function store(Request $request)
    {
        return $this->transaksiHeader->store($request);
    }

    public function show($id)
    {
        $id = \Crypt::decryptString($id);

        $data = $this->transaksiHeader->show($id);
        $regPendaftaran = explode('/',$data->no_pendaftaran)[0];
        $noBukti= !empty($data->no_bukti) ? explode('/',$data->no_pendaftaran)[0] : '';

        $detailTransaksi = $this->transaksiHeader->detailTransaksi($id);

        return view($this->route1.'.'.$this->route2.'.'.$this->route3,compact('data','regPendaftaran','detailTransaksi','noBukti'));
    }

    public function edit($id)
    {
        $id = \Crypt::decryptString($id);

        $data = $this->transaksiHeader->show($id);
        $selectTransaksi = $this->transaksiHeader->selectTransaksi($data->transaksi_id,false,false,'dkm');
        $selectKatKegiatan = $this->transaksiHeader->selectKatKegiatan($data->kat_kegiatan_id,'dkm');
        $selectKodeKatKegiatan = $this->transaksiHeader->selectKodeKatKegiatan($data->kat_kegiatan_id);
        $selectIDTransaksi = \helper::select('transaksi','transaksi_id');
        $selectKegiatan = \helper::select('kegiatan','nama_kegiatan',$data->kegiatan_id);
        $selectKepalaKeluarga = $this->transaksiHeader->selectKepalaKeluarga($data->anggota_keluarga_id,'dkm');        
        $regPendaftaran = explode('/',$data->no_pendaftaran)[0];
        $detailTransaksi = $this->transaksiHeader->detailTransaksi($id); 
        $detailTable = $this->transaksiHeader->detailTable($id);
        

        return view($this->route1.'.'.$this->route2.'.'.$this->route3,compact('selectTransaksi','selectKatKegiatan','selectKodeKatKegiatan','selectIDTransaksi','selectKegiatan','selectKepalaKeluarga','data','regPendaftaran','detailTransaksi','detailTable'));
    }

    public function update(Request $request, $id)
    {
        return $this->transaksiHeader->update($request,$id);
    }

    public function destroy($id)
    {
        return $this->transaksiHeader->destroy($id);
    }

    public function dataTables(Request $request)
    {
        return $this->transaksi->dataTables($request);
    }

    public function create_pdf($headerTrxKegiatanId)
    {
        return $this->transaksi->printPdf($headerTrxKegiatanId);
    }

    public function monthOption()
    {
        return [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
    }

    public function yearOption()
    {
        $startYear = date("Y") - 5;
        $endYear   = date("Y") + 5;
        $years     = range($startYear, $endYear);
        $years     = array_combine($years, $years);

        return $years;
    }
}
