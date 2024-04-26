<?php

namespace App\Http\Controllers\LaporanSikad;

use App\Http\Controllers\Controller;
use App\Repositories\LaporanSikad\LaporanTransaksiIdulFitriRepository;
use App\Exports\LaporanTransaksiKegiatanExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanTransaksiIdulFitriController extends Controller
{
    public function __construct(LaporanTransaksiIdulFitriRepository $_LaporanTransaksiKegiatanRepository)
    {
        $route_name = explode('.',\Route::currentRouteName());
        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

        $this->laporan = $_LaporanTransaksiKegiatanRepository;
    }

    public function index()
    {

        return view($this->route1.'.'.$this->route2.'.'.$this->route3);
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

    public function printLaporanByKegiatan()
    {
        return Excel::download(new LaporanTransaksiKegiatanExport, 'Laporan Transaksi Kegiatan DKM.xlsx');
    }

    public function dataTables()
    {
        return $this->laporan->dataTables();
    }
}
