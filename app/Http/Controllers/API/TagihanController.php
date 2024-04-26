<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi\{HeaderTrxKegiatan,DetailTrxKegiatan};

class TagihanController extends Controller
{
    public function storeTagihan(Request $request)
    {  
       $inputHeader = $request->only('transaksi_id',
                                     'kat_kegiatan_id',
                                     'kegiatan_id',
                                     'anggota_keluarga_id',
                                     'tgl_pendaftaran',
                                     'transaksi_id',
                                     'no_pendaftaran',
                                     'bukti_pembayaran');

        $storeHeader = $this->storeHeader($inputHeader);

        $inputDetail = [
            'nilai' => $request->nilai,
            'jumlah' => $request->jumlah,
            'total' => $request->total,
            'nama_detail_trx' => $request->nama_detail_trx,
            'jenis_transaksi_id' => $request->jenis_transaksi_id,
            'header_trx_kegiatan_id' =>  $storeHeader->header_trx_kegiatan_id
        ];

        if(!$storeHeader) return;

        $storeDetail = $this->storeDetail($inputDetail);
        $status = $storeDetail ? 'success' : 'failed';
        $response = $storeDetail ? 200 : 400;

        return response()->json(compact('status'),$response);
    }

    public function storeTagihanDKM(Request $request)
    {
        $inputHeader = $request->only('transaksi_id',
        'kat_kegiatan_id',
        'kegiatan_id',
        'anggota_keluarga_id',
        'tgl_pendaftaran',
        'transaksi_id',
        'no_pendaftaran',
        'bukti_pembayaran');

        $storeHeader = $this->storeHeader($inputHeader);

        return response()->json(['status' => 'success',
                                 'header_trx_kegiatan_id' => $storeHeader->header_trx_kegiatan_id]);
    }

    public function storeDetailTagihanDKM(Request $request)
    {
        $store = $this->storeDetail($request->all());

        $res = $store ? 200 : 100;
        $status = $store ? 'success' : 'failed';

        return response()->json(compact('res','status'));
    }

    public function storeHeader($inputHeader)
    {   
        $transaction = false;

        \DB::beginTransaction();

        try{
            $store = HeaderTrxKegiatan::create($inputHeader);

            \DB::commit();
            $transaction = true;
        }catch(\Exception $e){

            \DB::rollback();
            throw $e;
        }

        if(!$transaction) return false;

        if($inputHeader['bukti_pembayaran']){
            $file = $inputHeader['bukti_pembayaran'];
            $folderExist = is_dir(public_path("upload/transaksi_kegiatan"));
            if(!$folderExist) mkdir(public_path("upload/transaksi_kegiatan"));
            rename(public_path("temp/bukti_pembayaran/$file"),public_path("upload/transaksi_kegiatan/$file"));
        }

        return $store;
    }

    public function storeDetail($inputDetail)
    {
        $transaction = false;

        \DB::beginTransaction();

        try{
            $store = DetailTrxKegiatan::create($inputDetail);

            \DB::commit();
            $transaction = true;

        }catch(\Exception $e){

            \DB::rollback();
            throw $e;
        }

        return $transaction;
    }

    
}
