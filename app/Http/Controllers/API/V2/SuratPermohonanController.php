<?php

namespace App\Http\Controllers\API\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Surat\Lampiran;
use App\Models\Surat\SuratPermohonan;
use App\Models\Surat\SuratPermohonanLampiran; 
use App\Models\Master\Keluarga\AnggotaKeluarga;
use App\Repositories\WhatsappKeyRepository;

class SuratPermohonanController extends Controller
{
    public function __construct(Lampiran $lampiran, SuratPermohonan $suratPermohonan, AnggotaKeluarga $anggotaKeluarga, SuratPermohonanLampiran $lampiranSurat, WhatsappKeyRepository $whatsapp)
    {
        $this->user            = auth('api')->user();
        $this->lampiran        = $lampiran;
        $this->suratPermohonan = $suratPermohonan;
        $this->anggotaKeluarga = $anggotaKeluarga;
        $this->lampiranSurat   = $lampiranSurat;
        $this->whatsapp        = $whatsapp;
        $this->kepalaKeluarga  = 1;
    }

    public function getPemohon()
    {
        $anggotaKeluarga = $this->user->anggotaKeluarga;
        $keluarga = $this->anggotaKeluarga
                         ->select([
                             'anggota_keluarga.anggota_keluarga_id as id','anggota_keluarga.keluarga_id', 'anggota_keluarga.nama as name', 'hub_keluarga.hubungan_kel', 'anggota_keluarga.rt_id','anggota_keluarga.tgl_lahir', 'anggota_keluarga.is_active', 'users.picture'
                         ])
                         ->where('anggota_keluarga.is_active', true)
                         ->join('hub_keluarga', 'hub_keluarga.hub_keluarga_id', 'anggota_keluarga.hub_keluarga_id')
                         ->join('users', 'users.anggota_keluarga_id', 'anggota_keluarga.anggota_keluarga_id')
                         ->orderBy('hub_keluarga.hub_keluarga_id');

        if($anggotaKeluarga->is_rt)
        {
            $keluarga = $keluarga->where('anggota_keluarga.rt_id', $anggotaKeluarga->rt_id)
                                 ->orderBy('anggota_keluarga.nama')
                                 ->get();
        }
        else if($anggotaKeluarga->hub_keluarga_id === $this->kepalaKeluarga)
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

    public function getLampiran(Request $request)
    {
        $jenisSuratId = $request->jenis_surat;
        $lampiran = $this->lampiran
                         ->where('jenis_surat_id', $jenisSuratId)
                         ->where('status', 1)
                         ->orderBy('nama_lampiran', 'ASC')
                         ->get();

        return response()->json($lampiran);
    }

    public function detail(Request $request)
    {
        $suratPermohonanId = $request->surat_permohonan_id;
        $surat = $this->suratPermohonan
                      ->with([
                        'jenisSurat:jenis_surat_id,jenis_permohonan,kd_surat',
                        'agama:agama_id,nama_agama',
                        'status_pernikahan:status_pernikahan_id,nama_status_pernikahan',
                        'surat_permohonan_lampiran:surat_permohonan_lampiran_id,surat_permohonan_id,upload_lampiran,lampiran_id',
                        'surat_permohonan_lampiran.lampiran:lampiran_id,nama_lampiran'
                      ])
                      ->find($suratPermohonanId);
                      

        return response()->json(['status' => 'success', 'data' => $surat]);
    }

    public function active()
    {
        $wargaLoggedIn =  $this->user->anggotaKeluarga;
        $surat = $this->suratPermohonan->where('tgl_approve_lurah', '=', NULL)->orderBy('created_at', 'desc');
        
        if($wargaLoggedIn->hub_keluarga_id === $this->kepalaKeluarga)
        {
            $anggotaKeluargaIds = $wargaLoggedIn->allAnggotaKeluargaId();
            $result = $surat->whereIn('anggota_keluarga_id', $anggotaKeluargaIds)->get();
        }
        else
        {
            $result = $surat->where('anggota_keluarga_id', $wargaLoggedIn->anggota_keluarga_id)->get();
        }

        foreach($result as $res) {
            $status  = '';
            
            if($res->tgl_approve_lurah === null)
            {
                $status  = 'Sedang diproses kelurahan';
            }
            
            if($res->tgl_approve_rw === null)
            {
                $status = 'Menunggu persetujuan RW';
            }
            
            if($res->tgl_approve_rt === null)
            {
                $status = 'Menunggu persetujuan RT';

            }
            
            $res['status'] = $status;
        }

        return response()->json(['status' => 'success', 'data' => $result]);
    }

    public function finished()
    {
        $surat         = $this->suratPermohonan->where('tgl_approve_lurah', '!=', NULL)->orderBy('updated_at', 'desc');
        $wargaLoggedIn = $this->user->anggotaKeluarga;

        if($wargaLoggedIn->hub_keluarga_id = $this->kepalaKeluarga)
        {
            $anggotaKeluargaIds = $wargaLoggedIn->allAnggotaKeluargaId();
            $result = $surat->whereIn('surat_permohonan.anggota_keluarga_id', $anggotaKeluargaIds)->get();
        }
        else
        {
            $result = $surat->where('surat_permohonan.anggota_keluarga_id', $wargaLoggedIn->anggota_keluarga_id)->get();
        }
        
        return response()->json(['status' => 'success', 'data' => $result]);
    }

    public function waitApproval()
    {
        $surat         = $this->suratPermohonan->where('tgl_approve_kelurahan', '=', NULL)->orderBy('updated_at', 'desc');
        $wargaLoggedIn = $this->user->anggotaKeluarga;

        if($wargaLoggedIn->is_rt)
        {
            $result = $surat->where('tgl_approve_rw', '=', NULL)
                            ->where('tgl_approve_rt', '=', NULL)
                            ->where('surat_permohonan.rt_id', $wargaLoggedIn->rt_id)
                            ->get();
        }
        elseif($wargaLoggedIn->is_rw)
        {
            $result = $surat->where('tgl_approve_rw', '=', NULL)
                            ->where('tgl_approve_rt', '!=', NULL)
                            ->where('surat_permohonan.rw_id', $wargaLoggedIn->rw_id)
                            ->get();
        }
        else
        {
            $result = [];
        }
        
        return response()->json(['status' => 'success', 'data' => $result]);
    }

    public function approve(Request $request)
    {
        \DB::beginTransaction();

        try{

            $surat         = $this->suratPermohonan->find($request->surat_permohonan_id);
            $wargaLoggedIn = $this->user->anggotaKeluarga;

            // approved by rt
            if($wargaLoggedIn->is_rt)
            {
                $surat->update([
                    'petugas_rt_id'  => $wargaLoggedIn->anggota_keluarga_id,
                    'tgl_approve_rt' => date("Y-m-d"),
                    'no_surat'       => $surat->surat_permohonan_id . '/' . 'SP/' . $surat->rt->rt . '/' . date('m') . '/' . date('Y')
                ]);

                $ketuaRW = $this->anggotaKeluarga
                            ->select('anggota_keluarga.mobile')
                            ->where('anggota_keluarga.rw_id', $wargaLoggedIn->rw_id)
                            ->where('anggota_keluarga.is_rw', true)
                            ->first();

                $waktu = \helper::tglIndo($surat->updated_at);
                $waMsg = "[INFORMASI SIAPMAJU], Terdapat permohonan suket/surat keterangan [$surat->hal] atas nama $surat->nama_lengkap pada tanggal $waktu. Mohon untuk memeriksa pada aplikasi";
                $whatsappResponse = $this->whatsapp->send($waMsg, $ketuaRW->mobile);
            
                if(!$whatsappResponse['status']) {
                    return response()->json(['status' => 'failed' , 'message' => 'Gagal mengirm pesan whatsapp']);
                } 
            }
            // approved by rw
            elseif($wargaLoggedIn->is_rw)
            {
                $encryptSuratId  = \Crypt::encryptString($surat->surat_permohonan_id);
                $urlToGenerateQr = route('Kelurahan.SuratMasuk.show', $encryptSuratId);

                $surat->update([
                    'petugas_rw_id'  => $wargaLoggedIn->anggota_keluarga_id,
                    'tgl_approve_rw' => date("Y-m-d"),
                    'validasi'       => $urlToGenerateQr
                ]);

                $wargaPemohon = $this->anggotaKeluarga
                            ->select('anggota_keluarga.mobile')
                            ->where('anggota_keluarga.anggota_keluarga_id', $surat->anggota_keluarga_id)
                            ->first();
                $waktu = \helper::tglIndo($surat->updated_at);
                $waMsg = "[INFORMASI SIAPMAJU], Surat keterangan [$surat->hal] sudah disetujui oleh Ketua RT dan Ketua RW. Mohon untuk melihat status surat pada aplikasi";

                $whatsappResponse = $this->whatsapp->send($waMsg, $wargaPemohon->mobile);
            
                if(!$whatsappResponse['status']) {
                    return response()->json(['status' => 'failed' , 'message' => 'Gagal mengirm pesan whatsapp']);
                } 
            }

        \DB::commit();           
        }catch(\Exception $e){
            \DB::rollback();
            
            return response()->json(['status' => 'failed' , 'message' => $e->getMessage()]);
        }

        return response()->json(['status' => 'success', 'data' => $surat]);
    }

    public function store(Request $request)
    {   
        \DB::beginTransaction();

        try{
            $anggotaKeluarga = $this->anggotaKeluarga->find($request->anggota_keluarga_id);
            $surat = [];
            $surat['jenis_surat_id']       = $request->jenis_surat_id;
            $surat['anggota_keluarga_id']  = $request->anggota_keluarga_id;
            $surat['nama_lengkap']         = $anggotaKeluarga->nama;
            $surat['rt_id']                = $anggotaKeluarga->rt_id;
            $surat['rw_id']                = $anggotaKeluarga->rw_id;
            $surat['lampiran']             = 0;
            $surat['hal']                  = $request->hal;
            $surat['tempat_lahir']         = $request->tempat_lahir;
            $surat['tgl_lahir']            = $request->tgl_lahir;
            $surat['bangsa']               = $request->bangsa;
            $surat['agama_id']             = $request->agama_id;
            $surat['status_pernikahan_id'] = $request->status_pernikahan_id;
            $surat['pekerjaan']            = $request->pekerjaan;
            $surat['no_kk']                = $request->no_kk;
            $surat['no_ktp']               = $request->no_ktp;
            $surat['alamat']               = $anggotaKeluarga->alamat;
            $surat['tgl_permohonan']       = $request->tgl_permohonan;
            $surat['approve_draft']        = true;
            $surat['province_id']          = $anggotaKeluarga->province_id;
            $surat['city_id']              = $anggotaKeluarga->city_id;
            $surat['subdistrict_id']       = $anggotaKeluarga->subdistrict_id;
            $surat['kelurahan_id']         = $anggotaKeluarga->kelurahan_id;

            $storeSurat = $this->suratPermohonan->create($surat);

            $lampiran = $this->lampiran->pluck('lampiran_id');
            $countLampiran = 0;

            foreach($lampiran as $val) {
                if($request->has($val)) {
                    $countLampiran++;
                    $imageName = 'lampiran_' . rand() .'_'. time() . '.' .$request->$val->getClientOriginalExtension();
                    $request->$val->move(public_path('/uploaded_files/surat'), $imageName);

                    $this->lampiranSurat->create([
                        'surat_permohonan_id' => $storeSurat->surat_permohonan_id,
                        'lampiran_id' => $val,
                        'upload_lampiran' => $imageName,
                    ]);
                }
            }

            $storeSurat->update(['lampiran' => $countLampiran]);
            
            $ketuaRT = $this->anggotaKeluarga
                ->select('anggota_keluarga.mobile')
                ->where('anggota_keluarga.rt_id', $anggotaKeluarga->rt_id)
                ->where('anggota_keluarga.is_rt', true)
                ->first();

            $waktu = \helper::tglIndo($storeSurat->updated_at);
            $waMsg = "[INFORMASI SIAPMAJU], Terdapat permohonan suket/surat keterangan [$storeSurat->hal] atas nama $storeSurat->nama_lengkap pada tanggal $waktu. Mohon untuk memeriksa pada aplikasi";

            $whatsappResponse = $this->whatsapp->send($waMsg, $ketuaRT->mobile);
        
            if(!$whatsappResponse['status']) {
                return response()->json(['status' => 'failed' , 'message' => 'Gagal mengirm pesan whatsapp']);
            } 

            \DB::commit();           
        }catch(\Exception $e){
            \DB::rollback();
            
            return response()->json(['status' => 'failed' , 'message' => $e->getMessage()]);
        }

        return response()->json(['status' => 'success']);
    }

    public function update(Request $request)
    {   
        $transaction = false;
        \DB::beginTransaction();

        try{
            $anggotaKeluarga = $this->anggotaKeluarga->find($request->anggota_keluarga_id);
            $surat = $this->suratPermohonan->find($request->surat_permohonan_id);

            $data = [];
            $data['jenis_surat_id']       = $request->jenis_surat_id;
            $data['anggota_keluarga_id']  = $request->anggota_keluarga_id;
            $data['nama_lengkap']         = $anggotaKeluarga->nama;
            $data['rt_id']                = $anggotaKeluarga->rt_id;
            $data['rw_id']                = $anggotaKeluarga->rw_id;
            $data['hal']                  = $request->hal;
            $data['tempat_lahir']         = $request->tempat_lahir;
            $data['tgl_lahir']            = $request->tgl_lahir;
            $data['bangsa']               = $request->bangsa;
            $data['agama_id']             = $request->agama_id;
            $data['status_pernikahan_id'] = $request->status_pernikahan_id;
            $data['pekerjaan']            = $request->pekerjaan;
            $data['no_kk']                = $request->no_kk;
            $data['no_ktp']               = $request->no_ktp;
            $data['alamat']               = $anggotaKeluarga->alamat;
            $data['tgl_permohonan']       = $request->tgl_permohonan;
            $data['province_id']          = $anggotaKeluarga->province_id;
            $data['city_id']              = $anggotaKeluarga->city_id;
            $data['subdistrict_id']       = $anggotaKeluarga->subdistrict_id;
            $data['kelurahan_id']         = $anggotaKeluarga->kelurahan_id;
            $data['status_upload']        = $request->exists('status_upload') ? $request->status_upload : $surat->status_upload;

            $updateSurat = $surat->update($data);

            $lampiran = $this->lampiran->pluck('lampiran_id');
            $countLampiran = count($surat->surat_permohonan_lampiran);

            foreach($lampiran as $val) {
                if($request->has($val)) {
                    $lampiranExist = $this->lampiranSurat
                                             ->where('lampiran_id', $val)
                                             ->where('surat_permohonan_id',$request->surat_permohonan_id)
                                             ->first();
                    if($lampiranExist) {
                        //delete old file
                        $oldFile = public_path()."/uploaded_files/surat/$lampiranExist->upload_lampiran";
                        if(file_exists($oldFile)) \File::delete($oldFile);

                        $lampiranExist->delete();
                        $countLampiran--;
                    }
                    
                    $imageName = 'lampiran_' . rand() .'_'. time() . '.' .$request->$val->getClientOriginalExtension();
                    $request->$val->move(public_path('/uploaded_files/surat'), $imageName);

                    $this->lampiranSurat->create([
                        'surat_permohonan_id' => $surat->surat_permohonan_id,
                        'lampiran_id' => $val,
                        'upload_lampiran' => $imageName,
                    ]);
                    $countLampiran++;
                }
            }

            $surat->update(['lampiran' => $countLampiran]);

            \DB::commit();
            $transaction = true;
           
        }catch(\Exception $e){
            \DB::rollback();
            
            return response()->json(['status' => 'failed' , 'message' => $e->getMessage()]);
        }

        return response()->json(['status' => 'success']);
    }

}