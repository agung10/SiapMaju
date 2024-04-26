<?php

namespace App\Http\Controllers\LaporanSikad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\LaporanSikad\LaporanSuratPermohonanRepository;

class LaporanSuratPermohonanController extends Controller
{       
    public function __construct(LaporanSuratPermohonanRepository $_LaporanSuratPermohonanRepository)
    {
        $route_name = explode('.',\Route::currentRouteName());

        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

        $this->laporan = $_LaporanSuratPermohonanRepository;
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

    public function dataTables()
    {
        return $this->laporan->dataTables();
    }
}
