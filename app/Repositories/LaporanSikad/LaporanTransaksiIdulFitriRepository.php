<?php

namespace App\Repositories\LaporanSikad;
use App\Models\Transaksi\HeaderTrxKegiatan;
use App\Models\Transaksi\DetailTrxKegiatan;

class LaporanTransaksiIdulFitriRepository
{
    public function __construct(HeaderTrxKegiatan $_HeaderTrxKegiatan,DetailTrxKegiatan $_DetailTrxKegiatan)
    {
        $this->laporan = $_HeaderTrxKegiatan;
        $this->detailTransaksi = $_DetailTrxKegiatan;
    }

    public function searchLaporan($request)
    {   
        \Session::forget('cartLaporan');

        $startDate = $request->start_date;
        $endDate = $request->end_date;


        $laporan = $this->detailTransaksi
                        ->select('header_trx_kegiatan.header_trx_kegiatan_id','header_trx_kegiatan.tgl_pendaftaran','transaksi.nama_transaksi','kat_kegiatan.nama_kat_kegiatan','detail_trx_kegiatan.nama_detail_trx as nama','anggota_keluarga.alamat','header_trx_kegiatan.no_pendaftaran','header_trx_kegiatan.no_bukti','jenis_transaksi.nama_jenis_transaksi','kegiatan.nama_kegiatan','detail_trx_kegiatan.nilai')
                        ->join('header_trx_kegiatan','header_trx_kegiatan.header_trx_kegiatan_id','detail_trx_kegiatan.header_trx_kegiatan_id')
                        ->join('jenis_transaksi','jenis_transaksi.jenis_transaksi_id','detail_trx_kegiatan.jenis_transaksi_id')
                        ->join('transaksi','transaksi.transaksi_id','header_trx_kegiatan.transaksi_id')
                        ->join('kat_kegiatan','kat_kegiatan.kat_kegiatan_id','header_trx_kegiatan.kat_kegiatan_id')
                        ->join('kegiatan','kegiatan.kat_kegiatan_id','header_trx_kegiatan.kat_kegiatan_id')
                        ->join('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','header_trx_kegiatan.anggota_keluarga_id')
                        ->join('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                        ->where('kat_kegiatan.nama_kat_kegiatan','Idul Fitri')
                        ->whereBetween('header_trx_kegiatan.tgl_pendaftaran',[$startDate,$endDate])
                        ->whereNotNull('header_trx_kegiatan.tgl_approval')
                        ->get();
                        
        $result = view('LaporanSikad.LaporanTransaksiIdulFitri.resultLaporan',compact('laporan'))->render();

        \Request::session()->put('cartLaporan',$laporan);
        \Session::save(); 

        return response()->json(compact('result'));
    }
}