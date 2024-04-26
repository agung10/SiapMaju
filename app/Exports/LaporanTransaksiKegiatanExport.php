<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class LaporanTransaksiKegiatanExport implements FromView
{   
    public function view(): View
    {   
        $laporan = \Request::session()->get('cartLaporan');
        $route_name = explode('.',\Route::currentRouteName());

        $view = $route_name[1] == "LaporanTransaksiKegiatanDKM" ? 'LaporanSikad.LaporanTransaksiKegiatanDKM.printLaporanByKegiatan'
                                                                : 'LaporanSikad.LaporanTransaksiKegiatan.printLaporanByKegiatan';

        if($route_name[1] == "LaporanTransaksiIdulFitri") $view = 'LaporanSikad.LaporanTransaksiIdulFitri.printLaporanByKegiatan';

        return view($view,compact('laporan'));
    }
}
