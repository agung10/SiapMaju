<?php

namespace App\Http\Controllers\API\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanTransaksiKegiatanController extends Controller
{
    public function get(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $userLoggedIn = auth('api')->user()->anggotaKeluarga;
        $result = \DB::table('header_trx_kegiatan')
                        ->select('header_trx_kegiatan.header_trx_kegiatan_id',
                                 'transaksi.nama_transaksi',
                                 'kat_kegiatan.nama_kat_kegiatan',
                                 'anggota_keluarga.nama',
                                 'anggota_keluarga.rt_id',
                                 'anggota_keluarga.rw_id',
                                 'header_trx_kegiatan.no_pendaftaran',
                                 'header_trx_kegiatan.no_bukti',
                                 'header_trx_kegiatan.tgl_pendaftaran',
                                 'header_trx_kegiatan.created_at',
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
                        ->orderBy('header_trx_kegiatan.created_at', 'DESC');

        if($userLoggedIn->is_rt) {
            $result = $result->where('anggota_keluarga.rt_id', $userLoggedIn->rt_id)->get();
        } else if($userLoggedIn->is_rw) {
            $result = $result->where('anggota_keluarga.rw_id', $userLoggedIn->rw_id)->get();
        } else {
            $result = [];
        }

        return response()->json(compact('result'));
    }
}
