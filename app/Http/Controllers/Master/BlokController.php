<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\helper;
use App\Models\Master\Blok;
use App\Models\Master\Kelurahan;
use App\Models\Master\RT;
use App\Models\Master\RW;
use App\Repositories\RajaOngkir\RajaOngkirRepository;
use App\Repositories\RoleManagement\UserRepository;
use Illuminate\Validation\Rule;

class BlokController extends Controller
{
    public function __construct(RajaOngkirRepository $rajaOngkir, UserRepository $user)
    {
        $route_name = explode('.', \Route::currentRouteName());
        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

        $this->rajaOngkir = $rajaOngkir;
        $this->user = $user;
    }

    public function index()
    {
        $isAdmin = \helper::checkUserRole('admin');
        $provinces = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('isAdmin', 'provinces'));
    }

    public function create()
    {
        $isRt = \helper::checkUserRole('rt');

        $getData = $this->user->currentUser();
        $rtID = $getData->rt_id;
        $rwID = $getData->rw_id;
        $kelurahanID = $getData->kelurahan_id;

        $rt = RT::select(
            'rt.rt',
            'rt.rt_id',
            'rw.rw',
            'kelurahan.nama'
        )
            ->join('rw', 'rw.rw_id', 'rt.rw_id')
            ->join('kelurahan', 'kelurahan.kelurahan_id', 'rw.kelurahan_id')
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
            ->when($isRt, function ($query) use ($rwID) {
                $query->where('rw.rw_id', $rwID);
            })
            ->orderBy('rw', 'ASC')
            ->get();
        $resultRW = '<option></option>';
        foreach ($rw as $res) {
            if ($isRt) {
                $resultRW .= '<option value="' . $res->rw_id . '"' . ((!empty($res->rw_id)) ? ((!empty($res->rw_id == $rwID)) ? ('selected') : ('')) : ('')) . '>' . $res->rw . '</option>';
            } else {
                $resultRW .= '<option value="' . $res->rw_id . '">' . $res->rw . '</option>';
            }
        }

        $kelurahan = Kelurahan::select(
            'kelurahan.nama',
            'kelurahan.kelurahan_id'
        )
            ->when($isRt, function ($query) use ($kelurahanID) {
                $query->where('kelurahan.kelurahan_id', $kelurahanID);
            })
            ->orderBy('nama', 'ASC')
            ->get();
        $resultKelurahan = '<option></option>';
        foreach ($kelurahan as $res) {
            if ($isRt) {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '"' . ((!empty($res->kelurahan_id)) ? ((!empty($res->kelurahan_id == $kelurahanID)) ? ('selected') : ('')) : ('')) . '>' . $res->nama . '</option>';
            } else {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '">' . $res->nama . '</option>';
            }
        }

        if ($getData->province_id) {
            $provinces = $this->rajaOngkir->getProvinces()->where('province_id', $getData->province_id)->pluck('province', 'province_id');
        } else {
            $provinces = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');
        }
        $resultProvince = '<option></option>';
        foreach ($provinces as $province_id => $province) {
            if ($isRt) {
                $resultProvince .= '<option value="' . $province_id . '"' . ((!empty($province_id)) ? ((!empty($province_id == $getData->province_id)) ? ('selected') : ('')) : ('')) . '>' . $province . '</option>';
            } else {
                $resultProvince .= '<option value="' . $province_id . '">' . $province . '</option>';
            }
        }

        $resultCity = '<option></option>';
        if ($getData->city_id) {
            $cities = $this->rajaOngkir->getCitiesByProvince($getData->province_id)
            ->map(function ($value) {
                $value->city_name = $value->type . ' ' . $value->city_name;
                return $value;
            })
            ->where('city_id', $getData->city_id)
            ->pluck('city_name', 'city_id');
            
            foreach ($cities as $city_id => $city_name) {
                if ($isRt) {
                    $resultCity .= '<option value="' . $city_id . '"' . ((!empty($city_id)) ? ((!empty($city_id == $getData->city_id)) ? ('selected') : ('')) : ('')) . '>' . $city_name . '</option>';
                } else {
                    $resultCity .= '<option value="' . $city_id . '">' . $city_name . '</option>';
                }
            }
        }

        $resultSubdistrict = '<option></option>';
        if ($getData->subdistrict_id) {
            $subdistricts = $this->rajaOngkir->getSubdistrictsByCity($getData->city_id)->where('subdistrict_id', $getData->subdistrict_id)->pluck('subdistrict_name', 'subdistrict_id');
            
            foreach ($subdistricts as $subdistrict_id => $subdistrict_name) {
                if ($isRt) {
                    $resultSubdistrict .= '<option value="' . $subdistrict_id . '"' . ((!empty($subdistrict_id)) ? ((!empty($subdistrict_id == $getData->subdistrict_id)) ? ('selected') : ('')) : ('')) . '>' . $subdistrict_name . '</option>';
                } else {
                    $resultSubdistrict .= '<option value="' . $subdistrict_id . '">' . $subdistrict_name . '</option>';
                }
            }
        }

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('resultRT', 'resultRW', 'resultKelurahan', 'isRt', 'resultProvince', 'resultCity', 'resultSubdistrict'));
    }

    public function store(Request $request)
    {
        $input = $request->except('proengsoft_jsvalidation');
        $input['nama_blok'] = ucfirst($input['nama_blok']);
        
        $rules = [
            'nama_blok' => [
                'required', Rule::unique('blok')->where(function($query) use ($input) {
                    $query->where('blok.rt_id', $input['rt_id']);
                })
            ],
        ];
        
        $validator = helper::validation($request->all(), $rules);
        \DB::beginTransaction();

        if ($validator->fails()) {
            // return response()->json(['status' => 'failed', 'errors' => $validator->getMessageBag()->toArray()]);
            return response()->json(['status' => 'error_blok']);
        }
        try {
            Blok::create($input);
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
        $data = Blok::select(
            'blok.blok_id',
            'blok.nama_blok',
            'blok.long',
            'blok.lang',
            'blok.nop',
            'rt.rt_id',
            'rt.rt',
            'rw.rw_id',
            'rw.rw',
            'kelurahan.nama',
            'kelurahan.kelurahan_id',
        )
            ->leftjoin('rt', 'rt.rt_id', 'blok.rt_id')
            ->leftjoin('rw', 'rw.rw_id', 'blok.rw_id')
            ->leftjoin('kelurahan', 'kelurahan.kelurahan_id', 'blok.kelurahan_id')
            ->findorFail($id);

        // $data =  collect(
        //     \DB::select(
        //         "SELECT blokTbl.blok_id, blokTbl.nama_blok, blokTbl.long, blokTbl.lang, rtTbl.rt_id, rtTbl.rt, rwTbl.rw_id, rwTbl.rw, kelurahanTbl.kelurahan_id, kelurahanTbl.nama 
        //         FROM blok as blokTbl 
        //         LEFT OUTER JOIN rt as rtTbl ON rtTbl.rt_id = blokTbl.rt_id 
        //         LEFT OUTER JOIN rw as rwTbl ON rwTbl.rw_id = blokTbl.rw_id 
        //         LEFT OUTER JOIN kelurahan as kelurahanTbl ON kelurahanTbl.kelurahan_id = blokTbl.kelurahan_id 
        //         WHERE blokTbl.blok_id = '$id'"
        //     )
        // )->first();

        $blokGetAlamat = app('App\Http\Controllers\Master\BlokController')->getAlamat($id);

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data', 'blokGetAlamat'));
    }

    public function edit($id)
    {
        $id = \Crypt::decryptString($id);
        $data = Blok::findOrFail($id);

        $isRt = \helper::checkUserRole('rt');
        $getData = $this->user->currentUser();
        $kelurahanID = $getData->kelurahan_id;
        $rwID = $getData->rw_id;
        $rtID = $getData->rt_id;

        $rt = RT::select(
            'rt.rt',
            'rt.rt_id',
            'rt.rw_id',
            'rt.kelurahan_id'
        )
            ->where('rt.rw_id', $data->rw_id)
            ->join('rw', 'rw.rw_id', 'rt.rw_id')
            ->join('kelurahan', 'kelurahan.kelurahan_id', 'rw.kelurahan_id')
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
            ->when($isRt, function ($query) use ($rwID) {
                $query->where('rw.rw_id', $rwID);
            })
            ->orderBy('rw', 'ASC')
            ->get();
        $resultRW = '<option disabled selected></option>';
        foreach ($rw as $res) {
            if ($isRt) {
                $resultRW .= '<option value="' . $res->rw_id . '"' . ((!empty($res->rw_id)) ? ((!empty($res->rw_id == $rwID)) ? ('selected') : ('')) : ('')) . '>' . $res->rw . '</option>';
            } else {
                $resultRW .= '<option value="' . $res->rw_id . '"' . ((!empty($res->rw_id)) ? ((!empty($res->rw_id == $data->rw_id)) ? ('selected') : ('')) : ('')) . '>' . $res->rw . '</option>';
            }
        }

        $kelurahan = Kelurahan::select(
            'kelurahan.nama',
            'kelurahan.kelurahan_id'
        )
            ->where('subdistrict_id', $data->subdistrict_id)
            ->when($isRt, function ($query) use ($kelurahanID) {
                $query->where('kelurahan.kelurahan_id', $kelurahanID);
            })
            ->orderBy('nama', 'ASC')
            ->get();
        $resultKelurahan = '<option disabled selected></option>';
        foreach ($kelurahan as $res) {
            if ($isRt) {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '"' . ((!empty($res->kelurahan_id)) ? ((!empty($res->kelurahan_id == $kelurahanID)) ? ('selected') : ('')) : ('')) . '>' . $res->nama . '</option>';
            } else {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '"' . ((!empty($res->kelurahan_id)) ? ((!empty($res->kelurahan_id == $data->kelurahan_id)) ? ('selected') : ('')) : ('')) . '>' . $res->nama . '</option>';
            }
        }

        if ($getData->province_id) {
            $provinces = $this->rajaOngkir->getProvinces()->where('province_id', $getData->province_id)->pluck('province', 'province_id');
        } else {
            $provinces = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');
        }
        $resultProvince = '<option></option>';
        foreach ($provinces as $province_id => $province) {
            if ($isRt) {
                $resultProvince .= '<option value="' . $province_id . '"' . ((!empty($province_id)) ? ((!empty($province_id == $getData->province_id)) ? ('selected') : ('')) : ('')) . '>' . $province . '</option>';
            } else {
                $resultProvince .= '<option value="' . $province_id . '"' . ((!empty($province_id)) ? ((!empty($province_id == $data->province_id)) ? ('selected') : ('')) : ('')) . '>' . $province . '</option>';
            }        
        }
        
        if ($getData->city_id) {
            $cities = $this->rajaOngkir->getCitiesByProvince($data->province_id)
            ->map(function ($value) {
                $value->city_name = $value->type . ' ' . $value->city_name;
                return $value;
            })
            ->where('city_id', $data->city_id)
            ->pluck('city_name', 'city_id');
        } else {
            $cities = $this->rajaOngkir->getCitiesByProvince($data->province_id)
            ->map(function ($value) {
                $value->city_name = $value->type . ' ' . $value->city_name;
                return $value;
            })
            ->pluck('city_name', 'city_id');
        }
        $resultCity = '<option></option>';
        foreach ($cities as $city_id => $city_name) {
            if ($isRt) {
                $resultCity .= '<option value="' . $city_id . '"' . ((!empty($city_id)) ? ((!empty($city_id == $getData->city_id)) ? ('selected') : ('')) : ('')) . '>' . $city_name . '</option>';
            } else {
                $resultCity .= '<option value="' . $city_id . '"' . ((!empty($city_id)) ? ((!empty($city_id == $data->city_id)) ? ('selected') : ('')) : ('')) . '>' . $city_name . '</option>';
            }        }

        if ($getData->subdistrict_id) {
            $subdistricts = $this->rajaOngkir->getSubdistrictsByCity($data->city_id)->where('subdistrict_id', $data->subdistrict_id)->pluck('subdistrict_name', 'subdistrict_id');
        } else {
            $subdistricts = $this->rajaOngkir->getSubdistrictsByCity($data->city_id)->pluck('subdistrict_name', 'subdistrict_id');
        }
        $resultSubdistrict = '<option></option>';
        foreach ($subdistricts as $subdistrict_id => $subdistrict_name) {
            if ($isRt) {
                $resultSubdistrict .= '<option value="' . $subdistrict_id . '"' . ((!empty($subdistrict_id)) ? ((!empty($subdistrict_id == $getData->subdistrict_id)) ? ('selected') : ('')) : ('')) . '>' . $subdistrict_name . '</option>';
            } else {
                $resultSubdistrict .= '<option value="' . $subdistrict_id . '"' . ((!empty($subdistrict_id)) ? ((!empty($subdistrict_id == $data->subdistrict_id)) ? ('selected') : ('')) : ('')) . '>' . $subdistrict_name . '</option>';
            }        
        }

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data', 'resultRT', 'resultRW', 'resultKelurahan', 'resultProvince', 'resultCity', 'resultSubdistrict'));
    }

    public function update(Request $request, $id, Blok $blok)
    {
        $id = \Crypt::decryptString($id);
        $data = Blok::findOrFail($id);

        $input = $request->except('proengsoft_jsvalidation');
        $input['nama_blok'] = ucfirst($input['nama_blok']);
    
        $rules = [
            'nama_blok' => [
                'required',
                Rule::unique('blok', 'nama_blok')->ignore($id, 'blok.blok_id')->where(function($query) use ($input) {
                    $query->where('blok.rt_id', $input['rt_id']);
                })
            ],
        ];

        $input['updated_at'] = date('Y-m-d H:i:s');
        $input['user_updated'] = \Auth::user()->user_id;

        $validator = helper::validation($request->all(), $rules);
        \DB::beginTransaction();

        if ($validator->fails()) {
            // return response()->json(['status' => 'failed', 'errors' => $validator->getMessageBag()->toArray()]);
            return response()->json(['status' => 'error_blok']);
        }

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
        $data = Blok::findOrFail($id);

        try {
            Blok::destroy($id);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function dataTables(Request $request)
    {
        $currentRT = \DB::table('users')->select('anggota_keluarga.rt_id')
            ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
            ->where('users.user_id', \Auth::user()->user_id)
            ->first();

        $isRt = \helper::checkUserRole('rt');

        $datatableButtons = method_exists(new Blok, 'datatableButtons') ? Blok::datatableButtons() : ['show', 'edit', 'destroy'];

        if (request()->ajax()) {
            $data = Blok::select('blok.blok_id', 'blok.nama_blok', 'blok.long as longitude', 'blok.lang as latitude', 'blok.updated_at')
                ->when($isRt, function ($query) use ($currentRT) {
                    $query->where('blok.rt_id', $currentRT->rt_id);
                })
                ->when(!empty($request->province_id), function ($query) use ($request) {
                    $query->where('blok.province_id', $request->province_id);
                    if (!empty($request->city_id)) {
                        $query->where('blok.city_id', $request->city_id);
                    }
                    if (!empty($request->subdistrict_id)) {
                        $query->where('blok.subdistrict_id', $request->subdistrict_id);
                    }
                    if (!empty($request->kelurahan_id)) {
                        $query->where('blok.kelurahan_id', $request->kelurahan_id);
                    }
                    if (!empty($request->rw_id)) {
                        $query->where('blok.rw_id', $request->rw_id);
                    }
                    if (!empty($request->rt_id)) {
                        $query->where('blok.rt_id', $request->rt_id);
                    }
                });
        }

        return \DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show'         => in_array("show", $datatableButtons) ? route('Master.Blok' . '.show', \Crypt::encryptString($data->blok_id)) : null,
                    'edit'         => in_array("edit", $datatableButtons) ? route('Master.Blok' . '.edit', \Crypt::encryptString($data->blok_id)) : null,
                    'ajax_destroy' => in_array("destroy", $datatableButtons) ? $data->blok_id : null
                ]);
            })->rawColumns(['action'])
            ->make(true);
    }

    public function getAlamat($id)
    {
        $blokGetAlamat = \DB::table('blok')->where('blok_id', $id)->first();
        $nama_blok = [
            "subdistrict_id"   => "",
            "province_id"      => "",
            "province"         => "",
            "city_id"          => "",
            "city"             => "",
            "type"             => "",
            "subdistrict_name" => "",
            "nama_blok"        => ""
        ];

        if ($blokGetAlamat) {
            if (empty($blokGetAlamat->subdistrict_id)) {
                $nama_blok = json_decode($this->rajaOngkir->getSubdistrictDetailById(1579), true);
            } else {
                $nama_blok = json_decode($this->rajaOngkir->getSubdistrictDetailById($blokGetAlamat->subdistrict_id), true);
            }
            $nama_blok['nama_blok'] = $blokGetAlamat->nama_blok;
        }

        return $nama_blok;
    }
}
