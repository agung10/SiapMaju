<?php

namespace App\Http\Controllers\landing;

use App\Http\Controllers\Controller;
use App\Models\Master\Keluarga\AnggotaKeluarga;
use App\Repositories\DashboardRepository;
use App\Repositories\RajaOngkir\RajaOngkirRepository;
use Illuminate\Http\Request;

class LandingController extends Controller
{   
    public function __construct(DashboardRepository $_DashboardRepository, RajaOngkirRepository $rajaOngkir)
    {
        $route_name = explode('.',\Route::currentRouteName());
        $this->route1 = ((isset($route_name[0])) ? $route_name[0] : (''));
        $this->route2 = ((isset($route_name[1])) ? $route_name[1] : (''));

        $this->dashboard = $_DashboardRepository;
        $this->rajaOngkir = $rajaOngkir;
    }

    public function index()
    {
        $checkAdmin = \helper::checkUserRole('admin');
        $rwUser = \DB::table('users')->select('rw.rw',)
        ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
        ->leftJoin('rw', 'rw.rw_id', 'anggota_keluarga.rw_id')
        ->where('users.user_id', \Auth::user()->user_id)
        ->first();

        $provinces = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');
        $year = date('Y',strtotime('-3 year'));
        $tahun_pajak = \DB::table('pbb')->select('tahun_pajak')->distinct('tahun_pajak')->where('tahun_pajak', '>=', $year)->orderBy('tahun_pajak', 'asc')->get();
        return view($this->route1.'.'.$this->route2, compact('provinces', 'checkAdmin', 'rwUser', 'tahun_pajak'));
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

    public function approvalPermohonan($id)
    {
        return $this->dashboard->approvalSuratPermohonan($id);
    }

    public function destroy($id)
    {
        //
    }

    public function search(Request $request)
    {
        session()->flash('dashboardDataParams', array(
            "province_id" => $request->province_id,
            "city_id" => $request->city_id,
            "subdistrict_id" => $request->subdistrict_id,
            "kelurahan_id" => $request->kelurahan_id,
            "rw_id" => $request->rw_id,
            "rt_id" => $request->rt_id
        ));
        $checkAdmin = \helper::checkUserRole('admin');
        
        $provinces = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');
        $year = date('Y',strtotime('-3 year'));
        $tahun_pajak = \DB::table('pbb')->select('tahun_pajak')->distinct('tahun_pajak')->where('tahun_pajak', '>=', $year)->orderBy('tahun_pajak', 'asc')->get();
        return view('landing.index', compact('provinces', 'checkAdmin', 'tahun_pajak'));
    }
    
    public function searchTahunPajak($tahun_pajak) 
    {
        $tahun['jumlah_pbb_terdistribusi'] = \DB::table('pbb')->where('pbb.tahun_pajak','=',$tahun_pajak)->count();
        $tahun['jumlah_sudah_bayar'] = \DB::table('pbb')->where('nilai', '!=', 0)->whereNotNull('tgl_bayar')->where('pbb.tahun_pajak','=',$tahun_pajak)->count();
        $tahun['total'] = \DB::table('pbb')->where('nilai', '!=', 0)->whereNotNull('tgl_bayar')->where('pbb.tahun_pajak','=',$tahun_pajak)->sum('nilai');
        return response()->json($tahun);
    }
}
