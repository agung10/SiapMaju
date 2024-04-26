<?php

namespace App\Http\Controllers\UMKM;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UMKM\Pemesanan;

class PemesananController extends Controller
{
    public function __construct(Pemesanan $pemesanan)
    {
        $this->pemesanan = $pemesanan;
    }
    
    public function index()
    {
        return view ('UMKM.Pemesanan.index');
    }

    public function show($encryptedId)
    {   
        $pemesananId = \Crypt::decryptString($encryptedId);
        $pemesanan = $this->pemesanan->findOrFail($pemesananId);
        $pemesanan->status = $this->getStatus($pemesanan);

        return view('UMKM.Pemesanan.show', compact('pemesanan'));
    }

    public function dataTables()
    {
        $anggotaKeluarga = \Auth::user()->anggotaKeluarga;
        $owner = ($anggotaKeluarga && $anggotaKeluarga->anggota_keluarga_id != 10)
             ? $anggotaKeluarga->anggota_keluarga_id  // list data by anggota keluarga id
             : null;  // admin & khairul anam can list all data

        $pemesanan = $this->pemesanan->select([
                        'pemesanan.pemesanan_id',
                        'nama_produk',
                        'umkm_produk.image AS image_produk',
                        'umkm.nama AS nama_umkm',
                        'anggota_keluarga.nama AS pembeli',
                        'pemesanan.harga_produk',
                        'pemesanan.jumlah',
                        'pemesanan.total',
                        'pemesanan.created_at',
                        'pemesanan.delivered',
                        'pemesanan.paid',
                        'pemesanan.approved'
                    ])
                    ->join('umkm_produk', 'umkm_produk.umkm_produk_id', 'pemesanan.umkm_produk_id')
                    ->join('umkm', 'umkm.umkm_id', 'pemesanan.umkm_id')
                    ->join('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'pemesanan.anggota_keluarga_id')
                    ->when($owner, function($query, $owner) {
                        $query->where('pemesanan.anggota_keluarga_id', $owner);
                    });

        return \DataTables::of($pemesanan)
        ->addIndexColumn()
        ->addColumn('produk', function($row) {
            $produk = '<span style="width: 250px;">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-80 symbol-sm flex-shrink-0">
                                <img class="" src="'. \helper::imageLoad('umkm', $row->image_produk) .'" alt="photo">
                            </div>
                            <div class="ml-4">
                                <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">
                                    '. $row->nama_produk .'
                                </div>
                                <span class="text-muted font-weight-bold text-hover-primary">
                                    '. $row->nama_umkm .'
                                </span>
                            </div>
                        </div>
                    </span>';

            return $produk;
        })
        ->editColumn('harga_produk', function($row) {
            return 'Rp. '. \helper::number_formats($row->harga_produk, 'view', 0);
        })
        ->editColumn('total', function($row) {
            return 'Rp. '. \helper::number_formats($row->total, 'view', 0);
        })
        ->editColumn('created_at', function($row) {
            return date('d F Y (H:i)', strtotime($row->created_at));
        })
        ->addColumn('status', function($row) {
            return $this->getStatus($row);
        })
        ->addColumn('action', function($row) {
            return view('partials.buttons.cust-datatable',[
                'show' => route('UMKM.Pemesanan.show', \Crypt::encryptString($row->pemesanan_id))
            ]);
        })
        ->rawColumns(['produk', 'status', 'action'])
        ->make(true);
    }

    public function getStatus($record) 
    {
        $status = '';

        switch(true){
                case($record->approved === null):
                    $status = '<span class="badge badge-info">Menunggu konfirmasi penjual</span>';
                    break;

                case($record->approved === false):
                    $status = '<span class="badge badge-danger">Pemesanan ditolak</span>';
                    break;

                case($record->approved === true && $record->paid === false):
                    $status = '<span class="badge badge-warning">Menunggu Pembayaran</span>';
                    break;

                case($record->paid && $record->delivered === false):
                    $status = '<span class="badge badge-warning">Menunggu pengiriman</span>';
                    break;

                case($record->delivered):
                    $status = '<span class="badge badge-success">Selesai</span>';
                    break;
            }

            return $status;
    }
}
