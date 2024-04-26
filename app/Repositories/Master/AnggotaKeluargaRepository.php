<?php

namespace App\Repositories\Master;

use App\Models\Master\Keluarga\{Keluarga, AnggotaKeluarga, AnggotaKeluargaAlamat};
use App\User;
use App\Models\RoleManagement\UserRole;
use App\Helpers\helper;
use App\Repositories\RajaOngkir\RajaOngkirRepository;

class AnggotaKeluargaRepository
{
    public function __construct(AnggotaKeluarga $_AnggotaKeluarga, AnggotaKeluargaAlamatRepository $_AnggotaKeluargaAlamatRepo, RajaOngkirRepository $rajaOngkir)
    {
        $this->anggota = $_AnggotaKeluarga;
        $this->anggotaKeluargaAlamat = $_AnggotaKeluargaAlamatRepo;

        $this->rajaOngkir = $rajaOngkir;
    }

    public function checkKepalaKeluarga()
    {
        $hub_keluarga = \DB::table('hub_keluarga')
            ->select('hub_keluarga_id')
            ->where('hubungan_kel', 'Kepala Keluarga')
            ->first();

        $anggota_keluarga = $this->anggota
            ->select('hub_keluarga_id')
            ->where('anggota_keluarga_id', \Auth::user()->anggota_keluarga_id)
            ->first();

        if (empty($anggota_keluarga->hub_keluarga_id)) {
            return false;
        }

        $isKepalaKeluarga = $hub_keluarga->hub_keluarga_id === $anggota_keluarga->hub_keluarga_id ? true : false;

        return $isKepalaKeluarga;
    }

