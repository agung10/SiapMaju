<?php

namespace App\Http\Controllers\API\V2;

use App\Models\Master\Keluarga\Keluarga;
use App\Models\Master\Keluarga\AnggotaKeluarga;
use App\Models\Master\Blok;
use App\User;
use App\Models\RoleManagement\Role;
use App\Models\RoleManagement\UserRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\WhatsappKeyRepository;

class KeluargaController extends Controller
{
    public function __construct(Keluarga $keluarga, AnggotaKeluarga $anggotaKeluarga, User $user, Blok $blok, WhatsappKeyRepository $whatsapp, Role $role, UserRole $userRole)
    {
        $this->userLoggedIn    = auth('api')->user();
        $this->keluarga        = $keluarga;
        $this->anggotaKeluarga = $anggotaKeluarga;
        $this->blok            = $blok;
        $this->user            = $user;
        $this->role            = $role;
        $this->userRole        = $userRole;
        $this->whatsapp        = $whatsapp;
        $this->kepalaKeluarga  = 1;
    }

    public function getAnggotaKeluarga()
    {
        $anggotaKeluarga = $this->userLoggedIn->anggotaKeluarga;
        $keluarga = $this->anggotaKeluarga
                         ->select([
                             'anggota_keluarga.anggota_keluarga_id as id','anggota_keluarga.keluarga_id', 'anggota_keluarga.nama as name', 'hub_keluarga.hubungan_kel', 'anggota_keluarga.rt_id','anggota_keluarga.tgl_lahir', 'anggota_keluarga.is_active', 'users.picture'
                         ])
                         ->where('anggota_keluarga.is_active', true)
                         ->join('hub_keluarga', 'hub_keluarga.hub_keluarga_id', 'anggota_keluarga.hub_keluarga_id')
                         ->join('users', 'users.anggota_keluarga_id', 'anggota_keluarga.anggota_keluarga_id')
                         ->orderBy('hub_keluarga.hub_keluarga_id');

        if($anggotaKeluarga->hub_keluarga_id === $this->kepalaKeluarga)
        {
            $keluarga = $keluarga->where('anggota_keluarga.keluarga_id', $anggotaKeluarga->keluarga_id)->get();
        } 
        else 
        {
            $keluarga = $keluarga->where('anggota_keluarga.anggota_keluarga_id', $anggotaKeluarga->anggota_keluarga_id)->get();
        }

        foreach($keluarga as $v){
            $v['picture_src'] = \helper::imageLoad('users', $v->picture);
        }

        return response()->json($keluarga);
    }

    public function anggotaKeluargaDetail(Request $request)
    {
        $anggotaKeluagaId      = $request->anggota_keluarga_id;
        $anggotaKeluargaDetail = $this->anggotaKeluarga->find($anggotaKeluagaId);

        return response()->json($anggotaKeluargaDetail);
    }

    public function getBlok()
    {
        $anggotaKeluarga = $this->userLoggedIn->anggotaKeluarga;
        $blok = $this->blok
                     ->select('blok.nama_blok as label','blok.blok_id as value','blok.long','blok.lang')
                     ->where('long', '!=', NULL)
                     ->where('long', '!=', 0)
                     ->where('lang', '!=', NULL)
                     ->where('lang', '!=', 0)
                     ->where('rw_id', $anggotaKeluarga->rw_id)
                     ->orderBy('nama_blok')
                     ->get();

        return response()->json(['result' => $blok]);
    }

