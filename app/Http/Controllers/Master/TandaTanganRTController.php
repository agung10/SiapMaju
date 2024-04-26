<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\TandaTanganRT;
use App\Repositories\Master\TandaTanganRtRepository;
use Illuminate\Http\Request;

class TandaTanganRTController extends Controller
{
    public function __construct(TandaTanganRtRepository $_TandaTanganRtRepository)
    {
        $route_name = explode('.', \Route::currentRouteName());
        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

        $this->ttdRT = $_TandaTanganRtRepository;
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
        $rt = \DB::table('rt')->orderBy('rt', 'ASC')->get();
        $resultRT = '<option></option>';
        foreach ($rt as $res) {
            $resultRT .= '<option value="' . $res->rt_id . '">' . $res->rt . '</option>';
        }

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

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('resultRT', 'resultRW', 'resultKelurahan'));
    }

    public function store(Request $request)
    {
        return $this->ttdRT->store($request);
    }

    public function show($id)
    {
        $id = \Crypt::decryptString($id);
        $data = TandaTanganRT::join('rt', 'rt.rt_id', 'tanda_tangan_rt.rt_id')->join('rw', 'rw.rw_id', 'rt.rw_id')->join('kelurahan', 'kelurahan.kelurahan_id', 'rt.kelurahan_id')->findOrFail($id);

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data'));
    }

    public function edit($id)
    {
        $id = \Crypt::decryptString($id);
        $data = TandaTanganRT::select(
                'tanda_tangan_rt.tanda_tangan_rt_id',
                'tanda_tangan_rt.tanda_tangan_rt',
                'rt.rt_id',
                'rt.rt',
                'rt.rw_id',
                'rt.kelurahan_id'
            )
            ->join('rt', 'rt.rt_id', 'tanda_tangan_rt.rt_id')
            ->findOrFail($id);

        $rt = \DB::table('rt')->where('rt.rw_id', $data->rw_id)->orderBy('rt', 'ASC')->get();
        $resultRT = '<option disabled selected></option>';
        foreach ($rt as $res) {
            $resultRT .= '<option value="' . $res->rt_id . '"' . ((!empty($res->rt_id)) ? ((!empty($res->rt_id == $data->rt_id)) ? ('selected') : ('')) : ('')) . '>' . $res->rt . '</option>';
        }

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

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data', 'resultRT', 'resultRW', 'resultKelurahan'));
    }

    public function update(Request $request, $id)
    {
        $id = \Crypt::decryptString($id);
        return $this->ttdRT->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->ttdRT->delete($id);
    }

    public function dataTables(Request $request)
    {
        return $this->ttdRT->dataTables($request);
    }
}
