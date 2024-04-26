<?php

namespace App\Http\Controllers\Tentang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\helper;
use App\Models\Master\Keluarga\AnggotaKeluarga;
use App\Models\Master\Kelurahan;
use App\Models\Master\RT;
use App\Models\Master\RW;
use App\Models\Tentang\Profile;
use App\Repositories\RajaOngkir\RajaOngkirRepository;
use App\Repositories\RoleManagement\UserRepository;

class ProfileController extends Controller
{
    public function __construct(RajaOngkirRepository $rajaOngkir, UserRepository $_user)
    {
        $route_name  = explode('.', \Route::currentRouteName());

        $this->route1 = ((isset($route_name[0])) ? $route_name[0] : (''));
        $this->route2 = ((isset($route_name[1])) ? $route_name[1] : (''));
        $this->route3 = ((isset($route_name[2])) ? $route_name[2] : (''));

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

        if ($getData->province_id) {
            $provinces = $this->rajaOngkir->getProvinces()->where('province_id', $getData->province_id)->pluck('province', 'province_id');
        } else {
            $provinces = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');
        }
        $resultProvince = '<option></option>';
        foreach ($provinces as $province_id => $province) {
            if ($isRw) {
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
                if ($isRw) {
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
                if ($isRw) {
                    $resultSubdistrict .= '<option value="' . $subdistrict_id . '"' . ((!empty($subdistrict_id)) ? ((!empty($subdistrict_id == $getData->subdistrict_id)) ? ('selected') : ('')) : ('')) . '>' . $subdistrict_name . '</option>';
                } else {
                    $resultSubdistrict .= '<option value="' . $subdistrict_id . '">' . $subdistrict_name . '</option>';
                }
            }
        }
        
        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('resultRT', 'resultRW', 'resultKelurahan', 'isAdmin', 'isRw', 'resultProvince', 'resultCity', 'resultSubdistrict'));
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
            ->when($isRw, function ($query) use ($rwID) {
                $query->where('rw.rw_id', $rwID);
            })
            ->when($isRt, function ($query) use ($rwID) {
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
            ->when($isRw, function ($query) use ($kelurahanID) {
                $query->where('kelurahan.kelurahan_id', $kelurahanID);
            })
            ->when($isRt, function ($query) use ($kelurahanID) {
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

        if ($getData->province_id) {
            $provinces = $this->rajaOngkir->getProvinces()->where('province_id', $getData->province_id)->pluck('province', 'province_id');
        } else {
            $provinces = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');
        }
        $resultProvince = '<option></option>';
        foreach ($provinces as $province_id => $province) {
            if ($isRw || $isRt) {
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
                if ($isRw || $isRt) {
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
                if ($isRw || $isRt) {
                    $resultSubdistrict .= '<option value="' . $subdistrict_id . '"' . ((!empty($subdistrict_id)) ? ((!empty($subdistrict_id == $getData->subdistrict_id)) ? ('selected') : ('')) : ('')) . '>' . $subdistrict_name . '</option>';
                } else {
                    $resultSubdistrict .= '<option value="' . $subdistrict_id . '">' . $subdistrict_name . '</option>';
                }
            }
        }

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('resultRT', 'resultRW', 'resultKelurahan', 'isAdmin', 'isRw', 'isRt', 'resultProvince', 'resultCity', 'resultSubdistrict'));
    }

    public function store(Request $request)
    {
        $input = $request->except('proengsoft_jsvalidation');
        $transaction = false;

        \DB::beginTransaction();

        try {
            if ($request->hasFile('gambar_profile')) {
                $input['gambar_profile'] = 'profile' . rand() . '.' . $request->gambar_profile->getClientOriginalExtension();
                $request->gambar_profile->move(public_path('upload/profile'), $input['gambar_profile']);
            }

            Profile::create($input);
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
                "SELECT profileTbl.profile_id, profileTbl.gambar_profile, profileTbl.isi_profile, rtTbl.rt_id, rtTbl.rt, rwTbl.rw_id, rwTbl.rw, kelurahanTbl.kelurahan_id, kelurahanTbl.nama 
                FROM profile as profileTbl 
                LEFT OUTER JOIN rt as rtTbl ON rtTbl.rt_id = profileTbl.rt_id 
                LEFT OUTER JOIN rw as rwTbl ON rwTbl.rw_id = profileTbl.rw_id 
                LEFT OUTER JOIN kelurahan as kelurahanTbl ON kelurahanTbl.kelurahan_id = profileTbl.kelurahan_id 
                WHERE profileTbl.profile_id = '$id'"
            )
        )->first();

        $profileGetAlamat = $this->getAlamat($id);
        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data', 'profileGetAlamat'));
    }

    public function edit($id)
    {
        $id = \Crypt::decryptString($id);
        $data = Profile::findOrFail($id);

        $isAdmin = \helper::checkUserRole('admin');
        $isRw = \helper::checkUserRole('rw');
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
            ->when($isRw, function ($query) use ($rwID) {
                $query->where('rw.rw_id', $rwID);
            })
            ->when($isRt, function ($query) use ($rwID) {
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
            ->when($isRw, function ($query) use ($kelurahanID) {
                $query->where('kelurahan.kelurahan_id', $kelurahanID);
            })
            ->when($isRt, function ($query) use ($kelurahanID) {
                $query->where('kelurahan.kelurahan_id', $kelurahanID);
            })
            ->where('subdistrict_id', $data->subdistrict_id)
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

        if ($getData->province_id) {
            $provinces = $this->rajaOngkir->getProvinces()->where('province_id', $getData->province_id)->pluck('province', 'province_id');
        } else {
            $provinces = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');
        }
        $resultProvince = '<option></option>';
        foreach ($provinces as $province_id => $province) {
            if ($isRw) {
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
            if ($isRw) {
                $resultCity .= '<option value="' . $city_id . '"' . ((!empty($city_id)) ? ((!empty($city_id == $getData->city_id)) ? ('selected') : ('')) : ('')) . '>' . $city_name . '</option>';
            } else {
                $resultCity .= '<option value="' . $city_id . '"' . ((!empty($city_id)) ? ((!empty($city_id == $data->city_id)) ? ('selected') : ('')) : ('')) . '>' . $city_name . '</option>';
            }
        }

        if ($getData->subdistrict_id) {
            $subdistricts = $this->rajaOngkir->getSubdistrictsByCity($data->city_id)->where('subdistrict_id', $data->subdistrict_id)->pluck('subdistrict_name', 'subdistrict_id');
        } else {
            $subdistricts = $this->rajaOngkir->getSubdistrictsByCity($data->city_id)->pluck('subdistrict_name', 'subdistrict_id');
        }
        $resultSubdistrict = '<option></option>';
        foreach ($subdistricts as $subdistrict_id => $subdistrict_name) {
            if ($isRw) {
                $resultSubdistrict .= '<option value="' . $subdistrict_id . '"' . ((!empty($subdistrict_id)) ? ((!empty($subdistrict_id == $getData->subdistrict_id)) ? ('selected') : ('')) : ('')) . '>' . $subdistrict_name . '</option>';
            } else {
                $resultSubdistrict .= '<option value="' . $subdistrict_id . '"' . ((!empty($subdistrict_id)) ? ((!empty($subdistrict_id == $data->subdistrict_id)) ? ('selected') : ('')) : ('')) . '>' . $subdistrict_name . '</option>';
            }
        }

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data', 'isRw', 'resultRT', 'resultRW', 'resultKelurahan', 'resultProvince', 'resultCity', 'resultSubdistrict'));
    }

    public function update(Request $request, $id)
    {
        $id = \Crypt::decryptString($id);
        $model = Profile::findOrFail($id);

        $input = $request->except('proengsoft_jsvalidation');
        $input['updated_at'] = date('Y-m-d H:i:s');
        $input['user_updated'] = \Auth::user()->user_id;

        \DB::beginTransaction();

        try {
            if ($request->hasFile('gambar_profile')) {
                $input['gambar_profile'] = 'profile' . rand() . '.' . $request->gambar_profile->getClientOriginalExtension();
                $request->gambar_profile->move(public_path('upload/profile'), $input['gambar_profile']);

                \File::delete(public_path('upload/profile/' . $model->gambar_profile));
            }

            $model->update($input);

            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        $model = Profile::findOrFail($id);

        try {

            if ($model->image_cover) {
                \File::delete(public_path('upload/galeri/' . $model->image_cover));
            }

            Profile::destroy($id);

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

        $datatableButtons = method_exists(new Profile, 'datatableButtons') ? Profile::datatableButtons() : ['show', 'edit', 'destroy'];

        if (request()->ajax()) {
            $data = \DB::table('profile')
                ->select('profile.profile_id', 'profile.isi_profile', 'profile.gambar_profile')
                ->when($isRw, function ($query) use ($rwID) {
                    $query->where('profile.rw_id', $rwID);
                })
                ->when($isRt, function ($query) use ($rtID) {
                    $query->where('profile.rt_id', $rtID);
                })
                ->when(!empty($request->province_id), function ($query) use ($request) {
                    $query->where('profile.province_id', $request->province_id);
                    if (!empty($request->city_id)) {
                        $query->where('profile.city_id', $request->city_id);
                    }
                    if (!empty($request->subdistrict_id)) {
                        $query->where('profile.subdistrict_id', $request->subdistrict_id);
                    }
                    if (!empty($request->kelurahan_id)) {
                        $query->where('profile.kelurahan_id', $request->kelurahan_id);
                    }
                    if (!empty($request->rw_id)) {
                        $query->where('profile.rw_id', $request->rw_id);
                    }
                    if (!empty($request->rt_id)) {
                        $query->where('profile.rt_id', $request->rt_id);
                    }
                })
                ->orderBy('updated_at', 'desc')
                ->get();
        }

        return \DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('gambar_profile', function ($model) {
                if ($model->gambar_profile) {
                    return '<img src=' . asset('upload/profile/' . $model->gambar_profile) . ' width="200" height="200">';
                } else {
                    return '<img src=' . asset('images/NoPic.png') . ' width="200" height="200">';
                }
            })
            ->addColumn('action', function ($data) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show2'         => ['name' => 'Detail', 'route' => route('Tentang.Profile' . '.show', \Crypt::encryptString($data->profile_id))],
                    'edit2'         => ['name' => 'Edit', 'route' => route('Tentang.Profile' . '.edit', \Crypt::encryptString($data->profile_id))],
                    'ajaxDestroy2'  => ['name' => 'Delete', 'id' => $data->profile_id]
                ]);
            })
            ->rawColumns(['gambar_profile', 'action'])
            ->make(true);
    }

    public function getAlamat($id)
    {
        $profileGetAlamat = \DB::table('profile')->where('profile_id', $id)->first();
        $isi_profile = [
            "subdistrict_id"   => "",
            "province_id"      => "",
            "province"         => "",
            "city_id"          => "",
            "city"             => "",
            "type"             => "",
            "subdistrict_name" => "",
            "isi_profile"        => ""
        ];

        if ($profileGetAlamat) {
            if (empty($profileGetAlamat->subdistrict_id)) {
                $isi_profile = json_decode($this->rajaOngkir->getSubdistrictDetailById(1579), true);
            } else {
                $isi_profile = json_decode($this->rajaOngkir->getSubdistrictDetailById($profileGetAlamat->subdistrict_id), true);
            }
            $isi_profile['isi_profile'] = $profileGetAlamat->isi_profile;
        }

        return $isi_profile;
    }
}
