<?php

namespace App\Http\Controllers\Tentang\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\helper;
use App\Models\Master\Kelurahan;
use App\Models\Master\RT;
use App\Models\Master\RW;
use App\Models\Tentang\Pengurus;
use App\Models\Tentang\KatPengurus;
use App\Repositories\RajaOngkir\RajaOngkirRepository;
use App\Repositories\RoleManagement\UserRepository;

class ListPengurusController extends Controller
{
    public function __construct(RajaOngkirRepository $rajaOngkir, UserRepository $_user)
    {
        $this->rajaOngkir = $rajaOngkir;
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

        return view('Tentang.Pengurus.ListPengurus.index', compact('resultRT', 'resultRW', 'resultKelurahan', 'isAdmin', 'isRw'));
    }

    public function create()
    {
        $KatPengurus = KatPengurus::select('kat_pengurus.kat_pengurus_id', 'kat_pengurus.nama_kategori')
            ->get();

        $result = '<option disabled selected></option>';
        foreach ($KatPengurus as $myData) {
            $result .= '<option value="' . $myData->kat_pengurus_id . '">' . $myData->nama_kategori . '</option>';
        }

        $isRt = \helper::checkUserRole('rt');
        $isRw = \helper::checkUserRole('rw');
        $isAdmin = \helper::checkUserRole('admin');

        $getData = $this->user->currentUser();
        $kelurahanID = $getData->kelurahan_id;
        $rwID = $getData->rw_id;
        $rtID = $getData->rt_id;

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
            ->when($isRt, function ($query) use ($rtID) {
                $query->where('rt.rt_id', $rtID);
            })
            ->orderBy('rt', 'ASC')
            ->get();

        $resultRT = '<option></option>';
        foreach ($rt as $res) {
            if ($isRt) {
                $resultRT .= '<option value="' . $res->rt_id . '"' . ((!empty($res->rt_id)) ? ((!empty($res->rt_id == $rtID)) ? ('selected') : ('')) : ('')) . '>' . $res->rt . '</option>';
            } else {
                $resultRT .= '<option value="' . $res->rt_id . '">' . $res->rt . '</option>';
            }
        }

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

        return view('Tentang.Pengurus.ListPengurus.create', compact('result', 'resultRT', 'resultRW', 'resultKelurahan', 'isAdmin', 'isRw', 'isRt'));
    }

