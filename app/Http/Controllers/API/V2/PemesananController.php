<?php

namespace App\Http\Controllers\API\V2;

use App\Models\UMKM\Pemesanan;
use App\Models\UMKM\Umkm;
use App\Models\UMKM\UmkmProduk;
use App\Models\Master\Keluarga\AnggotaKeluarga;
use App\Models\Master\Blok;
use App\Repositories\WhatsappKeyRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    public function __construct(Pemesanan $pemesanan, Umkm $umkm, UmkmProduk $produk, AnggotaKeluarga $anggotaKeluarga, Blok $blok, WhatsappKeyRepository $whatsapp)
    {
        $this->userLoggedIn    = auth('api')->user();
        $this->pemesanan       = $pemesanan;
        $this->umkm            = $umkm;
        $this->produk          = $produk;
        $this->whatsapp        = $whatsapp;
        $this->anggotaKeluarga = $anggotaKeluarga;
        $this->blok            = $blok;
    }

    public function listOrderAsOwner()
    {
        $pemesanan     = [];
        $wargaLoggedIn = $this->userLoggedIn->anggotaKeluarga;
        $umkm          = $this->umkm->where('anggota_keluarga_id', $wargaLoggedIn->anggota_keluarga_id)->first();

        if($umkm) {
            $pemesanan = $this->pemesanan
                              ->where('umkm_id', $umkm->umkm_id)
                              ->with('anggota_keluarga:anggota_keluarga_id,nama')
                              ->with('produk:umkm_produk_id,image')
                              ->orderBy('delivered')
                              ->orderBy('created_at', 'DESC')
                              ->get();
        }

        

        return response()->json($pemesanan);
    }

    public function listOrderAsBuyer()
    {
        $wargaLoggedIn = $this->userLoggedIn->anggotaKeluarga;
        $pemesanan     = $this->pemesanan
                              ->where('anggota_keluarga_id', $wargaLoggedIn->anggota_keluarga_id)
                              ->with('anggota_keluarga:anggota_keluarga_id,nama')
                              ->with('produk:umkm_produk_id,image')
                              ->orderBy('delivered')
                              ->orderBy('created_at', 'DESC')
                              ->get();

        return response()->json($pemesanan);
    }

    public function order(Request $request)
    {
        \DB::beginTransaction();

        try{
            $inputPemesanan = [];
            $response = ['status' => false, 'msg' => ''];
            $produk   = $this->produk->find($request->umkm_produk_id);

            if(is_null($produk) || $produk->stok < $request->jumlah)
            {
                $response['msg'] = 'Stok tidak mencukupi';
                
                return $response;
            }

            $inputPemesanan['umkm_id']             = $produk->umkm_id;
            $inputPemesanan['umkm_produk_id']      = $produk->umkm_produk_id;
            $inputPemesanan['anggota_keluarga_id'] = $request->anggota_keluarga_id;
            $inputPemesanan['nama_produk']         = $produk->nama;
            $inputPemesanan['harga_produk']        = $produk->harga;
            $inputPemesanan['jumlah']              = $request->jumlah;
            $inputPemesanan['total']               = $produk->harga * $request->jumlah;

            $pemesanan = $this->pemesanan->create($inputPemesanan);
            $produk->update(['stok' => ($produk->stok - $request->jumlah)]);

            /** Send whatsapp notification **/
            $buyer = $this->anggotaKeluarga->find($request->anggota_keluarga_id);
            $seller = $this->anggotaKeluarga->find($pemesanan->umkm->anggota_keluarga_id);
            $blokBuyer = $this->blok->find($buyer->keluarga->blok_id);

            // example msg: "Maryanto Tasmin - Blok A8/12 memesan 2 Donnie Brown Bag, Silahkan konfirmasi untuk memproses pesanan  melalui aplikasi SiapMaju"
            $msgNewOrder = $buyer->nama. " - Blok ". $blokBuyer->nama_blok ." memesan ". $request->jumlah ." ". $produk->nama .", Silahkan konfirmasi untuk memproses pesanan  melalui aplikasi SiapMaju.";
            
            $whatsappResponse = $this->whatsapp->send($msgNewOrder, $seller->mobile);

            if(!$whatsappResponse['status']) {
                $response['msg'] = 'Gagal mengirm pesan whatsapp';
                
                return $response;
            }

            $response['status'] = true;
            $response['msg']    = 'Pesanan berhasil dibuat, Mohon tunggu konfirmasi dari penjual untuk melanjutkan pembayaran';

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            \Log::error($e);

            $response['msg'] = $e->getMessage();
        }

        return response()->json($response);
    }

    public function cancelOrder(Request $request)
    {
        \DB::beginTransaction();

        try{
            $response  = ['status' => false, 'msg' => ''];
            $pemesanan = $this->pemesanan->find($request->pemesanan_id);

            if(is_null($pemesanan))
            {
                $response['msg'] = 'Pesanan tidak dapat ditemukan';
                
                return $response;
            }

            $produk = $this->produk->find($pemesanan->umkm_produk_id);
            $produk->update(['stok' => ($produk->stok + $pemesanan->jumlah)]);

            $pemesanan->delete();

            $response['status'] = true;
            $response['msg']    = 'Pesanan berhasil dibatalkan';

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            \Log::error($e);

            $response['msg'] = $e->getMessage();
        }

        return response()->json($response);
    }

    public function confirmOrder(Request $request)
    {
        \DB::beginTransaction();

        try{
            $response  = ['status' => false, 'msg' => ''];
            $pemesanan = $this->pemesanan->find($request->pemesanan_id);

            if(is_null($pemesanan))
            {
                $response['msg'] = 'Pesanan tidak dapat ditemukan';
                
                return $response;
            }

            $pemesanan->update(['approved' => $request->approved]);

            if($request->approved === false) {
                $produk = $this->produk->find($pemesanan->umkm_produk_id);
                $produk->update(['stok' => ($produk->stok + $pemesanan->jumlah)]);
            }
            else 
            {
                /** Send whatsapp notification **/
                $buyer = $this->anggotaKeluarga->find($pemesanan->anggota_keluarga_id);

                // example msg: "Pesanan Donnie Brown Bag Anda telah dikonfirmasi penjual, Silahkan lakukan pembayaran dan konfirmasi pembayaran melalui aplikasi SiapMaju untuk melanjutkan pesanan."
                $msgOrderConfirmed = "Pesanan ". $pemesanan->nama_produk. " Anda telah dikonfirmasi penjual, Silahkan lakukan pembayaran dan konfirmasi pembayaran melalui aplikasi SiapMaju untuk melanjutkan pesanan.";
                
                $whatsappResponse = $this->whatsapp->send($msgOrderConfirmed, $buyer->mobile);

                if(!$whatsappResponse['status']) {
                    $response['msg'] = 'Gagal mengirm pesan whatsapp';
                    
                    return $response;
                }
            }

            $response['status'] = true;
            $response['msg']    = 'Pesanan telah '. ($request->approved ? 'diterima' : 'ditolak');

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            \Log::error($e);

            $response['msg'] = $e->getMessage();
        }

        return response()->json($response);
    }

    public function payOrder(Request $request)
    {
        \DB::beginTransaction();

        try{
            $response  = ['status' => false, 'msg' => ''];
            $pemesanan = $this->pemesanan->find($request->pemesanan_id);
            $imageName = null;

            if(is_null($pemesanan))
            {
                $response['msg'] = 'Pesanan tidak dapat ditemukan';
                
                return $response;
            }

            if($request->has('bukti_pembayaran_')) {
                $imageName = 'bukti_pembayaran_' . rand() .'_'. time() . '.' .$request->bukti_pembayaran->getClientOriginalExtension();
                $request->bukti_pembayaran->move(public_path('/uploaded_files/pemesanan'), $imageName);
            }

            $pemesanan->update([
                'paid' => true,
                'bukti_pembayaran' => $imageName
            ]);

            /** Send whatsapp notification **/
            $buyer = $this->anggotaKeluarga->find($pemesanan->anggota_keluarga_id);
            $seller = $this->anggotaKeluarga->find($pemesanan->umkm->anggota_keluarga_id);
            $blokBuyer = $this->blok->find($buyer->keluarga->blok_id);

            // example msg: "Maryanto Tasmin - Blok A8/12 telah melakukan konfirmasi pembayaran untuk produk Donnie Brown Bag, Silahkan proses pesanan dan kirimkan pesanan"
            $msgOrderPaid = $buyer->nama ." - Blok ". $blokBuyer->nama_blok . " telah melakukan konfirmasi pembayaran untuk produk ". $pemesanan->nama_produk. ", Silahkan proses pesanan dan kirimkan pesanan";
            
            $whatsappResponse = $this->whatsapp->send($msgOrderPaid, $seller->mobile);

            if(!$whatsappResponse['status']) {
                $response['msg'] = 'Gagal mengirm pesan whatsapp';
                
                return $response;
            }

            $response['status'] = true;
            $response['msg']    = 'Pesanan berhasil dibayar';

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            \Log::error($e);

            $response['msg'] = $e->getMessage();
        }

        return response()->json($response);
    }

    public function orderDelivered(Request $request)
    {
        \DB::beginTransaction();

        try{
            $response  = ['status' => false, 'msg' => ''];
            $pemesanan = $this->pemesanan->find($request->pemesanan_id);

            if(is_null($pemesanan))
            {
                $response['msg'] = 'Pesanan tidak dapat ditemukan';
                
                return $response;
            }

            $pemesanan->update(['delivered' => true]);

            $response['status'] = true;
            $response['msg']    = 'Pesanan telah selesai';

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            \Log::error($e);

            $response['msg'] = $e->getMessage();
        }

        return response()->json($response);
    }
}
