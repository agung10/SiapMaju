<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\helper;
use App\Repositories\{HeaderTransaksiRepository,ApprovalTransaksiRepository}; 
use PDF;

class ApprovalTransaksiController extends Controller
{
    public function __construct(HeaderTransaksiRepository $_HeaderTransaksiRepository, ApprovalTransaksiRepository $_ApprovalTransaksiRepository)
    {
        $route_name = explode('.',\Route::currentRouteName());

        $this->route1 = ((isset($route_name[0])) ? $route_name[0] : (''));
        $this->route2 = ((isset($route_name[1])) ? $route_name[1] : (''));
        $this->route3 = ((isset($route_name[2])) ? $route_name[2] : (''));

        $this->transaksi = $_HeaderTransaksiRepository;
        $this->approval = $_ApprovalTransaksiRepository;
    }

    public function index()
    {
        return view($this->route1.'.'.$this->route2.'.'.$this->route3);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {   
        $id = \Crypt::decryptString($id);

        $data = $this->transaksi->show($id);
        $regPendaftaran = explode('/',$data->no_pendaftaran)[0];
        $noBukti= !empty($data->no_bukti) ? explode('/',$data->no_pendaftaran)[0] : '';
        $detailTransaksi = $this->transaksi->detailTransaksi($id);

        return view($this->route1.'.'.$this->route2.'.'.$this->route3,compact('data','regPendaftaran','detailTransaksi','noBukti'));
    }

    public function edit($id)
    {   
        $id = \Crypt::decryptString($id);
      
        $data = $this->transaksi->show($id);
        $regPendaftaran = explode('/',$data->no_pendaftaran)[0];
        $noBukti= !empty($data->no_bukti) ? explode('/',$data->no_pendaftaran)[0] : '';
        $detailTransaksi = $this->transaksi->detailTransaksi($id);
        $tanggal = helper::datePrint($data->tgl_pendaftaran);

        $petugas = \DB::table('users')
                        ->select('anggota_keluarga.nama','users.email','users.is_admin')
                        ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                        ->where('users.user_id',$data->user_approval)
                        ->first();
              
        $pdf = \PDF::loadView($this->route1. '.' .$this->route2. '.printPDF', compact('data','tanggal','detailTransaksi','petugas'))
              ->setPaper('A5', 'portrait')
              ->setOptions(['dpi' => 80,'fontHeightRatio' => 1,'defaultFont' => 'calibri']);
        
      

        return $pdf->stream();

    }

    public function update(Request $request, $id)
    {
        return $this->approval->approval($id);

    }

    public function destroy($id)
    {
        //
    }

    public function dataTables()
    {
        return $this->approval->dataTables();
    }
}
