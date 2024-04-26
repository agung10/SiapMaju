<?php

namespace App\Repositories\Master;

use App\Models\Master\Keluarga\{Keluarga, AnggotaKeluarga, MutasiWarga};
use App\User;
use App\Models\RoleManagement\UserRole;
use App\Helpers\helper;
use App\Repositories\RajaOngkir\RajaOngkirRepository;

class KeluargaRepository
{
    public function __construct(AnggotaKeluarga $_AnggotaKeluarga, RajaOngkirRepository $rajaOngkir)
    {
        $this->anggota = $_AnggotaKeluarga;
        $this->rajaOngkir = $rajaOngkir;
    }

    public function dataTables($request)
    {
        $datatableButtons = method_exists(new Keluarga, 'datatableButtons') ? Keluarga::datatableButtons() : ['show', 'edit', 'destroy'];

        $logedUSer = \DB::table('users')
            ->select('anggota_keluarga.is_rt', 'anggota_keluarga.is_rw', 'users.is_admin', 'keluarga.rt_id', 'keluarga.rw_id')
            ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
            ->leftJoin('keluarga', 'keluarga.keluarga_id', 'anggota_keluarga.keluarga_id')
            ->where('user_id', \Auth::user()->user_id)
            ->first();

        $isRt = $logedUSer->is_rt == true;
        $isRw = $logedUSer->is_rw == true;
        $isAdmin = $logedUSer->is_admin == true;
        $isPetugas = $isRt == true || $isRw == true || $isAdmin == true;
        $isWarga = $isRt != true && $isRw != true && $isAdmin != true;

        if (request()->ajax()) {
            $data = Keluarga::select([
                'keluarga.keluarga_id', 'keluarga.alamat', 'keluarga.no_telp', 'keluarga.email', 'blok.nama_blok as nama_blok','rt.rt','anggota_keluarga.nama'
                ])
                ->join('anggota_keluarga', 'anggota_keluarga.keluarga_id', 'keluarga.keluarga_id')
                ->join('blok', 'blok.blok_id', 'keluarga.blok_id')
                ->join('rt', 'rt.rt_id', 'keluarga.rt_id')
                ->when($isRt == true, function ($query) use ($logedUSer) {
                    $query->where('keluarga.rt_id', $logedUSer->rt_id);
                })
                ->when($isRw == true, function ($query) use ($logedUSer) {
                    $query->where('keluarga.rw_id', $logedUSer->rw_id);
                })
                ->when(!empty($request->province_id), function ($query) use ($request) {
                    $query->where('keluarga.province_id', $request->province_id);
                    if (!empty($request->city_id)) {
                        $query->where('keluarga.city_id', $request->city_id);
                    }
                    if (!empty($request->subdistrict_id)) {
                        $query->where('keluarga.subdistrict_id', $request->subdistrict_id);
                    }
                    if (!empty($request->kelurahan_id)) {
                        $query->where('keluarga.kelurahan_id', $request->kelurahan_id);
                    }
                    if (!empty($request->rw_id)) {
                        $query->where('keluarga.rw_id', $request->rw_id);
                    }
                    if (!empty($request->rt_id)) {
                        $query->where('keluarga.rt_id', $request->rt_id);
                    }
                    if (!empty($request->blok_id)) {
                        $query->where('keluarga.blok_id', $request->blok_id);
                    }
                })
                ->where('anggota_keluarga.hub_keluarga_id', 1)
                ->where('anggota_keluarga.is_active', '!=', false)
                ->withCount(['anggotaKeluarga']);
        }

        return \DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('anggota_keluarga_count', function ($data) use ($datatableButtons) {
                $names = '';
                $jenis_kelamin = '';

                $detail = $this->anggota->where('keluarga_id', $data->keluarga_id)->orderBy('anggota_keluarga.created_at', 'asc')->get();
                $badge = count($detail) > 1 ? 'badge-primary' : 'badge-warning';

                foreach($detail as $key => $d) {
                    $jenis_kelamin .= $d->jenis_kelamin;
                    if(count($detail) > 1) $names .= '<br />';

                    $mutasi = \DB::table('mutasi_warga')->where('anggota_keluarga_id', $d->anggota_keluarga_id)->latest("mutasi_warga_id")->first();

                    if ($mutasi != null) {
                        if ($mutasi->status_mutasi_warga_id == 1) {
                            $names .= $d->nama;
                        } else if ($mutasi->status_mutasi_warga_id == 2) {
                            $names .= $d->nama . ' (Pindah)';
                        } else if ($mutasi->status_mutasi_warga_id == 3) {
                            if ($d->jenis_kelamin == "L") {
                                $names .= 'Alm. ' . $d->nama;
                            } else if ($d->jenis_kelamin == "P") {
                                $names .= 'Almh. ' . $d->nama;
                            }
                        }
                    } else {
                        $names .= $d->nama;
                    }
                }

                $anggotaKeluarga = "<span class='badge {$badge}' data-toggle='tooltip' data-html='true' title='{$names}' data-original-title='{$names}'>
                            {$data->anggota_keluarga_count}
                        </span>";

                return $anggotaKeluarga;
            })
            ->addColumn('action', function ($data) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show'         => in_array("show", $datatableButtons) ? route('Master.ListKeluarga' . '.show', \Crypt::encryptString($data->keluarga_id)) : null,
                    'edit'         => in_array("edit", $datatableButtons) ? route('Master.ListKeluarga' . '.edit', \Crypt::encryptString($data->keluarga_id)) : null,
                    'ajax_destroy' => in_array("destroy", $datatableButtons) ? $data->keluarga_id : null
                ]);
            })
            ->rawColumns(['action', 'anggota_keluarga_count'])
            ->make(true);
    }

    public function store($request)
    {
        $wargaLama =  AnggotaKeluarga::select('anggota_keluarga_id', 'keluarga_id')->where('anggota_keluarga_id', $request->anggota_keluarga_id)->first();
        
        $rules = '';
        if ($request->anggota_keluarga_id) {
            $user = \DB::table('users')->select('user_id')->where('anggota_keluarga_id', $wargaLama->anggota_keluarga_id)->first();
            $rules = ['email' => 'unique:keluarga,email,' . (string)$wargaLama->keluarga_id . ',keluarga_id|unique:anggota_keluarga,email,' . (string)$wargaLama->anggota_keluarga_id . ',anggota_keluarga_id|unique:users,email,' . (string)$user->user_id . ',user_id'];
        } else {
            $rules = ['email' => 'unique:keluarga,email|unique:anggota_keluarga,email|unique:users,email'];
        }
        $validators = helper::validation($request->all(), $rules);
        if ($validators->fails()) {
            return response(['status' => 'error_email']);
        }

        $input = $request->except('proengsoft_jsvalidation', 'warga_id', 'tgl_lahir', 'jenis_kelamin', 'hub_keluarga_id', 'anggota_keluarga_id');
        $input['user_created'] = \Auth::user()->user_id;

        try {
            if ($request->hasFile('file')) {
                $input['file'] = 'file_' . rand() . '.' . $request->file->getClientOriginalExtension();
                $request->file->move(public_path('uploaded_files/file'), $input['file']);
            }
            $keluarga = Keluarga::create($input);

            $inputWarga = [
                'keluarga_id' => $keluarga->keluarga_id,
                'hub_keluarga_id' => $request->hub_keluarga_id,
                'province_id' => $keluarga->province_id,
                'subdistrict_id' => $keluarga->subdistrict_id,
                'city_id' => $keluarga->city_id,
                'kelurahan_id' => $keluarga->kelurahan_id,
                'rw_id' => $keluarga->rw_id,
                'rt_id' => $keluarga->rt_id,
            ];
            if ($wargaLama) {
                $wargaLama->update($inputWarga);

                $inputDataMutasi = [
                    'anggota_keluarga_id' => $wargaLama->anggota_keluarga_id,
                    'status_mutasi_warga_id' => 1,
                    'tanggal_mutasi' => $keluarga->created_at,
                    'keterangan' => 'Menjadi Kepala Keluarga',
                ];
                MutasiWarga::create($inputDataMutasi);
            }

            \DB::commit();
            return response()->json([
                'status' => 'success',
                'email' => $keluarga->email,
                'no_telp' => $keluarga->no_telp,
                'alamat' => $keluarga->alamat,
                'keluarga_id' => \Crypt::encryptString($keluarga->keluarga_id),
                'rt_id' => $keluarga->rt_id,
                'rw_id' => $keluarga->rw_id,
                'kelurahan_id' => $keluarga->kelurahan_id,
                'subdistrict_id' => $keluarga->subdistrict_id,
                'city_id' => $keluarga->city_id,
                'province_id' => $keluarga->province_id,
            ]);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        $hub_kepala_keluarga = \DB::table('hub_keluarga')
            ->select('hub_keluarga_id')
            ->where('hubungan_kel', 'Kepala Keluarga')
            ->first();

        $data = Keluarga::select(
            'keluarga.keluarga_id',
            'keluarga.alamat',
            'keluarga.status_domisili',
            'keluarga.alamat_ktp',
            'keluarga.no_telp',
            'keluarga.email',
            'keluarga.blok_id',
            'keluarga.rt_id',
            'keluarga.rw_id',
            'keluarga.kelurahan_id',
            'keluarga.subdistrict_id',
            'keluarga.city_id',
            'keluarga.province_id',
            'keluarga.file',
            'anggota_keluarga.nama as anggota_nama',
            'anggota_keluarga.alamat as anggota_alamat',
            'anggota_keluarga.mobile as anggota_mobile',
            'anggota_keluarga.email as anggota_email',
            'anggota_keluarga.anggota_keluarga_id',
            'anggota_keluarga.jenis_kelamin',
            'blok.nama_blok as nama_blok'
        )
            ->join('blok', 'blok.blok_id', 'keluarga.blok_id')
            ->leftJoin('anggota_keluarga', 'anggota_keluarga.keluarga_id', 'keluarga.keluarga_id')
            ->findOrFail($id);

        $kepala_keluarga = $this->anggota
            ->select('nama', 'mobile', 'tgl_lahir')
            ->where('keluarga_id', $data->keluarga_id)
            ->where('hub_keluarga_id', $hub_kepala_keluarga->hub_keluarga_id)
            ->first();
        return compact('data', 'kepala_keluarga');
    }

    public function destroy($id)
    {
        $keluarga = Keluarga::findOrFail($id);

        $anggota_keluarga = $this->anggota
            ->select('anggota_keluarga_id')
            ->where('keluarga_id', $keluarga->keluarga_id)
            ->get();

        if ($anggota_keluarga) {
            foreach ($anggota_keluarga as $key => $val) {
                User::where('anggota_keluarga_id', $val->anggota_keluarga_id)
                    ->delete();
            }
        }

        $anggota = $this->anggota
            ->where('keluarga_id', $keluarga->keluarga_id)
            ->delete();

        if ($keluarga->file) {
            \File::delete(public_path('uploaded_files/file/' . $keluarga->file));
        }
        Keluarga::destroy($id);

        return response()->json(['status' => 'success']);
    }

    public function storeAnggotaKeluarga($request)
    {
        $transaction = false;
        $input = $request->except('proengsoft_jsvalidation');
        $password = '135790';
        $input['password'] = bcrypt($password);
        $input['keluarga_id'] = \Crypt::decryptString($request->keluarga_id);

        \DB::beginTransaction();
        try {

            $anggota = $this->anggota->create($input);

            $inputUser = [
                'username' => $request->email,
                'email' => $request->email,
                'password' => $input['password'],
                'anggota_keluarga_id' => $anggota->anggota_keluarga_id
            ];

            $inputDataMutasi = [
                'anggota_keluarga_id' => $anggota->anggota_keluarga_id,
                'status_mutasi_warga_id' => 1,
                'tanggal_mutasi' => $anggota->created_at,
                'keterangan' => '',
            ];
            MutasiWarga::create($inputDataMutasi);

            $user = User::create($inputUser);

            $this->storeUserRole($user);

            \DB::commit();
            $transaction = true;
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }

        if ($transaction === true) {
            //    $this->sendMail($request->email,$password);
            return response()->json(['status' => 'success']);
        }
    }

    public function updateAnggotaKeluarga($request, $id)
    {
        $transaction = false;

        $input = $request->except('proengsoft_jsvalidation');
        $input['keluarga_id'] = \Crypt::decryptString($request->keluarga_id);
        $anggota = $this->anggota->findOrFail($id);
        \DB::beginTransaction();

        try {
            $anggota->update($input);

            \DB::commit();
            $transaction = true;
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }

        if ($transaction === true) {
            return response()->json(['status' => 'success']);
        }
    }

    public function updateKeluarga($request, $id)
    {
        $anggotaKeluarga = \DB::table('anggota_keluarga')->select('anggota_keluarga_id', 'keluarga_id', 'hub_keluarga_id')->where('keluarga_id', $id)->where('hub_keluarga_id', 1)->first();
        $user = \DB::table('users')->select('user_id')->where('anggota_keluarga_id', $anggotaKeluarga->anggota_keluarga_id)->first();
        
        $transaction = false;

        $rules = ['email' => 'unique:keluarga,email,' . $id . ',keluarga_id|unique:anggota_keluarga,email,' . (string)$anggotaKeluarga->anggota_keluarga_id . ',anggota_keluarga_id|unique:users,email,' . (string)$user->user_id . ',user_id'];
        $validators = helper::validation($request->all(), $rules); 
                
        if ($validators->fails()) {
            return response(['status' => 'error_email']);
        }

        $input = $request->except('proengsoft_jsvalidation');
        $input['keluarga_id'] = \Crypt::decryptString($request->keluarga_id);
        $keluarga = Keluarga::findOrFail($id);

        \DB::beginTransaction();

        try {
            if ($request->hasFile('file')) {
                $input['file'] = 'file_' . rand() . '.' . $request->file->getClientOriginalExtension();
                $request->file->move(public_path('uploaded_files/file'), $input['file']);

                \File::delete(public_path('uploaded_files/file/' . $keluarga->file));
            }
            $keluarga->update($input);

            \DB::commit();
            $transaction = true;
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }

        if ($transaction === true) {
            return response()->json([
                'status' => 'success',
                'email' => $keluarga->email,
                'no_telp' => $keluarga->no_telp,
                'alamat' => $keluarga->alamat,
                'rt_id' => $keluarga->rt_id,
                'rw_id' => $keluarga->rw_id,
                'kelurahan_id' => $keluarga->kelurahan_id,
                'subdistrict_id' => $keluarga->subdistrict_id,
                'city_id' => $keluarga->city_id,
                'province_id' => $keluarga->province_id,
            ]);
        }
    }

    public function storeUserRole($user)
    {
        $role = \DB::table('role')
            ->select('role_id')
            ->where('role_name', 'Warga')
            ->first();

        $role_id = $role->role_id ?? 0;

        $role = UserRole::create([
            'user_id' => $user->user_id,
            'role_id' => $role->role_id,
        ]);
    }

    public function sendMail($email, $password)
    {
        $mailContent = 'Keluarga anda berhasil didaftarkan, Password anda adalah: ' . $password . ' Silahkan lakukan penggantian password';

        \Mail::raw($mailContent, function ($message) use ($email) {
            $message->to($email)
                ->from('tdp.sikad@mail.com', 'TDP')
                ->subject('Notifikasi Penambahan Anggota Keluarga');
        });
    }

    public function getAlamat($id)
    {
        $keluargaGetAlamat = \DB::table('keluarga')->where('keluarga_id', $id)->first();
        $alamat = [
            "subdistrict_id"   => "",
            "province_id"      => "",
            "province"         => "",
            "city_id"          => "",
            "city"             => "",
            "type"             => "",
            "subdistrict_name" => "",
            "alamat"           => ""
        ];

        if ($keluargaGetAlamat) {
            if (empty($keluargaGetAlamat->subdistrict_id)) {
                $alamat = json_decode($this->rajaOngkir->getSubdistrictDetailById(1579), true);
            } else {
                $alamat = json_decode($this->rajaOngkir->getSubdistrictDetailById($keluargaGetAlamat->subdistrict_id), true);
            }
            $alamat['alamat'] = $keluargaGetAlamat->alamat;
        }

        return $alamat;
    }
}
