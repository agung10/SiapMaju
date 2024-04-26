<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Kelurahan;
use App\Models\Master\RT;
use App\Models\Master\RW;
use Illuminate\Http\Request;
use App\Models\Master\TeleponPenting;
use App\Repositories\RoleManagement\UserRepository;

class TeleponPentingController extends Controller
{
    
    public function __construct(UserRepository $user)
    {
        $route_name  = explode('.',\Route::currentRouteName());
        $this->route1 = ((isset($route_name[0])) ? $route_name[0] : (''));
        $this->route2 = ((isset($route_name[1])) ? $route_name[1] : (''));
        $this->route3 = ((isset($route_name[2])) ? $route_name[2] : (''));

        $this->user = $user;
    }

    public function index()
    {
        $isRw = \helper::checkUserRole('rw');
        $isAdmin = \helper::checkUserRole('admin');

        $getData = $this->user->currentUser();
        $kelurahanID = $getData->kelurahan_id;
        $rwID = $getData->rw_id;

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

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('resultRW', 'resultKelurahan', 'isAdmin', 'isRw'));
    }

    public function create()
    {
        $isRt = \helper::checkUserRole('rt');
        $isRw = \helper::checkUserRole('rw');
        $isAdmin = \helper::checkUserRole('admin');

        $getData = $this->user->currentUser();
        $kelurahanID = $getData->kelurahan_id;
        $rwID = $getData->rw_id;
        $rtID = $getData->rt_id;

        $rw = RW::select(
            'rw.rw',
            'rw.rw_id',
        )
            ->join('kelurahan', 'kelurahan.kelurahan_id', 'rw.kelurahan_id')
            ->when($isRw || $isRt, function ($query) use ($rwID) {
                $query->where('rw.rw_id', $rwID);
            })
            ->orderBy('rw', 'ASC')
            ->get();

        $resultRW = '<option></option>';
        foreach ($rw as $res) {
            if ($isRw || $isRt) {
                $resultRW .= '<option value="' . $res->rw_id . '"' . ((!empty($res->rw_id)) ? ((!empty($res->rw_id == $rwID)) ? ('selected') : ('')) : ('')) . '>' . $res->rw . '</option>';
            } else {
                $resultRW .= '<option value="' . $res->rw_id . '">' . $res->rw . '</option>';
            }
        }

        $kelurahan = Kelurahan::select(
            'kelurahan.nama',
            'kelurahan.kelurahan_id'
        )
            ->when($isRw || $isRt, function ($query) use ($kelurahanID) {
                $query->where('kelurahan.kelurahan_id', $kelurahanID);
            })
            ->orderBy('nama', 'ASC')
            ->get();

        $resultKelurahan = '<option></option>';
        foreach ($kelurahan as $res) {
            if ($isRw || $isRt) {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '"' . ((!empty($res->kelurahan_id)) ? ((!empty($res->kelurahan_id == $kelurahanID)) ? ('selected') : ('')) : ('')) . '>' . $res->nama . '</option>';
            } else {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '">' . $res->nama . '</option>';
            }
        }

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('resultRW', 'resultKelurahan', 'isAdmin', 'isRw', 'isRt'));
    }

    public function store(Request $request)
    {
        $input = $request->except('proengsoft_jsvalidation', 'kelurahan_id');

        \DB::beginTransaction();

        try {
            TeleponPenting::create($input);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        $id = \Crypt::decryptString($id);
        $data = TeleponPenting::findOrFail($id);

        $detail = RT::join('rw', 'rw.rw_id', 'rt.rw_id')->join('kelurahan', 'kelurahan.kelurahan_id', 'rt.kelurahan_id')->where('rt.rw_id', $data->rw_id)->first();

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('data', 'detail'));
    }

    public function edit($id)
    {
        $id = \Crypt::decryptString($id);
        $data = TeleponPenting::findOrFail($id);
        $detail = RT::join('rw', 'rw.rw_id', 'rt.rw_id')->join('kelurahan', 'kelurahan.kelurahan_id', 'rt.kelurahan_id')->where('rt.rw_id', $data->rw_id)->first();

        $isAdmin = \helper::checkUserRole('admin');
        $isRw = \helper::checkUserRole('rw');
        $isRt = \helper::checkUserRole('rt');

        $getData = $this->user->currentUser();
        $kelurahanID = $getData->kelurahan_id;
        $rwID = $getData->rw_id;
        $rtID = $getData->rt_id;

        $rw = RW::select(
            'rw.rw',
            'rw.rw_id',
            'rw.kelurahan_id',
        )
            ->where('rw.kelurahan_id', $detail->kelurahan_id)
            ->when($isRw || $isRt, function ($query) use ($rwID) {
                $query->where('rw.rw_id', $rwID);
            })
            ->orderBy('rw', 'ASC')
            ->get();
        $resultRW = '<option disabled selected></option>';
        foreach ($rw as $res) {
            if ($isRw || $isRt) {
                $resultRW .= '<option value="' . $res->rw_id . '"' . ((!empty($res->rw_id)) ? ((!empty($res->rw_id == $rwID)) ? ('selected') : ('')) : ('')) . '>' . $res->rw . '</option>';
            } else {
                $resultRW .= '<option value="' . $res->rw_id . '"' . ((!empty($res->rw_id)) ? ((!empty($res->rw_id == $detail->rw_id)) ? ('selected') : ('')) : ('')) . '>' . $res->rw . '</option>';
            }
        }

        $kelurahan = Kelurahan::select(
            'kelurahan.nama',
            'kelurahan.kelurahan_id'
        )
            ->when($isRw || $isRt, function ($query) use ($kelurahanID) {
                $query->where('kelurahan.kelurahan_id', $kelurahanID);
            })
            ->orderBy('nama', 'ASC')
            ->get();
        $resultKelurahan = '<option disabled selected></option>';
        foreach ($kelurahan as $res) {
            if ($isRw || $isRt) {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '"' . ((!empty($res->kelurahan_id)) ? ((!empty($res->kelurahan_id == $kelurahanID)) ? ('selected') : ('')) : ('')) . '>' . $res->nama . '</option>';
            } else {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '"' . ((!empty($res->kelurahan_id)) ? ((!empty($res->kelurahan_id == $detail->kelurahan_id)) ? ('selected') : ('')) : ('')) . '>' . $res->nama . '</option>';
            }
        }

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('data', 'resultRW', 'resultKelurahan'));
    }

    public function update(Request $request, $id)
    {
        $data = TeleponPenting::findOrFail(\Crypt::decryptString($id));
        $input = $request->except('proengsoft_jsvalidation', 'kelurahan_id');
        $input['updated_at'] = date('Y-m-d H:i:s');

        \DB::beginTransaction();

        try {
            $data->update($input);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        $data = TeleponPenting::findOrFail($id);

        try {
            $data->delete();
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function dataTables(Request $request)
    {
        $isRw = \helper::checkUserRole('rw');
        $isRt = \helper::checkUserRole('rt');

        $getData = $this->user->currentUser();
        $rwID = $getData->rw_id;
        $rtID = $getData->rt_id;

        $datatableButtons = method_exists(new TeleponPenting, 'datatableButtons') ? TeleponPenting::datatableButtons() : ['show', 'edit', 'destroy'];

        if (request()->ajax()) {
            $data = TeleponPenting::select('telepon_penting_id','nama_instansi', 'no_tlp', 'alamat')
                                ->join('rw', 'rw.rw_id', 'telepon_penting.rw_id')
                                ->when($isRw, function ($query) use ($rwID) {
                                    $query->where('rw.rw_id', $rwID);
                                })
                                ->when($isRt, function ($query) use ($rtID) {
                                    $query->where('rw.rt_id', $rtID);
                                })
                                ->when(!empty($request->kelurahan_id), function ($query) use ($request) {
                                    $query->where('rw.kelurahan_id', $request->kelurahan_id);
                                    if (!empty($request->rw_id)) {
                                        $query->where('rw.rw_id', $request->rw_id);
                                    }
                                })
                                ->orderBy('telepon_penting_id', 'desc')
                                ->get();
        }
        return \DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($data) use ($datatableButtons) {
            return view('partials.buttons.cust-datatable',[
                'show'         => in_array("show", $datatableButtons ) ? route('Master.TeleponPenting'.'.show', \Crypt::encryptString($data->telepon_penting_id)) : null,
                'edit'         => in_array("edit", $datatableButtons ) ? route('Master.TeleponPenting'.'.edit', \Crypt::encryptString($data->telepon_penting_id)) : null,
                'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $data->telepon_penting_id : null
            ]);
        })->rawColumns(['action'])
        ->make(true);
    }
}
