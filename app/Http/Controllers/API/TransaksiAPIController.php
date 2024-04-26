<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi\HeaderTrxKegiatan;
use App\Models\Transaksi\DetailTrxKegiatan;

class TransaksiAPIController extends Controller
{
    public function detail($id)
    {
        $trxKegiatan = HeaderTrxKegiatan::select('header_trx_kegiatan.header_trx_kegiatan_id',
                                      'anggota_keluarga.nama AS nama_lengkap',
                                      'transaksi.nama_transaksi',
                                      'kat_kegiatan.nama_kat_kegiatan',
                                      'kegiatan.nama_kegiatan',
                                      'header_trx_kegiatan.no_pendaftaran',
                                      'header_trx_kegiatan.tgl_pendaftaran',
                                      'header_trx_kegiatan.no_bukti',
                                      'header_trx_kegiatan.tgl_approval',
                                      'header_trx_kegiatan.bukti_pembayaran',
                                      )
                            ->join('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','header_trx_kegiatan.anggota_keluarga_id')
                            ->join('transaksi','transaksi.transaksi_id','header_trx_kegiatan.transaksi_id')
                            ->join('kat_kegiatan','kat_kegiatan.kat_kegiatan_id','header_trx_kegiatan.kat_kegiatan_id')
                            ->join('kegiatan','kegiatan.kegiatan_id','header_trx_kegiatan.kegiatan_id')
                            ->where('header_trx_kegiatan.header_trx_kegiatan_id',$id)
                            ->first();

        $detailTrxKegiatan = DetailTrxKegiatan::select([
                                                'detail_trx_kegiatan.nama_detail_trx', 'jenis_transaksi.nama_jenis_transaksi', 'detail_trx_kegiatan.nilai', 'detail_trx_kegiatan.jumlah'
                                              ])
                                              ->where('header_trx_kegiatan_id',$id)
                                              ->join('jenis_transaksi', 'jenis_transaksi.jenis_transaksi_id', 'detail_trx_kegiatan.jenis_transaksi_id')
                                              ->get();
        $srcBuktiPembayaran = \helper::loadImgUpload('transaksi_kegiatan', $trxKegiatan->bukti_pembayaran);

        $trxKegiatan->setAttribute('bukti_pembayaran', $srcBuktiPembayaran);
        $trxKegiatan->setAttribute('detail_trx', $detailTrxKegiatan);

        return response()->json($trxKegiatan);
    }
}
