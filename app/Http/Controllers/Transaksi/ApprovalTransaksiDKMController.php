<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\{HeaderTransaksiRepository,ApprovalTransaksiRepository}; 

class ApprovalTransaksiDKMController extends Controller
{
    public function __construct(HeaderTransaksiRepository $_HeaderTransaksiRepository, ApprovalTransaksiRepository $_ApprovalTransaksiRepository)
    {
        $route_name = explode('.',\Route::currentRouteName());
        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

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
        //
    }

    public function update(Request $request, $id)
    {
      return $this->approval->approval($id);
    }

    public function destroy($id)
    {
        
    }

    public function dataTables()
    {
        return $this->approval->dataTables('dkm');
    }
}
