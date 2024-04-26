<?php

namespace App\Repositories;

use App\Models\PBB\Pbb;
use App\Models\Master\Keluarga\AnggotaKeluarga;
use App\Models\Master\Blok;

class PbbRepository extends BaseRepository
{
    public function __construct(Pbb $pbb)
    {
        $this->model = $pbb;
    }

    public function datatablePembagianPbb($request)
    {
        $isRt = (\Auth::user()->anggotaKeluarga && \Auth::user()->anggotaKeluarga->is_rt);

        $pbb  = $this->model
                    ->select([
                        'pbb.pbb_id', 'blok.nama_blok', 'anggota_keluarga.nama AS nama_warga', 'pbb.nop', 'pbb.tgl_terima', 'pbb.tahun_pajak', 'pbb.updated_at'
                    ])
                    ->whereNull('tgl_bayar')
                    ->join('blok', 'pbb.blok_id', 'blok.blok_id')
                    ->join('anggota_keluarga', 'pbb.anggota_keluarga_id', 'anggota_keluarga.anggota_keluarga_id');

        if($isRt) {
            $pbb->where('anggota_keluarga.rt_id', \Auth::user()->anggotaKeluarga->rt_id);
        }

        return \DataTables::of($pbb)
                ->addIndexColumn()
                ->editColumn('tgl_terima', function($row){
                    return strftime('%d %B %Y', strtotime($row->tgl_terima));
                })
                ->addColumn('action', function($row) {
                    return view('partials.buttons.cust-datatable',[
                        'show' => route("pbb.pembagian.show", $row->encryptedId),
                        'edit' => route("pbb.pembagian.edit", $row->encryptedId),
                    ]);
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function datatablePembayaranPbb($request, $tahun_pajak = false)
    {
        $wargaLogin = \Auth::user()->anggotaKeluarga;
        $pbb = $this->model
                    ->select([
                        'pbb.pbb_id', 'pbb.anggota_keluarga_id', 'anggota_keluarga.keluarga_id', 'blok.nama_blok', 'anggota_keluarga.nama AS nama_warga', 'pbb.nop', 'pbb.tgl_terima', 'pbb.tgl_bayar', 'pbb.tahun_pajak', 'pbb.updated_at', 'anggota_keluarga.rt_id'
                    ])
                    ->when(($tahun_pajak != false),function($query) use ($tahun_pajak) {
                        $query->where('pbb.tahun_pajak', $tahun_pajak);
                    })
                    ->join('blok', 'pbb.blok_id', 'blok.blok_id')
                    ->join('anggota_keluarga', 'pbb.anggota_keluarga_id', 'anggota_keluarga.anggota_keluarga_id');

        // warga non rt
        if($wargaLogin && !$wargaLogin->is_rt) {
            $pbb = $pbb->where('pbb.blok_id', $wargaLogin->keluarga->blok_id);
        }
        // warga ketua rt
        else if($wargaLogin && $wargaLogin->is_rt) {
            $pbb = $pbb->where('anggota_keluarga.rt_id', $wargaLogin->rt_id);
        }
        // warga kepala keluarga
        else if($wargaLogin && $wargaLogin->hub_keluarga_id === 1) {
            $pbb = $pbb->where('anggota_keluarga.keluarga_id', $wargaLogin->keluarga_id);
        }
        
        return \DataTables::of($pbb)
                ->addIndexColumn()
                ->editColumn('tgl_terima', function($row){
                    return strftime('%d %B %Y', strtotime($row->tgl_terima));
                })
                ->editColumn('tgl_bayar', function($row){
                    return $row->tgl_bayar ? strftime('%d %B %Y', strtotime($row->tgl_bayar)) : '-';
                })
                ->addColumn('status', function($row){
                    return $row->tgl_bayar
                        ? '<span class="label font-weight-bold label-lg  label-success label-inline">PAID</span>'
                        : '<span class="label font-weight-bold label-lg  label-danger label-inline">UNPAID</span>';
                })
                ->addColumn('action', function($row) {
                    $buttons['show2'] = ['name' => 'Detail', 'route' => route('pbb.pembayaran' . '.show', $row->encryptedId)];
                    if(is_null($row->tgl_bayar)) {
                        $buttons['customButton'] = [
                            'name' => 'Bayar',
                            'class' => 'btn-light-warning',
                            'route' => route("pbb.pembayaran.edit", $row->encryptedId)
                        ];
                    }

                    return view('partials.buttons.cust-datatable', $buttons);
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
    }

    public function getBlokByUserLogin()
    {
        $wargaLogin = \Auth::user()->anggotaKeluarga;
        $blok = Blok::when($wargaLogin, function($q) use($wargaLogin){
                        $q->where('rw_id', $wargaLogin->rw_id);
                    })
                    ->whereIn('blok_id', function($q) {
                        $q->select('blok_id')
                          ->from('keluarga');
                    })
                    ->whereNotNull('nop')
                    ->orderBy('nama_blok')
                    ->get(['nama_blok', 'blok_id', 'nop']);

        return $blok;
    }

    public function getWargaByBlok($request)
    {
        $blokId = $request->blok_id;
        $warga = AnggotaKeluarga::select([
                                    'anggota_keluarga.anggota_keluarga_id',
                                    \DB::raw("CONCAT(anggota_keluarga.nama,' - ', hub_keluarga.hubungan_kel) AS display_name"),
                                ])
                                ->join('hub_keluarga', 'anggota_keluarga.hub_keluarga_id', 'hub_keluarga.hub_keluarga_id')
                                ->join('keluarga', 'anggota_keluarga.keluarga_id', 'keluarga.keluarga_id')
                                ->join('blok', 'keluarga.blok_id', 'blok.blok_id')
                                ->whereNotNull('blok.nop')
                                ->where('blok.blok_id', $blokId)
                                ->pluck('display_name', 'anggota_keluarga_id');

        return $warga;
    }

    public function storePembagianPbb($request)
    {
        $pbbExist = $this->model->where('anggota_keluarga_id', $request->anggota_keluarga_id)
                              ->where('tahun_pajak', $request->tahun_pajak)
                              ->first();

        if($pbbExist) return [
            'status'       => false,
            'notification' => 'PBB dengan penerima tersebut di tahun '. $request->tahun_pajak .' telah ada', 
            'type'         => 'danger'
        ];

        $storeImage         = $request->hasFile('foto_terima');
        $data               = $storeImage ? $request->all() : $request->except('foto_terima');
        $data['tgl_terima'] = \helper::date_formats($data['tgl_terima'], 'db');
        $store              = $this->store($data, $storeImage);

        return [
            'status'       => true,
            'notification' => 'Data berhasil disimpan', 
            'type'         => 'success'
        ];
    }

    public function updatePembagianPbb($request, $encryptedId)
    {
        $pbbId = \Crypt::decryptString($encryptedId);
        $pbbExist = $this->model->where('anggota_keluarga_id', $request->anggota_keluarga_id)
                              ->where('tahun_pajak', $request->tahun_pajak)
                              ->where('pbb_id', '!=', $pbbId)
                              ->first();

        if($pbbExist) return [
            'status'       => false,
            'notification' => 'PBB dengan penerima tersebut di tahun '. $request->tahun_pajak .' telah ada', 
            'type'         => 'danger'
        ];

        $updateImage        = $request->hasFile('foto_terima');
        $data               = $updateImage ? $request->all() : $request->except('foto_terima');
        $data['tgl_terima'] = \helper::date_formats($data['tgl_terima'], 'db');
        $update             = $this->update($data, $pbbId, $updateImage);

        return [
            'status'       => true,
            'notification' => 'Data berhasil disimpan', 
            'type'         => 'success'
        ];
    }

    public function updatePembayaranPbb($request, $pbbId)
    {
        $updateImage   = $request->hasFile('bukti_bayar');
        $data          = $updateImage ? $request->all() : $request->except('bukti_bayar');
        $data['nilai'] = \helper::number_formats($data['nilai'], 'db');
        $update        = $this->update($data, $pbbId, $updateImage);

        return [
            'status'       => true,
            'notification' => 'Pembayaran berhasil disimpan', 
            'type'         => 'success'
        ];
    }

}