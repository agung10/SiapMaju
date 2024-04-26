<?php

namespace App\Http\Controllers\Polling;
use App\Http\Controllers\Controller;
use App\Models\Polling\HasilPolling;
use App\Repositories\{ PollingRepository }; 
use Illuminate\Http\Request;

class LaporanAuditController extends Controller
{   
    public function __construct(PollingRepository $_PollingRepository)
    {
        $route_name = explode('.', \Route::currentRouteName());
        $this->route1 = ((isset($route_name[0])) ? $route_name[0] : (''));
        $this->route2 = ((isset($route_name[1])) ? $route_name[1] : (''));
        $this->route3 = ((isset($route_name[2])) ? $route_name[2] : (''));
        $this->polling =  $_PollingRepository;
    }

    public function index() {
        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3);
    }
    
    public function show($id) {
        $data = $this->polling->getQuestion(\Crypt::decrypt($id));
        $pollData = $this->polling->collectPollingData($data);

        if ($data->rt === 'DKM') {
            $hasilPolling = HasilPolling::select('hasil_polling.anggota_keluarga_id', 'hasil_polling.id_pilih_jawaban', 'hasil_polling.keterangan')->join('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'hasil_polling.anggota_keluarga_id')->where(['id_polling' => $data->id_polling, 'agama_id' => 1])->get();
        } else {
            $hasilPolling = HasilPolling::select('hasil_polling.anggota_keluarga_id', 'hasil_polling.id_pilih_jawaban', 'hasil_polling.keterangan')->join('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'hasil_polling.anggota_keluarga_id')->where(['id_polling' => $data->id_polling])->get();
        }
        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data', 'hasilPolling', 'pollData'));
    }

    public function dataTables() {
        return $this->polling->dataTablesLaporanAudit();
    }
}