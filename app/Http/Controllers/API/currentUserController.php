<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\RoleManagement\{ UserRepository };
use Illuminate\Http\Request;
use App\Models\Surat\SuratPermohonan;
use App\Models\Transaksi\HeaderTrxKegiatan;

class currentUserController extends Controller
{   
    public function __construct(UserRepository $_UserRepository)
    {
        $this->user = auth('api')->user();
        $this->userRepo = $_UserRepository;
    }

    public function currentUser(Request $request)
    {
        
        $user = \DB::table('users')
                    ->select('users.user_id','users.username','anggota_keluarga.nama','hub_keluarga.hubungan_kel','users.picture','anggota_keluarga.anggota_keluarga_id','keluarga.rt_id','keluarga.rw_id','keluarga.alamat','keluarga.keluarga_id','anggota_keluarga.is_rt','anggota_keluarga.is_rw', 'umkm.umkm_id')
                    ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                    ->leftJoin('hub_keluarga','hub_keluarga.hub_keluarga_id','anggota_keluarga.hub_keluarga_id')
                    ->leftJoin('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                    ->leftJoin('umkm','anggota_keluarga.anggota_keluarga_id','umkm.anggota_keluarga_id')
                    ->where('users.user_id',$this->user->user_id)
                    ->first();
                    
        $profilPic = \helper::imageLoad('users',$user->picture);

        return response()->json(['status' => 'success','result' => compact('user','profilPic')],200);
    }

    public function transactionStatus(Request $request)
    {
        $wargaLoggedIn = $this->user->anggotaKeluarga;
        $surat = SuratPermohonan::select([
                        'surat_permohonan_id','hal','tgl_approve_rw',
                        'created_at','validasi'
                    ])
                    ->where('tgl_approve_lurah', '=', NULL)
                    ->orderBy('created_at','DESC');

        if($wargaLoggedIn->hub_keluarga_id === 1) // kepala keluarga
        {
            $anggotaKeluargaIds = $wargaLoggedIn->allAnggotaKeluargaId();
            $surat = $surat->whereIn('anggota_keluarga_id', $anggotaKeluargaIds)->get();
        }
        else
        {
            $surat = $surat->where('anggota_keluarga_id', $wargaLoggedIn->anggota_keluarga_id)->get();
        }

        $count_surat = sizeof($surat);

        $transaksi_kegiatan = HeaderTrxKegiatan::select([
                                'header_trx_kegiatan_id','no_pendaftaran',
                                'tgl_approval','created_at'
                                ])
                                ->orderBy('created_at','DESC');

        if($wargaLoggedIn->hub_keluarga_id === 1) // kepala keluarga
        {
            $anggotaKeluargaIds = $wargaLoggedIn->allAnggotaKeluargaId();
            $transaksi_kegiatan = $transaksi_kegiatan->whereIn('anggota_keluarga_id', $anggotaKeluargaIds)->get();
        }
        else
        {
            $transaksi_kegiatan = $transaksi_kegiatan->where('anggota_keluarga_id', $wargaLoggedIn->anggota_keluarga_id)->get();
        }

        $count_transaksi_kegiatan = sizeof($transaksi_kegiatan);                         

        $pengumumanTable = \DB::table('pengumuman')
                          ->select('pengumuman.pengumuman','pengumuman.image1')
                          ->where('rw_id', $wargaLoggedIn->rw_id)
                          ->orderBy('created_at','DESC')
                          ->get();

        $count_pengumuman = sizeof($pengumumanTable);

        $pengumuman = [];

        foreach($pengumumanTable as $key => $val){
            $image1 = $val->image1 ? asset("upload/pengumuman/$val->image1")
                                   : asset('images/noImage.jpg');

            $pushPengumuman['pengumuman'] = $val->pengumuman;
            $pushPengumuman['image1'] = $image1;
            
            array_push($pengumuman,$pushPengumuman);
        }

        return response()->json(['status' => 'success',
                                 'result' => compact('surat','count_surat','transaksi_kegiatan','count_transaksi_kegiatan','pengumuman','count_pengumuman')],200);
    }

    public function changeProfilPic(Request $request)
    {
        $id = $this->user->user_id;
        $user = $this->userRepo->show($id); 
        
        if($request->hasFile('picture')){
            $file = $request->file('picture');
            $fileName = 'user_pic'.rand().'.'.$file->getClientOriginalExtension();
            $oldFile = public_path()."/uploaded_files/users/$user->picture";

            if(file_exists($oldFile)) \File::delete($oldFile);

            $file->move('uploaded_files/users',$fileName);

            \DB::beginTransaction();
            $transaction = false;

            try{

                $user->update(['picture' => $fileName]);
                \DB::commit();
                $transaction = true;

            }catch(\Exception $e){
                
                \DB::rollback();
                throw $e;
            }
        }

        $status = $transaction ? 'success' : 'failed';

        return response()->json(compact('status'));
    }

    public function changePassword(Request $request)
    {
        $input = ['password' => bcrypt($request->password)];

        $id = $this->user->user_id;

        return $this->userRepo->update($input,$id);
    }

    public function deleteProfilPic(Request $request)
    {
        $id = $id = $this->user->user_id;
        $user = $this->userRepo->show($id);
        $oldFile = public_path()."/uploaded_files/users/$user->picture";

        if(file_exists($oldFile)) \File::delete($oldFile);

        \DB::beginTransaction();
        $transaction = false;

        try{    

            $user->update(['picture' => null]);
            \DB::commit();
            $transaction = true;
        }catch(\Exception $e){

            \DB::rollback();
            throw $e;
        }

        $status = $transaction ? 'success' : 'failed';

        return response()->json(compact('status'));
    }
}
