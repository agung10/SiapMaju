<?php

namespace App\Http\Controllers\LaporanSikad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\LaporanSikad\LaporanTransaksiKegiatanRepository;
use App\Repositories\HeaderTransaksiRepository; 
use App\Exports\LaporanTransaksiKegiatanExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanTransaksiKegiatanController extends Controller
{
    public function __construct(LaporanTransaksiKegiatanRepository $_LaporanTransaksiKegiatanRepository,HeaderTransaksiRepository $_HeaderTransaksiRepository)
    {
        $route_name = explode('.',\Route::currentRouteName());
        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

        $this->laporan = $_LaporanTransaksiKegiatanRepository;
        $this->headerTransaksi = $_HeaderTransaksiRepository;
    }

    public function index()
    {   
        $selectKatKegiatan = $this->headerTransaksi->selectKatKegiatan();

        return view($this->route1.'.'.$this->route2.'.'.$this->route3,compact('selectKatKegiatan'));
    }

    public function create()
    {   
        return view($this->route1.'.'.$this->route2.'.'.$this->route3);
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
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

    public function searchLaporan(Request $request)
    {
        return $this->laporan->searchLaporan($request);
    }

    public function searchLaporanByKegiatan(Request $request)
    {   
        return $this->laporan->searchLaporanByKegiatan($request);
    }

    public function printLaporanByKegiatan()
    {
        return Excel::download(new LaporanTransaksiKegiatanExport, 'Laporan Transaksi Kegiatan.xlsx');
    }

    public function dataTables()
    {
        return $this->laporan->dataTables();
    }
}
