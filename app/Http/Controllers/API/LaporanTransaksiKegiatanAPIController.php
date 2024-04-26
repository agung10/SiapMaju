<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanTransaksiKegiatanAPIController extends Controller
{
    public function searchResult(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $result = \DB::table('header_trx_kegiatan')
                        ->select('header_trx_kegiatan.header_trx_kegiatan_id',
                                 'transaksi.nama_transaksi',
                                 'kat_kegiatan.nama_kat_kegiatan',
                                 'anggota_keluarga.nama',
                                 'header_trx_kegiatan.no_pendaftaran',
                                 'header_trx_kegiatan.no_bukti',
                                 'header_trx_kegiatan.tgl_pendaftaran',
                                 'detail_trx_kegiatan.nama_detail_trx',
                                 'detail_trx_kegiatan.total',
                                 'jenis_transaksi.nama_jenis_transaksi',
                                 'jenis_transaksi.satuan')
                        ->join('transaksi','transaksi.transaksi_id','header_trx_kegiatan.transaksi_id')
                        ->join('kat_kegiatan','kat_kegiatan.kat_kegiatan_id','header_trx_kegiatan.kat_kegiatan_id')
                        ->join('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','header_trx_kegiatan.anggota_keluarga_id')
                        ->join('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                        ->join('detail_trx_kegiatan','detail_trx_kegiatan.header_trx_kegiatan_id','header_trx_kegiatan.header_trx_kegiatan_id')
                        ->join('jenis_transaksi','jenis_transaksi.jenis_transaksi_id','detail_trx_kegiatan.jenis_transaksi_id')
                        ->where('header_trx_kegiatan.kegiatan_id',$request->kegiatan_id)
                        ->whereBetween('header_trx_kegiatan.tgl_pendaftaran',[$startDate,$endDate])
                        ->get();

        return response()->json(compact('result'));
    }
}
