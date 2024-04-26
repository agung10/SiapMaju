<?php

namespace App\Http\Controllers\API\V2;

use App\Models\RamahAnak\RamahAnak;
use App\Models\RamahAnak\Vaksin;
use App\Models\Master\Keluarga\AnggotaKeluarga;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RamahAnakController extends Controller
{
    public function __construct(RamahAnak $ramahAnak, Vaksin $vaksin, AnggotaKeluarga $anggotaKeluarga)
    {
        $this->userLoggedIn    = auth('api')->user();
        $this->ramahAnak       = $ramahAnak;
        $this->vaksin          = $vaksin;
        $this->anggotaKeluarga = $anggotaKeluarga;
        $this->kepalaKeluarga  = 1;
    }

    public function listAnak()
    {
        $wargaLoggedIn = $this->userLoggedIn->anggotaKeluarga;
        $statusHubAnak = 5;
        $listAnak  = $this->anggotaKeluarga
                          ->select([
                            'anggota_keluarga_id as id', 'nama as name','tgl_lahir', 'jenis_kelamin'
                          ])
                          ->where('keluarga_id', $wargaLoggedIn->keluarga_id)
                          ->where('hub_keluarga_id', $statusHubAnak)
                          ->where('anggota_keluarga.is_active', true)
                          ->get();

        return response()->json($listAnak);
    }

    public function detail()
    {
        $response = [];
        $wargaLoggedIn = $this->userLoggedIn->anggotaKeluarga;
        $statusHubAnak = 5;
        $listAnak  = $this->anggotaKeluarga
                          ->select([
                            'anggota_keluarga_id as id', 'nama as name','tgl_lahir', 'jenis_kelamin'
                          ])
                          ->where('keluarga_id', $wargaLoggedIn->keluarga_id)
                          ->where('hub_keluarga_id', $statusHubAnak)
                          ->where('anggota_keluarga.is_active', true)
                          ->get();

        foreach($listAnak as $anak) {
            $dataAnak = $this->ramahAnak->where('anggota_keluarga_id', $anak->id)->with('vaksin')->get();
            $anak['chart']   = $this->generateChartData($anak, $dataAnak);
            $anak['vaksin']  = $this->generateVaccineData($dataAnak);
            $anak['lainnya'] = $this->generateOthersData($dataAnak);

            array_push($response, $anak);
        }

        return response()->json($response); 
    }

    public function generateChartData($anak, $dataAnak)
    {
        $chartData = [];
        $start    = new \DateTime($anak->tgl_lahir);
        $interval = new \DateInterval('P1M');
        $end      = new \DateTime($anak->tgl_lahir);
        $end->add(new \DateInterval('P5Y'));
        $end->modify('+1 month');
        $period   = new \DatePeriod($start, $interval, $end);

        $chartData['tinggi']['labels']   = [];
        $chartData['berat']['labels']    = [];
        $chartData['berat']['datasets']  = [];
        $chartData['tinggi']['datasets'] = [];

        $berat['data'] = [];
        $tinggi['data'] = [];

        // loop each month for 5 years since $anak->tgl_lahir
        foreach ($period as $key => $dt) {
            $year = $dt->format('Y');
            $month = $dt->format('M');
            $filterDataAnak = $dataAnak->sortBy('tgl_input')
                                   ->filter(function ($value) use ($year, $month) {
                                        return date('Y', strtotime($value->tgl_input)) == $year 
                                            && date('M', strtotime($value->tgl_input)) == $month ;
                                   })
                                   ->values()
                                   ->first();

            $beratAnak  = $filterDataAnak ? $filterDataAnak->berat : null;
            $tinggiAnak = $filterDataAnak ? $filterDataAnak->tinggi : null;

            array_push($chartData['tinggi']['labels'], $key);
            array_push($chartData['berat']['labels'], $key);

            array_push($berat['data'], $beratAnak);
            array_push($tinggi['data'], $tinggiAnak);
        }

        array_push($chartData['berat']['datasets'], $berat);
        array_push($chartData['tinggi']['datasets'], $tinggi);

        return $chartData;
    }

    public function generateVaccineData($dataAnak)
    {
        return $dataAnak->where('vaksin', '!=', NULL)->values()->all();
    }

    public function generateOthersData($dataAnak)
    {
        return $dataAnak->all();
    }

}
