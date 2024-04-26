<?php

namespace App\Http\Controllers\Master\RW;

use App\Http\Controllers\Controller;
use App\Models\Master\CapRW;
use Illuminate\Http\Request;
use App\Repositories\Master\CapRWRepository;

class CapRWController extends Controller
{
    public function __construct(CapRWRepository $_CapRWRepository)
    {
        $route_name = explode('.', \Route::currentRouteName());
        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

        $this->capRW = $_CapRWRepository;
    }

    public function index()
    {
        $kelurahan = \DB::table('kelurahan')->select('kelurahan.nama', 'kelurahan.kelurahan_id')->orderBy('nama', 'ASC')->get();
        $resultKelurahan = '<option></option>';
        foreach ($kelurahan as $res) {
            $resultKelurahan .= '<option value="' . $res->kelurahan_id . '">' . $res->nama . '</option>';
        }
        
        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('resultKelurahan'));
    }

    public function create()
    {
        $rw = \DB::table('rw')->orderBy('rw', 'ASC')->get();
        $resultRW = '<option></option>';
        foreach ($rw as $res) {
            $resultRW .= '<option value="' . $res->rw_id . '">' . $res->rw . '</option>';
        }

        $kelurahan = \DB::table('kelurahan')->orderBy('nama', 'ASC')->get();
        $resultKelurahan = '<option></option>';
        foreach ($kelurahan as $res) {
            $resultKelurahan .= '<option value="' . $res->kelurahan_id . '">' . $res->nama . '</option>';
        }

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('resultRW', 'resultKelurahan'));
    }

    public function store(Request $request)
    {
        return $this->capRW->store($request);
    }

    public function show($id)
    {
        $id = \Crypt::decryptString($id);
        $data = CapRW::join('rw', 'rw.rw_id', 'cap_rw.rw_id')->join('kelurahan', 'kelurahan.kelurahan_id', 'rw.kelurahan_id')->findOrFail($id);

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data'));
    }

    public function edit($id)
    {
        $id = \Crypt::decryptString($id);
        $data = CapRW::select(
            'cap_rw.cap_rw_id',
            'cap_rw.cap_rw',
            'rw.rw_id',
            'rw.kelurahan_id'
        )
            ->join('rw', 'rw.rw_id', 'cap_rw.rw_id')
            ->findOrFail($id);

        $rw = \DB::table('rw')->where('rw.kelurahan_id', $data->kelurahan_id)->orderBy('rw', 'ASC')->get();
        $resultRW = '<option disabled selected></option>';
        foreach ($rw as $res) {
            $resultRW .= '<option value="' . $res->rw_id . '"' . ((!empty($res->rw_id)) ? ((!empty($res->rw_id == $data->rw_id)) ? ('selected') : ('')) : ('')) . '>' . $res->rw . '</option>';
        }

        $kelurahan = \DB::table('kelurahan')->orderBy('nama', 'ASC')->get();
        $resultKelurahan = '<option disabled selected></option>';
        foreach ($kelurahan as $res) {
            $resultKelurahan .= '<option value="' . $res->kelurahan_id . '"' . ((!empty($res->kelurahan_id)) ? ((!empty($res->kelurahan_id == $data->kelurahan_id)) ? ('selected') : ('')) : ('')) . '>' . $res->nama . '</option>';
        }

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data', 'resultRW', 'resultKelurahan'));
    }

    public function update(Request $request, $id)
    {
        $id = \Crypt::decryptString($id);
        return $this->capRW->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->capRW->delete($id);
    }

    public function dataTables(Request $request)
    {
        return $this->capRW->dataTables($request);
    }
}
