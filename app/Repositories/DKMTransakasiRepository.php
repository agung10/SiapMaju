<?php

namespace App\Repositories;
use App\Models\Transaksi\{HeaderTrxKegiatan,DetailTrxKegiatan};
use App\Repositories\HeaderTransaksiRepository;
use App\User;

class DKMTransakasiRepository
{
    public function __construct(HeaderTrxKegiatan $_HeaderTrxKegiatan, HeaderTransaksiRepository $headerTrxRepo, User $user)
    {
        $this->transaksi     = $_HeaderTrxKegiatan;
        $this->headerTrxRepo = $headerTrxRepo;
        $this->user          = $user;
    }

    public function dataTables($request)
    {   
        $datatableButtons = method_exists(new $this->transaksi, 'datatableButtons') ? $this->transaksi->datatableButtons() : ['show', 'edit', 'destroy'];

        $logedUSer = \DB::table('users')
                         ->select('anggota_keluarga.is_rt','anggota_keluarga.is_rw','users.is_admin','keluarga.rt_id','keluarga.rw_id',
                                  'anggota_keluarga.anggota_keluarga_id')
                         ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                         ->leftJoin('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                         ->where('user_id',\Auth::user()->user_id)
                         ->first();

        $isRt = $logedUSer->is_rt == true;
        $isRw = $logedUSer->is_rw == true;
        $isAdmin = $logedUSer->is_admin == true;
        $isWarga = $isRt != true && $isRw != true && $isAdmin != true;

        $model = \DB::table('header_trx_kegiatan')
                     ->select('header_trx_kegiatan.header_trx_kegiatan_id','transaksi.nama_transaksi','kat_kegiatan.nama_kat_kegiatan','anggota_keluarga.nama','header_trx_kegiatan.no_pendaftaran','header_trx_kegiatan.no_bukti','header_trx_kegiatan.tgl_approval', 'header_trx_kegiatan.tgl_pendaftaran', 'header_trx_kegiatan.updated_at','blok.nama_blok')
                     ->join('transaksi','transaksi.transaksi_id','header_trx_kegiatan.transaksi_id')
                     ->join('kat_kegiatan','kat_kegiatan.kat_kegiatan_id','header_trx_kegiatan.kat_kegiatan_id')
                     ->join('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','header_trx_kegiatan.anggota_keluarga_id')
                     ->join('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                     ->join('blok','blok.blok_id','keluarga.blok_id')
                     ->when(!empty($request->monthSelected), function ($query) use ($request) {
                        $query->whereMonth('header_trx_kegiatan.tgl_pendaftaran', $request->monthSelected);
                        if (!empty($request->yearSelected)) {
                            $query->whereYear('header_trx_kegiatan.tgl_pendaftaran', $request->yearSelected);
                        }
                     })
                     ->whereNotIn('kat_kegiatan.kat_kegiatan_id',function($query){
                        $query->select('kegiatan.kat_kegiatan_id')
                               ->from('kegiatan')
                               ->whereNotIn('kegiatan.rt_id',function($query){
                                   $query->select('rt.rt_id')
                                         ->from('rt')
                                         ->where('rt.rt','DKM');
                               });
                    });

        return \DataTables::of($model)
                            ->addIndexColumn()
                            ->addColumn('action', function($data) use ($datatableButtons,$isWarga) {
                                if(empty($data->no_bukti)){
                                    if($isWarga){
                                        return view('partials.buttons.cust-datatable',[
                                            'show'         => in_array("show", $datatableButtons ) ? route('Transaksi.DKM'.'.show', \Crypt::encryptString($data->header_trx_kegiatan_id)) : null,
                                            'edit'         => in_array("edit", $datatableButtons ) ? route('Transaksi.DKM'.'.edit', \Crypt::encryptString($data->header_trx_kegiatan_id)) : null,
                                            'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $data->header_trx_kegiatan_id : null,
                                        ]);
                                    }else{
                                        return view('partials.buttons.cust-datatable',[
                                            'show'         => in_array("show", $datatableButtons ) ? route('Transaksi.DKM'.'.show', \Crypt::encryptString($data->header_trx_kegiatan_id)) : null,
                                            'edit'         => in_array("edit", $datatableButtons ) ? route('Transaksi.DKM'.'.edit', \Crypt::encryptString($data->header_trx_kegiatan_id)) : null,
                                            'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $data->header_trx_kegiatan_id : null,
                                        ]);
                                    }
                                }else{
                                    return view('partials.buttons.cust-datatable',[
                                        'show'         => in_array("show", $datatableButtons ) ? route('Transaksi.DKM'.'.show', \Crypt::encryptString($data->header_trx_kegiatan_id)) : null,
                                        'customButton' => ['route' => route('Transaksi.DKM.create_pdf', \Crypt::encryptString($data->header_trx_kegiatan_id)),
                                                           'name' => 'Print'
                                                          ]
                                    ]);
                                }
                            })
                            ->addColumn('nama',function($data){
                                return "$data->nama ($data->nama_blok)";  
                            })
                            ->rawColumns(['action'])
                            ->make(true);
    }

    public function printPdf($headerTrxKegiatanId)
    {
        $id              = \Crypt::decryptString($headerTrxKegiatanId);
        $data            = $this->headerTrxRepo->show($id);
        $regPendaftaran  = explode('/',$data->no_pendaftaran)[0];
        $noBukti         = !empty($data->no_bukti) ? explode('/',$data->no_pendaftaran)[0] : '';
        $detailTransaksi = $this->headerTrxRepo->detailTransaksi($id);
        $tanggal         = \helper::datePrint($data->tgl_pendaftaran);

        $petugas = $this->user
                        ->select('anggota_keluarga.nama','users.email','users.is_admin')
                        ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                        ->where('users.user_id',$data->user_approval)
                        ->first();
              
        $pdf = \PDF::loadView('Transaksi.Approval.printPDF', compact('data','tanggal','detailTransaksi','petugas'))
              ->setPaper('A5', 'portrait')
              ->setOptions(['dpi' => 80,'fontHeightRatio' => 1,'defaultFont' => 'calibri']);
        
      

        return $pdf->stream();
    }
}