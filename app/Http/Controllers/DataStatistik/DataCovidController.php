<?php

namespace App\Http\Controllers\DataStatistik;

use App\Http\Controllers\Controller;
use App\Models\Master\Kelurahan;
use App\Models\Master\RT;
use App\Models\Master\RW;
use Illuminate\Http\Request;
use App\Repositories\DataCovidRepository;
use App\Repositories\RoleManagement\UserRepository;

class DataCovidController extends Controller
{
    public function __construct(DataCovidRepository $_DataCovidRepository, UserRepository $_user)
    {
        $route_name = explode('.',\Route::currentRouteName());
        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

        $this->covid = $_DataCovidRepository;
        $this->user = $_user;
    }

    public function index()
    {
        $isRw = \helper::checkUserRole('rw');
        $isAdmin = \helper::checkUserRole('admin');

        $getData = $this->user->currentUser();
        $kelurahanID = $getData->kelurahan_id;
        $rwID = $getData->rw_id;

        $rt = RT::select(
            'rt.rt',
            'rt.rt_id',
            'rt.rw_id',
        )
            ->join('rw', 'rw.rw_id', 'rt.rw_id')
            ->join('kelurahan', 'kelurahan.kelurahan_id', 'rw.kelurahan_id')
            ->when($isRw, function ($query) use ($rwID) {
                $query->where('rt.rw_id', $rwID);
            })
            ->orderBy('rt', 'ASC')
            ->get();

        $resultRT = '<option></option>';
        foreach ($rt as $res) {
            $resultRT .= '<option value="' . $res->rt_id . '">' . $res->rt . '</option>';
        }

        $rw = RW::select(
            'rw.rw',
            'rw.rw_id',
        )
            ->join('kelurahan', 'kelurahan.kelurahan_id', 'rw.kelurahan_id')
            ->when($isRw, function ($query) use ($rwID) {
                $query->where('rw.rw_id', $rwID);
            })
            ->orderBy('rw', 'ASC')
            ->get();

        $resultRW = '<option></option>';
        foreach ($rw as $res) {
            if ($isRw) {
                $resultRW .= '<option value="' . $res->rw_id . '"' . ((!empty($res->rw_id)) ? ((!empty($res->rw_id == $rwID)) ? ('selected') : ('')) : ('')) . '>' . $res->rw . '</option>';
            } else {
                $resultRW .= '<option value="' . $res->rw_id . '">' . $res->rw . '</option>';
            }
        }

        $kelurahan = Kelurahan::select(
            'kelurahan.nama',
            'kelurahan.kelurahan_id'
        )
            ->when($isRw, function ($query) use ($kelurahanID) {
                $query->where('kelurahan.kelurahan_id', $kelurahanID);
            })
            ->orderBy('nama', 'ASC')
            ->get();

        $resultKelurahan = '<option></option>';
        foreach ($kelurahan as $res) {
            if ($isRw) {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '"' . ((!empty($res->kelurahan_id)) ? ((!empty($res->kelurahan_id == $kelurahanID)) ? ('selected') : ('')) : ('')) . '>' . $res->nama . '</option>';
            } else {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '">' . $res->nama . '</option>';
            }
        }

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('resultRT', 'resultRW', 'resultKelurahan', 'isAdmin', 'isRw'));
    }


    public function create()
    {
        return view($this->route1.'.'.$this->route2.'.'.$this->route3);
    }

    public function store(Request $request)
    {
        return $this->covid->store($request);
    }

    public function show($id)
    {   
        $id = \Crypt::decryptString($id);
        $data = $this->covid->show($id);

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('data'));
    
    }

    public function edit($id)
    {   
        $id = \Crypt::decryptString($id);
        $data = $this->covid->show($id);

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('data'));
    }

    public function update(Request $request, $id)
    {   
        $id = \Crypt::decryptString($id);
        
        return $this->covid->update($request,$id);
    }

    public function destroy($id)
    {
        return $this->covid->delete($id);
    }

    public function dataTables(Request $request)
    {   
        return $this->covid->dataTables($request);
    }
}
