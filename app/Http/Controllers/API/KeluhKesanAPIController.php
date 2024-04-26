<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KeluhKesan\{KeluhKesan,BalasKeluhKesan};

class KeluhKesanAPIController extends Controller
{   
    
    public function getKeluhKesan()
    {   
        $userRole = \helper::checkUserRole('all');
        $isWarga  = $userRole['isWarga'];
        $user_id  = auth('api')->user()->user_id;
        $rwUser   = auth('api')->user()->anggotaKeluarga->rw_id;

        $keluhKesans = \DB::table('keluh_kesan')
                          ->select('keluh_kesan_id','keluh_kesan','keluh_kesan.file_image','users.picture','anggota_keluarga.nama','anggota_keluarga.is_rw','anggota_keluarga.is_rt','blok.nama_blok')
                          ->join('users','users.user_id','keluh_kesan.user_id')
                          ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                          ->leftJoin('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                          ->leftJoin('blok','blok.blok_id','keluarga.blok_id')
                          ->when($isWarga,function($query)use($user_id){
                              $query->where('keluh_kesan.user_id',$user_id);
                          })
                          ->where('anggota_keluarga.rw_id', $rwUser)
                          ->orderBy('keluh_kesan.created_at','DESC')
                          ->get();

       

        $result = [];

        foreach($keluhKesans as $key => $val){
            $file_image = $val->file_image ? asset("uploaded_files/keluh_kesan/keluh_kesan/$val->file_image")
                                           : null;

            $keluhKesan['keluh_kesan_id'] = $val->keluh_kesan_id;
            $keluhKesan['keluh_kesan'] = $val->keluh_kesan;
            $keluhKesan['picture'] = \helper::imageLoad('users',$val->picture);
            $keluhKesan['file_image'] = $file_image;
            $keluhKesan['nama'] = $val->nama;
            $keluhKesan['is_rw'] = $val->is_rw;
            $keluhKesan['is_rt'] = $val->is_rt;
            $keluhKesan['nama_blok'] = $val->nama_blok;

            array_push($result,$keluhKesan);
        }

        return response()->json(compact('result'),200);
    }

    public function getBalasKeluhanKesan(Request $request)
    {
        $balas_keluh_kesans =  \DB::table('balas_keluh_kesan')
                                 ->select('balas_keluh_kesan','balas_keluh_kesan.file_image','users.picture','anggota_keluarga.nama','anggota_keluarga.is_rw','anggota_keluarga.is_rt','blok.nama_blok')
                                 ->join('users','users.user_id','balas_keluh_kesan.user_id')
                                 ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                                 ->leftJoin('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                                  ->leftJoin('blok','blok.blok_id','keluarga.blok_id')
                                 ->where('balas_keluh_kesan.keluh_kesan_id',$request->keluh_kesan_id)
                                 ->orderBy('balas_keluh_kesan.created_at','DESC')
                                 ->get();

        $result = [];

        foreach($balas_keluh_kesans as $key => $val){
            $file_image = $val->file_image ? asset("uploaded_files/keluh_kesan/balas_keluh_kesan/$val->file_image")
                                           : null;

            $balasKeluhKesan['balas_keluh_kesan'] = $val->balas_keluh_kesan;
            $balasKeluhKesan['picture'] = \helper::imageLoad('users',$val->picture);
            $balasKeluhKesan['file_image'] = $file_image;
            $balasKeluhKesan['nama'] = $val->nama;
            $balasKeluhKesan['is_rw'] = $val->is_rw;
            $balasKeluhKesan['is_rt'] = $val->is_rt;
            $balasKeluhKesan['nama_blok'] = $val->nama_blok;

            array_push($result,$balasKeluhKesan);
        }

        $status = sizeof($result) > 0 ?  'success' : 'failed';
        
        return response()->json(compact('result','status'));
    } 

    public function sendKeluhKesan(Request $request)
    {
        return $this->createNewData('keluh_kesan',$request);   
    }

    public function balasKeluhKesan(Request $request)
    {
        
        return $this->createNewData('balas_keluh_kesan',$request);  
    }

    public function sendKeluhKesanTemp(Request $request)
    {
        return $this->sendToTemp($request,'keluh_kesan');
    }

    public function sendBalasKeluhKesanTemp(Request $request)
    {
        return $this->sendToTemp($request,'balas_keluh_kesan');
    }   

    public function deleteTempImage(Request $request)
    {   
        $route = \Request::segment(3);

        $folder = $route === 'sendKeluhKesan' ? 'keluh_kesan' : 'balas_keluh_kesan';

        \File::delete(public_path("temp/keluh_kesan/$folder/$request->file_name"));

        return response()->json(['status' => 'success'],200);
    }

    public function sendToTemp($request,$action)
    {
        $file = $request->file('file_image');
        $fileName = $action.'_'.rand().'.'.$file->getClientOriginalExtension();

        $file->move("temp/keluh_kesan/$action",$fileName);

        $status = 'success';

        return response()->json(compact('fileName','status'));
    }

    public function createNewData($action,$request)
    {   
        $transaction = false;
        $input = $request->all();
        $input['user_id'] = auth('api')->user()->user_id;

        \DB::beginTransaction();

        try{    
            if($request->file_image){
                $input['file_image'] = $request->file_image;
                $pathFolder = public_path("uploaded_files/keluh_kesan/$action"); 

                if(!file_exists($pathFolder)) mkdir($pathFolder,0777,true);
                    
                rename(public_path("temp/keluh_kesan/$action/$request->file_image"),public_path("uploaded_files/keluh_kesan/$action/$request->file_image"));
            }

            if($action === 'keluh_kesan'){
                KeluhKesan::create($input);
            }else{
                BalasKeluhKesan::create($input);
            }
            
            \DB::commit();
            $transaction = true;
        }catch(\Exception $e){
            \DB::rollback();
            throw $e;
        }

        $status = $transaction ? 'success' : 'failed';
        $response = $transaction ? 200 : 400;
        
        return response()->json(compact('status'),$response);
    }
}