    public function dataTables($request)
    {
        $isKepalaKeluarga = $this->checkKepalaKeluarga();
        $logedKeluarga = null;

        if ($isKepalaKeluarga) {
            $logedKeluarga = $this->anggota
                ->select('keluarga_id')
                ->findOrFail(\Auth::user()->anggota_keluarga_id);
        } else {
            // loged as anggota keluarga that is not kepala keluarga
            $logedKeluarga = $this->anggota
                ->select('anggota_keluarga_id')
                ->find(\Auth::user()->anggota_keluarga_id);
        }

        if (request()->ajax()) {
            $data = AnggotaKeluarga::select([
                'anggota_keluarga.anggota_keluarga_id',
                'anggota_keluarga.nama',
                'anggota_keluarga.alamat',
                'blok.nama_blok',
                'rt.rt',
                'hub_keluarga.hubungan_kel'
            ])
                ->join('keluarga', 'keluarga.keluarga_id', 'anggota_keluarga.keluarga_id')
                ->join('blok', 'blok.blok_id', 'keluarga.blok_id')
                ->join('rt', 'rt.rt_id', 'keluarga.rt_id')
                ->join('hub_keluarga', 'hub_keluarga.hub_keluarga_id', 'anggota_keluarga.hub_keluarga_id')
                ->orWhere(function ($query) use ($logedKeluarga) {
                    if ($logedKeluarga) {
                        $query->where('anggota_keluarga.anggota_keluarga_id', $logedKeluarga->anggota_keluarga_id);
                    }
                })
                ->orWhere(function ($query) use ($isKepalaKeluarga, $logedKeluarga) {
                    if ($isKepalaKeluarga) {
                        $query->where('anggota_keluarga.keluarga_id', $logedKeluarga->keluarga_id);
                        $query->where('anggota_keluarga.is_active', true);
                    }
                })
                ->where('anggota_keluarga.is_active', true)
                ->when(!empty($request->province_id), function ($query) use ($request) {
                    $query->where('anggota_keluarga.province_id', $request->province_id);
                    if (!empty($request->city_id)) {
                        $query->where('anggota_keluarga.city_id', $request->city_id);
                    }
                    if (!empty($request->subdistrict_id)) {
                        $query->where('anggota_keluarga.subdistrict_id', $request->subdistrict_id);
                    }
                    if (!empty($request->kelurahan_id)) {
                        $query->where('anggota_keluarga.kelurahan_id', $request->kelurahan_id);
                    }
                    if (!empty($request->rw_id)) {
                        $query->where('anggota_keluarga.rw_id', $request->rw_id);
                    }
                    if (!empty($request->rt_id)) {
                        $query->where('anggota_keluarga.rt_id', $request->rt_id);
                    }
                });
        }

        return \DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return view('partials.buttons.cust-datatable', [
                    'show' => route('Master.AnggotaKeluarga' . '.show', \Crypt::encryptString($data->anggota_keluarga_id)),
                    'edit' => route('Master.AnggotaKeluarga' . '.edit', \Crypt::encryptString($data->anggota_keluarga_id)),
                    'ajax_destroy' => $data->anggota_keluarga_id
                ]);
            })
            ->rawColumns(['action', 'keluarga'])
            ->make(true);
    }

    public function store($request)
    {
        $transaction = false;

        $rules = ['email' => 'unique:anggota_keluarga,email|unique:users,email'];
        $validators = helper::validation($request->all(), $rules);

        if ($validators->fails()) {
            return response(['status' => 'error_email']);
        }

        $input = $request->except('proengsoft_jsvalidation', 'umkm');

        $checkRT = AnggotaKeluarga::select('rt_id', 'is_rt')->where('rt_id', $input['rt_id'])->where('anggota_keluarga.is_rt', true)->first();
        if ($checkRT == null) {
            return response(['status' => 'error_rt']);
        }

        $input['is_active'] = false;
        $input['have_umkm'] = false;

        $input['password'] = bcrypt(strtolower($request->password));
        $input['tgl_lahir'] = date('Y-m-d', strtotime($request->tgl_lahir));

        $url = 'https://dev2.kamarkerja.com:3333/message/text';
        $response = @get_headers($url);

        // if cant reach url
        if (!$response) return redirect()->back()->with('error', 'Maaf terjadi kesalahan'); 

        $whatsappKey = \DB::table('whatsapp_key')
            ->select('whatsapp_key')
            ->first()
            ->whatsapp_key ?? null;

        if (!$whatsappKey) return redirect()->back()->with('error','No Whatsapp belum disandingkan'); 

        \DB::beginTransaction();
        try {
            $anggota = $this->anggota->create($input);
            $inputUser = [
                'username' => $request->email,
                'email' => $request->email,
                'password' => $input['password'],
                'anggota_keluarga_id' => $anggota->anggota_keluarga_id,
            ];

            $data = AnggotaKeluarga::select('anggota_keluarga_id', 'keluarga_id', 'rt_id', 'nama', 'mobile')
            ->where('anggota_keluarga_id', $anggota->anggota_keluarga_id)
            ->first();

            $kepalaKeluarga = \DB::table('anggota_keluarga')->select('anggota_keluarga.nama', 'anggota_keluarga.mobile')->where('anggota_keluarga.keluarga_id', $data->keluarga_id)->where('anggota_keluarga.hub_keluarga_id', 1)->first();
            $mobileKK = '62' . substr($kepalaKeluarga->mobile, 1);
            $whatsapp_msgKK = "Proses penambahan anggota baru yang anda ajukan telah berhasil, silahkan menunggu Ketua RT setempat untuk melakukan approve data.";
            \Http::post("$url?key=$whatsappKey",[
                'id' => $mobileKK,
                'message' => $whatsapp_msgKK
            ]);

            $ketuaRT = \DB::table('anggota_keluarga')->select('anggota_keluarga.mobile')->where('anggota_keluarga.rt_id', $data->rt_id)->where('anggota_keluarga.is_rt', true)->first();
            $mobileRT = '62' . substr($ketuaRT->mobile, 1);
            $whatsapp_msgRT = "Mohon persetujuannya untuk penambahan anggota keluarga dari ($kepalaKeluarga->nama) atas nama $data->nama, melalui aplikasi SiapMaju.";
            \Http::post("$url?key=$whatsappKey",[
                'id' => $mobileRT,
                'message' => $whatsapp_msgRT
            ]);

            $user = User::create($inputUser);
            $this->storeUserRole($user);

            \DB::commit();
            $transaction = true;
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }

        if ($transaction) {
            return response()->json(['status' => 'success']);
        }
    }

    public function update($request, $id)
    {
        $user = \DB::table('users')->select('user_id')->where('anggota_keluarga_id', $id)->first();
        $transaction = false;

        $rules = ['email' => 'unique:anggota_keluarga,email,' . $id . ',anggota_keluarga_id|unique:users,email,' . (string)$user->user_id . ',user_id'];
        $validators = helper::validation($request->all(), $rules);

        if ($validators->fails()) {
            return response(['status' => 'error_email']);
        }

        $input = $request->except('proengsoft_jsvalidation', 'umkm');
        $input['tgl_lahir'] =  date('Y-m-d', strtotime($request->tgl_lahir));

        $anggota = $this->anggota->findOrFail($id);
        $user = User::where('anggota_keluarga_id', $id)->first();
        $keluarga = Keluarga::where('keluarga_id', $request->keluarga_id)->first();

        if ($request->has('password')) {
            $input['password'] = bcrypt(strtolower($request->password));
        }

        $inputUser = [
            'username' => $request->email,
            'email' => $request->email,
            'password' => $input['password']
        ];

        if (empty($request->password)) {
            unset($inputUser['password']);
        }

        if ($request->keluarga_id && $request->hub_keluarga_id == 1) {
            $inputKeluarga = [
                'no_telp' => $request->mobile,
                'alamat' => $request->alamat,
                'email' => $request->email,
                'rt_id' => $request->rt_id,
                'rw_id' => $request->rw_id,
                'kelurahan_id' => $request->kelurahan_id,
                'subdistrict_id' => $request->subdistrict_id,
                'city_id' => $request->city_id,
                'province_id' => $request->province_id,
            ];
        }

        \DB::beginTransaction();
        try {

            $anggota->update($input);
            if ($user) {
                $user->update($inputUser); 
            }
            if ($keluarga && $request->hub_keluarga_id == 1) {
                $keluarga->update($inputKeluarga); 
            }

            // update alamat beda tabel dari API RAJA ONGKIR
            // if($request->subdistrict_id)
            // {
            //     $this->anggotaKeluargaAlamat->updateOrCreate(
            //         ['anggota_keluarga_id' => $id],
            //         [
            //             'province_id'    => $request->province_id,
            //             'city_id'        => $request->city_id,
            //             'subdistrict_id' => $request->subdistrict_id,
            //             'alamat'         => $request->alamat
            //         ]
            //     );
            // }
            
            \DB::commit();
            $transaction = true;
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }

        if ($transaction) {
            return response()->json(['status' => 'success']);
        }
    }

    public function destroy($id)
    {
        $anggota = $this->anggota
            ->select('anggota_keluarga_id')
            ->findOrFail($id);

        \DB::beginTransaction();
        try {

            User::where('anggota_keluarga_id', $anggota->anggota_keluarga_id)
                ->delete();

            $this->anggota->destroy($id);
            \DB::commit();
            $transaction = true;
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }

        if ($transaction == true) {
            return response()->json(['status' => 'success']);
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

    public function selectOwner($exception=false)
    {
        $checkRole = \helper::checkUserRole('all');
        $isAdmin = $checkRole['isAdmin'];

        $cekUMKM = \DB::table('umkm')
                    ->select('anggota_keluarga_id')
                    ->where('aktif', true)
                    ->where('anggota_keluarga_id', \Auth::user()->anggota_keluarga_id)
                    ->groupBy('anggota_keluarga_id')
                    ->get();

        $isOwner = $cekUMKM != $isAdmin ? true : false;
        
        $data = \DB::table('anggota_keluarga')
            ->select(
                'anggota_keluarga_id',
                'nama'
            )
            ->where('is_active', true)
            ->when(!$exception, function ($query) use ($isOwner){
                $query->when($isOwner, function ($query) {
                    $query->where('anggota_keluarga_id', \Auth::user()->anggota_keluarga_id);
                });
            })
            ->orderBy('nama')
            ->pluck('nama', 'anggota_keluarga_id');

        return $data;
    }

    public function getAlamat($id)
    {
        $anggotaGetAlamat = \DB::table('anggota_keluarga')->where('anggota_keluarga_id', $id)->first();
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

        if($anggotaGetAlamat)
        {
            if (empty($anggotaGetAlamat->subdistrict_id)) {
                $alamat = json_decode($this->rajaOngkir->getSubdistrictDetailById(1579), true);
            } else {
                $alamat = json_decode($this->rajaOngkir->getSubdistrictDetailById($anggotaGetAlamat->subdistrict_id), true);
            }
            $alamat['alamat'] = $anggotaGetAlamat->alamat;
        }

        return $alamat;
    }
}