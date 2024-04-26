<?php

namespace App\Http\Controllers\Master\Keluarga;
use App\Http\Controllers\Controller;
use App\Http\Requests\Keluarga\{ MutasiWargaRequest };
use App\Repositories\Master\{ MutasiWargaRepository };
use Illuminate\Http\Request;

class MutasiWargaController extends Controller
{
    public function __construct(MutasiWargaRepository $mutasi)
    {
        $route_name = explode('.', \Route::currentRouteName());
        $this->route1 = ((isset($route_name[0])) ? ($route_name[0]) : (''));
        $this->route2 = ((isset($route_name[1])) ? ($route_name[1]) : (''));
        $this->route3 = ((isset($route_name[2])) ? ($route_name[2]) : (''));
        $this->mutasi = $mutasi;
    }

    public function index() {
        return view($this->route1 . '.Keluarga.' . $this->route2 . '.' . $this->route3);
    }

    public function create() {
        $familyMember = $this->mutasi->getFamilyMember(\Auth::user()->anggota_keluarga_id);
        $movedStatus = $this->mutasi->getMovedStatus();
        return view($this->route1 . '.Keluarga.' . $this->route2 . '.' . $this->route3, compact('familyMember', 'movedStatus'));
    }

    public function store(MutasiWargaRequest $request) {
        return $this->mutasi->store($request);
    }

    public function show($id) {
        $data = $this->mutasi->show(\Crypt::decryptString($id));
        $history = $this->history(\Crypt::decryptString($id));
        return view($this->route1 . '.Keluarga.' . $this->route2 . '.' . $this->route3, compact('data', 'history'));
    }

    public function edit($id) {}

    public function update(Request $request, $id) {}

    public function destroy($id) {}

    public function history($memberID) {
        $data = $this->mutasi->getHistory($memberID);
        return view($this->route1 . '.Keluarga.' . $this->route2 . '.riwayat', compact('data'));
    }

    public function dataTables() {
        return $this->mutasi->getMovedDatatables();
    }
}