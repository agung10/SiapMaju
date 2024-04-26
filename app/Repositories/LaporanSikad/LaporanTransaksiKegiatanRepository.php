<?php

namespace App\Repositories\LaporanSikad;
use App\Models\Transaksi\HeaderTrxKegiatan;

class LaporanTransaksiKegiatanRepository
{   
    public function __construct(HeaderTrxKegiatan $_HeaderTrxKegiatan)
    {
        $this->laporan = $_HeaderTrxKegiatan;
    }

    public function dataTables()
    {
        $model = $this->laporan
                      ->select('header_trx_kegiatan.header_trx_kegiatan_id','transaksi.nama_transaksi','kat_kegiatan.nama_kat_kegiatan','anggota_keluarga.nama','header_trx_kegiatan.no_pendaftaran','header_trx_kegiatan.no_bukti')
                      ->join('transaksi','transaksi.transaksi_id','header_trx_kegiatan.transaksi_id')
                      ->join('kat_kegiatan','kat_kegiatan.kat_kegiatan_id','header_trx_kegiatan.kat_kegiatan_id')
                      ->join('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','header_trx_kegiatan.anggota_keluarga_id')
                      ->join('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                      ->get();

        return \DataTables::of($model)
                           ->addIndexColumn()
                           ->make(true);

    }

    public function searchLaporan($request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
    
        $laporan = $this->laporan
                        ->select('header_trx_kegiatan.header_trx_kegiatan_id','transaksi.nama_transaksi','kat_kegiatan.nama_kat_kegiatan','anggota_keluarga.nama','header_trx_kegiatan.no_pendaftaran','header_trx_kegiatan.no_bukti')
                        ->join('transaksi','transaksi.transaksi_id','header_trx_kegiatan.transaksi_id')
                        ->join('kat_kegiatan','kat_kegiatan.kat_kegiatan_id','header_trx_kegiatan.kat_kegiatan_id')
                        ->join('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','header_trx_kegiatan.anggota_keluarga_id')
                        ->join('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                        ->whereBetween('header_trx_kegiatan.tgl_pendaftaran',[$startDate,$endDate])
                        ->get();

        $result = view('LaporanSikad.LaporanTransaksiKegiatan.resultLaporan',compact('laporan'))->render();
        
        return response()->json(compact('result'));
    }

    public function searchLaporanByKegiatan($request)
    {   
        \Session::forget('cartLaporan');

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $laporan = $this->laporan
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

        $result = view('LaporanSikad.LaporanTransaksiKegiatan.resultLaporanByKegiatan',compact('laporan'))->render();
        
        // create session for printing purpose

        \Request::session()->put('cartLaporan',$laporan);
        \Session::save(); 

        return response()->json(compact('result'));
    }
}