    public function addAnggotaKeluarga(Request $request)
    {
        \DB::beginTransaction();

        try{
            $response = ['status' => false, 'msg' => ''];
            $emailExist     = $this->anggotaKeluarga->where('email', $request->email)->first();
            $kepalaKeluarga = $this->anggotaKeluarga
                                   ->where('keluarga_id', $request->keluarga_id)
                                   ->where('hub_keluarga_id', 1) // id hubungan keluarga sebagai kepala keluarga
                                   ->first();
            if($emailExist) {
                $response['msg'] = 'Email telah digunakan pengguna lain';
                
                return $response;
            }

            if($kepalaKeluarga === null) {
                $response['msg'] = 'Kepala keluarga tidak ditemukan';
                
                return $response;
            }
            
            $anggotaKeluarga = $this->anggotaKeluarga->create([
                'keluarga_id'     => $request->keluarga_id,
                'hub_keluarga_id' => $request->hub_keluarga_id,
                'nama'            => $request->nama,
                'alamat'          => $request->alamat,
                'mobile'          => $request->mobile,
                'email'           => $request->email,
                'jenis_kelamin'   => $request->jenis_kelamin,
                'tgl_lahir'       => $request->tgl_lahir,
                'password'        => bcrypt(strtolower($request->password)),
                'province_id'     => $kepalaKeluarga->province_id,
                'city_id'         => $kepalaKeluarga->city_id,
                'subdistrict_id'  => $kepalaKeluarga->subdistrict_id,
                'kelurahan_id'    => $kepalaKeluarga->kelurahan_id,
                'rw_id'           => $kepalaKeluarga->rw_id,
                'rt_id'           => $kepalaKeluarga->rt_id,
                'is_active'       => false,
                'is_rt'           => false,
                'is_rw'           => false,
                'have_umkm'       => false
            ]);

            $user = $this->user->create([
                'username' => $request->email,
                'email'    => $request->email,
                'password' => bcrypt($request->password),
                'is_admin' => false,
                'anggota_keluarga_id' => $anggotaKeluarga->anggota_keluarga_id
            ]);

            /** Send whatsapp notification **/
            $ketuaRT = $this->anggotaKeluarga
                ->select('anggota_keluarga.mobile')
                ->where('anggota_keluarga.rt_id', $kepalaKeluarga->rt_id)
                ->where('anggota_keluarga.is_rt', true)
                ->first();

            $msgToRT = "Mohon persetujuannya untuk penambahan anggota keluarga dari ($kepalaKeluarga->nama) atas nama $request->nama, melalui aplikasi SiapMaju.";
            
            $whatsappResponse = $this->whatsapp->send($msgToRT, $ketuaRT->mobile);

            if(!$whatsappResponse['status']) {
                $response['msg'] = 'Gagal mengirm pesan whatsapp';
                
                return $response;
            } 

            $msgToWargaBaru = "Proses penambahan anggota baru yang anda ajukan telah berhasil, silahkan menunggu Ketua RT setempat untuk melakukan approve data.";

            $whatsappResponse = $this->whatsapp->send($msgToWargaBaru, $kepalaKeluarga->mobile);

            if(!$whatsappResponse['status']) {
                $response['msg'] = 'Gagal mengirm pesan whatsapp';
                
                return $response;
            } 

            /** Create Role **/
            $role = $this->role
                ->select('role_id')
                ->where('role_name', 'Warga')
                ->first();

            $userRole = $this->userRole->create([
                'user_id' => $user->user_id,
                'role_id' => $role->role_id,
            ]);

            $response['status'] = true;
            $response['msg']    = 'Anggota keluarga berhasil ditambahkan';

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            \Log::error($e);

            $response['msg'] = $e->getMessage();
        }

        return response()->json($response);
    }

    public function updateAnggotaKeluarga(Request $request)
    {
        \DB::beginTransaction();

        try{
            $response = [
                'status' => false, 
                'msg'    => '',
                'result' => null
            ];
            $anggotaKeluargaDetail = $this->anggotaKeluarga->find($request->anggota_keluarga_id);
            $user                  = $this->user->where('anggota_keluarga_id', $request->anggota_keluarga_id)->first();
            $emailExist            = $this->anggotaKeluarga
                                          ->where('anggota_keluarga_id', '!=', $request->anggota_keluarga_id)
                                          ->where('email', $request->email)
                                          ->first();

            if($anggotaKeluargaDetail === null || $user === null) {
                $response['msg'] = 'Anggota Keluarga tidak ditemukan';
                
                return $response;
            }

            if($emailExist) {
                $response['msg'] = 'Email telah digunakan pengguna lain';
                
                return $response;
            }

            $anggotaKeluargaDetail->update($request->all());
            
            $user->update([
                'username' => $request->email,
                'email' => $request->email,
            ]);

            $currentUser = $this->user
                    ->select('users.user_id','users.username','anggota_keluarga.nama','hub_keluarga.hubungan_kel','users.picture','anggota_keluarga.anggota_keluarga_id','keluarga.rt_id','keluarga.rw_id','keluarga.alamat','keluarga.keluarga_id','anggota_keluarga.is_rt','anggota_keluarga.is_rw')
                    ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                    ->leftJoin('hub_keluarga','hub_keluarga.hub_keluarga_id','anggota_keluarga.hub_keluarga_id')
                    ->leftJoin('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                    ->where('users.user_id',$this->userLoggedIn->user_id)
                    ->first();
                    
            $profilPic = \helper::imageLoad('users',$currentUser->picture);

            $response['status'] = true;
            $response['msg']    = 'Anggota keluarga berhasil diupdate';
            $response['result'] = ['user' => $currentUser, 'profilPic' => $profilPic];

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            \Log::error($e);

            $response['msg'] = $e->getMessage();
        }

        return response()->json($response);
    }
}