    public function store(Request $request)
    {
        $input = $request->except('proengsoft_jsvalidation');
        $transaction = false;

        $rules = [
            'kat_pengurus_id' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
            'jabatan' => 'required',
            'photo' => 'required|max:2000|mimes:jpg,jpeg,png',
        ];

        $validator = helper::validation($request->all(), $rules);
        \DB::beginTransaction();

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'errors' => $validator->getMessageBag()->toArray()]);
        }
        try {
            if ($request->hasFile('photo')) {
                $input['photo'] = 'pengurus' . rand() . '.' . $request->photo->getClientOriginalExtension();
                $request->photo->move(public_path('upload/pengurus'), $input['photo']);
            }

            Pengurus::create($input);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        $id = \Crypt::decryptString($id);

        $data =  collect(
            \DB::select(
                "SELECT pengurusTbl.pengurus_id, pengurusTbl.photo, pengurusTbl.nama, pengurusTbl.jabatan, pengurusTbl.alamat, pengurusTbl.no_urut, kat_pengurusTbl.nama_kategori as kat_pengurus, rtTbl.rt_id, rtTbl.rt, rwTbl.rw_id, rwTbl.rw, kelurahanTbl.kelurahan_id, kelurahanTbl.nama 
                FROM pengurus as pengurusTbl 
                LEFT OUTER JOIN kat_pengurus as kat_pengurusTbl ON kat_pengurusTbl.kat_pengurus_id = pengurusTbl.kat_pengurus_id 
                LEFT OUTER JOIN rt as rtTbl ON rtTbl.rt_id = pengurusTbl.rt_id 
                LEFT OUTER JOIN rw as rwTbl ON rwTbl.rw_id = pengurusTbl.rw_id 
                LEFT OUTER JOIN kelurahan as kelurahanTbl ON kelurahanTbl.kelurahan_id = pengurusTbl.kelurahan_id 
                WHERE pengurusTbl.pengurus_id = '$id'"
            )
        )->first();

        $pengurusGetAlamat = $this->getAlamat($id);
        return view('Tentang.Pengurus.ListPengurus.show', compact('data', 'pengurusGetAlamat'));
    }

    public function edit($id)
    {
        $id = \Crypt::decryptString($id);
        $data = Pengurus::findOrFail($id);

        $isAdmin = \helper::checkUserRole('admin');
        $isRw = \helper::checkUserRole('rw');
        $isRt = \helper::checkUserRole('rt');

        $getData = $this->user->currentUser();
        $kelurahanID = $getData->kelurahan_id;
        $rwID = $getData->rw_id;
        $rtID = $getData->rt_id;

        $KatPengurus = KatPengurus::select('kat_pengurus.kat_pengurus_id', 'kat_pengurus.nama_kategori')
            ->get();

        $result = '<option disabled selected></option>';
        foreach ($KatPengurus as $myData) {
            $result .= '<option value="' . $myData->kat_pengurus_id . '" ' . ((!empty($myData->kat_pengurus_id)) ? (($data->kat_pengurus_id == $myData->kat_pengurus_id) ? ('selected') : ('')) : ('')) . '>' . $myData->nama_kategori . '</option>';
        }

        $rt = RT::select(
            'rt.rt',
            'rt.rt_id',
            'rt.rw_id',
            'rt.kelurahan_id'
        )
            ->where('rt.rw_id', $data->rw_id)
            ->join('rw', 'rw.rw_id', 'rt.rw_id')
            ->join('kelurahan', 'kelurahan.kelurahan_id', 'rw.kelurahan_id')
            ->when($isRw, function ($query) use ($rwID) {
                $query->where('rw.rw_id', $rwID);
            })
            ->when($isRt, function ($query) use ($rtID) {
                $query->where('rt.rt_id', $rtID);
            })
            ->orderBy('rt', 'ASC')
            ->get();
        $resultRT = '<option disabled selected></option>';
        foreach ($rt as $res) {
            if ($isRt) {
                $resultRT .= '<option value="' . $res->rt_id . '"' . ((!empty($res->rt_id)) ? ((!empty($res->rt_id == $rtID)) ? ('selected') : ('')) : ('')) . '>' . $res->rt . '</option>';
            } else {
                $resultRT .= '<option value="' . $res->rt_id . '"' . ((!empty($res->rt_id)) ? ((!empty($res->rt_id == $data->rt_id)) ? ('selected') : ('')) : ('')) . '>' . $res->rt . '</option>';
            }
        }

        $rw = RW::select(
            'rw.rw',
            'rw.rw_id',
            'rw.kelurahan_id',
        )
            ->where('rw.kelurahan_id', $data->kelurahan_id)
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
                $resultRW .= '<option value="' . $res->rw_id . '"' . ((!empty($res->rw_id)) ? ((!empty($res->rw_id == $data->rw_id)) ? ('selected') : ('')) : ('')) . '>' . $res->rw . '</option>';
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
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '"' . ((!empty($res->kelurahan_id)) ? ((!empty($res->kelurahan_id == $data->kelurahan_id)) ? ('selected') : ('')) : ('')) . '>' . $res->nama . '</option>';
            }
        }

        return view('Tentang.Pengurus.ListPengurus.edit', compact('data', 'result', 'resultRT', 'resultRW', 'resultKelurahan'));
    }

    public function update(Request $request, $id)
    {
        $id = \Crypt::decryptString($id);
        $data = Pengurus::findOrFail($id);

        $input = $request->except('proengsoft_jsvalidation');
        $input['updated_at'] = date('Y-m-d H:i:s');
        $input['user_updated'] = \Auth::user()->user_id;

        $rules = [
            'kat_pengurus_id' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
            'jabatan' => 'required',
            'photo' => 'max:2000|mimes:jpg,jpeg,png',
        ];

        $validator = helper::validation($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'errors' => $validator->getMessageBag()->toArray()]);
        }

        \DB::beginTransaction();

        try {
            if ($request->hasFile('photo')) {
                $input['photo'] = 'pengurus' . rand() . '.' . $request->photo->getClientOriginalExtension();
                $request->photo->move(public_path('upload/pengurus'), $input['photo']);

                \File::delete(public_path('upload/pengurus/' . $data->photo));
            }

            $data->update($input);

            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        $data = Pengurus::findOrFail($id);

        try {
            if ($data->photo) {
                \File::delete(public_path('upload/pengurus/' . $data->photo));
            }
            Pengurus::destroy($id);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function dataTables(Request $request)
    {
        $checkRole = \helper::checkUserRole('all');
        $isRw = $checkRole['isRw'];
        $isRt = $checkRole['isRt'];

        $getData = $this->user->currentUser();
        $rwID = $getData->rw_id;
        $rtID = $getData->rt_id;

        $datatableButtons = method_exists(new Pengurus, 'datatableButtons') ? Pengurus::datatableButtons() : ['show', 'edit', 'destroy'];
        
        if (request()->ajax()) {
            $data = \DB::table('pengurus')
            ->select(
                'pengurus.pengurus_id',
                'pengurus.nama',
                'pengurus.jabatan',
                'kat_pengurus.nama_kategori as kat_pengurus',
                'pengurus.photo'
            )
                ->join('kat_pengurus', 'kat_pengurus.kat_pengurus_id', 'pengurus.kat_pengurus_id')
                ->when($isRw, function ($query) use ($rwID) {
                    $query->where('pengurus.rw_id', $rwID);
                })
                ->when($isRt, function ($query) use ($rtID) {
                    $query->where('pengurus.rt_id', $rtID);
                })
                ->when(!empty($request->kelurahan_id), function ($query) use ($request) {
                    $query->where('pengurus.kelurahan_id', $request->kelurahan_id);
                    if (!empty($request->rw_id)) {
                        $query->where('pengurus.rw_id', $request->rw_id);
                    }
                    if (!empty($request->rt_id)) {
                        $query->where('pengurus.rt_id', $request->rt_id);
                    }
                })
                ->orderBy('pengurus_id', 'desc')
                ->get();
        }
        return \DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('photo', function ($row) {
                return view('layouts.partials.image_column', ['src' => \helper::loadImgUpload('pengurus', $row->photo)]);
            })
            ->addColumn('action', function ($data) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show2'         => ['name' => 'Detail', 'route' => route('Tentang.ListPengurus' . '.show', \Crypt::encryptString($data->pengurus_id))],
                    'edit2'         => ['name' => 'Edit', 'route' => route('Tentang.ListPengurus' . '.edit', \Crypt::encryptString($data->pengurus_id))],
                    'ajaxDestroy2'  => ['name' => 'Delete', 'id' => $data->pengurus_id]
                ]);
            })
            ->rawColumns(['action', 'photo'])
            ->make(true);
    }

    public function getAlamat($id)
    {
        $pengurusGetAlamat = \DB::table('pengurus')->where('pengurus_id', $id)->first();
        $alamat = [
            "subdistrict_id"   => "",
            "province_id"      => "",
            "province"         => "",
            "city_id"          => "",
            "city"             => "",
            "type"             => "",
            "subdistrict_name" => "",
            "alamat"        => ""
        ];

        if ($pengurusGetAlamat) {
            if (empty($pengurusGetAlamat->subdistrict_id)) {
                $alamat = json_decode($this->rajaOngkir->getSubdistrictDetailById(1579), true);
            } else {
                $alamat = json_decode($this->rajaOngkir->getSubdistrictDetailById($pengurusGetAlamat->subdistrict_id), true);
            }
            $alamat['alamat'] = $pengurusGetAlamat->alamat;
        }

        return $alamat;
    }
}
