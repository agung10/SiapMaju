<?php

namespace App\Http\Controllers\API\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi\HeaderTrxKegiatan;
use App\Models\Transaksi\DetailTrxKegiatan;
use App\Models\Master\Keluarga\AnggotaKeluarga;
use App\Repositories\ApprovalTransaksiRepository;

class TagihanController extends Controller
{
    public function __construct(HeaderTrxKegiatan $headerTrx, DetailTrxKegiatan $detailTrx, AnggotaKeluarga $anggotaKeluarga, ApprovalTransaksiRepository $approvalRepo)
    {
        $this->user            = auth('api')->user();
        $this->headerTrx       = $headerTrx;
        $this->detailTrx       = $detailTrx;
        $this->anggotaKeluarga = $anggotaKeluarga;
        $this->approvalRepo    = $approvalRepo;
        $this->kepalaKeluarga  = 1;
    }

    public function pending()
    {
        $wargaLoggedIn  =  $this->user->anggotaKeluarga;
        $kepalaKeluarga = $this->anggotaKeluarga
            ->where('keluarga_id', $wargaLoggedIn->keluarga_id)
            ->where('hub_keluarga_id', $this->kepalaKeluarga)
            ->first();
        $tagihan = $this->headerTrx
            ->nonDKM()
            ->with([
                'katKegiatan:kat_kegiatan_id,nama_kat_kegiatan',
                'kegiatan:kegiatan_id,nama_kegiatan'
            ])
            ->where('tgl_approval', '=', NULL)
            ->orderBy('created_at', 'desc')
            ->where('anggota_keluarga_id', $kepalaKeluarga->anggota_keluarga_id)
            ->get();

        return response()->json(['status' => 'success', 'data' => $tagihan]);
    }

    public function finished()
    {
        $wargaLoggedIn  =  $this->user->anggotaKeluarga;
        $kepalaKeluarga = $this->anggotaKeluarga
            ->where('keluarga_id', $wargaLoggedIn->keluarga_id)
            ->where('hub_keluarga_id', $this->kepalaKeluarga)
            ->first();
        $tagihan = $this->headerTrx
            ->nonDKM()
            ->with([
                'katKegiatan:kat_kegiatan_id,nama_kat_kegiatan',
                'kegiatan:kegiatan_id,nama_kegiatan'
            ])
            ->where('tgl_approval', '!=', NULL)
            ->orderBy('created_at', 'desc')
            ->where('anggota_keluarga_id', $kepalaKeluarga->anggota_keluarga_id)
            ->get();

        return response()->json(['status' => 'success', 'data' => $tagihan]);
    }

    public function waitApproval()
    {
        $wargaLoggedIn = $this->user->anggotaKeluarga;

        $tagihan = $this->headerTrx
            ->nonDKM()
            ->with([
                'katKegiatan:kat_kegiatan_id,nama_kat_kegiatan',
                'kegiatan:kegiatan_id,nama_kegiatan',
                'anggotaKeluarga:anggota_keluarga_id,nama'
            ])
            ->where('tgl_approval', '=', NULL)
            ->orderBy('created_at', 'desc');

        if ($wargaLoggedIn->is_rt) {
            $result = $tagihan->whereIn('anggota_keluarga_id', function ($q) use ($wargaLoggedIn) {
                $q->select('anggota_keluarga_id')
                    ->from('anggota_keluarga')
                    ->where('rt_id', $wargaLoggedIn->rt_id);
            })
                ->get();
        } else {
            $result = [];
        }

        return response()->json(['status' => 'success', 'data' => $result]);
    }

    public function approve(Request $request)
    {
        $transaction = false;
        $response = ['status' => false, 'msg' => ''];

        \DB::beginTransaction();
        try {

            $userId  = $this->user->user_id;
            $tagihan = $this->headerTrx
                ->select('*')
                ->leftJoin('transaksi', 'transaksi.transaksi_id', 'header_trx_kegiatan.transaksi_id', 'kegiatan.nama_kegiatan')
                ->leftJoin('kat_kegiatan', 'kat_kegiatan.kat_kegiatan_id', 'header_trx_kegiatan.kat_kegiatan_id')
                ->join('kegiatan', 'kegiatan.kegiatan_id', 'header_trx_kegiatan.kegiatan_id')
                ->join('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'header_trx_kegiatan.anggota_keluarga_id')
                ->join('keluarga', 'keluarga.keluarga_id', 'anggota_keluarga.keluarga_id')
                ->join('rt', 'rt.rt_id', 'keluarga.rt_id')
                ->findOrFail($request->header_trx_kegiatan_id);

            $noBukti = date('Ymdhis') . '/' . $tagihan->kode_kat . '/' . $tagihan->nama_transaksi . '/' . date('Y-m', strtotime($tagihan->tgl_pendaftaran));

            // $url = 'https://dev2.kamarkerja.com:3333/message/text';
            // $headers = @get_headers($url);

            // // if cant reach url
            // if(!$headers) {
            //     $response['msg'] = 'Tidak dapat menghubungkan server';

            //     return response()->json($response);
            // }

            // $whatsappKey = \DB::table('whatsapp_key')
            //     ->select('whatsapp_key')
            //     ->first()
            //     ->whatsapp_key ?? null;

            // if (!$headers) {
            //     $response['msg'] = 'No Whatsapp belum disandingkan';

            //     return response()->json($response);
            // }

            // $mobile = '62'.substr($tagihan->mobile,1);

            // $kegiatan =  $tagihan->nama_kegiatan;
            // $time = date('Y-m-d, H:i:s');
            // $createdDate = explode('/',\helper::datePrint(date('Y-m-d',strtotime($tagihan->tgl_pendaftaran))));

            // $pengurus = $tagihan->rt;

            // $whatsappMsg = "Terimakasih atas Pembayaran $kegiatan dan  sudah kami terima dengan No Transaksi $noBukti, untuk bulan $createdDate[1] $createdDate[2]. Pengurus $pengurus [SiapMaju-$time]";

            $input = [
                'no_bukti' => $noBukti,
                'tgl_approval' => date('Y-m-d'),
                'user_approval' => $userId,
                'user_updated_id' => $userId,
            ];

            $tagihan->update($input);

            $response['status'] = true;
            $response['msg']    = 'Tagihan telah disetujui';

            \DB::commit();
            $transaction = true;
        } catch (\Exception $e) {
            \DB::rollback();

            $response['msg'] = $e->getMessage();

            return response()->json($response);
        }

        // if($transaction) {
        //     \Http::post("$url?key=$whatsappKey",[
        //         'id' => $mobile,
        //         'message' => $whatsappMsg
        //     ]);
        // }

        return response()->json($response);
    }

    public function delete(Request $request)
    {
        $response = ['status' => false, 'msg' => ''];

        \DB::beginTransaction();
        try {
            $tagihan = $this->headerTrx
                ->where('tgl_approval', null)
                ->where('header_trx_kegiatan_id', $request->header_trx_kegiatan_id)
                ->first();
            if ($tagihan) {
                foreach ($tagihan->detail as $detail) {
                    $detail->delete();
                }

                if ($tagihan->bukti_pembayaran) {
                    $buktiPembayaran = public_path() . "/upload/transaksi_kegiatan/$tagihan->bukti_pembayaran";
                    if (file_exists($buktiPembayaran)) \File::delete($buktiPembayaran);
                }

                $tagihan->delete();
            }

            $response['status'] = true;
            $response['msg']    = 'Tagihan berhasil dihapus';

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();

            $response['msg'] = $e->getMessage();
        }

        return response()->json($response);
    }
}
