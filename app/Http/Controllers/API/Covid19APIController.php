<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Covid19APIController extends Controller
{
    public function getData()
    {   
        $dateFrom = date('Y-m-d',strtotime('-10 days'));
        $dateTo = date('Y-m-d');
        
        $table = \DB::table('covid19')
                     ->select('covid19.tgl_input','covid19.jml_positif','covid19.jml_sembuh','covid19.jml_meninggal')
                     ->whereBetween('covid19.tgl_input',[$dateFrom,$dateTo])
                     ->orderBy('covid19.tgl_input','ASC')
                     ->get();

        $stackChartData = \DB::table('covid19')
                             ->select('covid19.tgl_input','covid19.jml_positif','covid19.jml_sembuh','covid19.jml_meninggal','rt.rt')
                             ->join('rt','rt.rt_id','covid19.rt_id')
                             ->orderBy('covid19.tgl_input','ASC')
                            ->get();

        $sumPositif = \DB::table('covid19')
                          ->sum('jml_positif');
        $sumSembuh = \DB::table('covid19')
                         ->sum('jml_sembuh');

        
        return response()->json(['result' => $table,
                                 'stackChartData' => $stackChartData,
                                 'sumPositif' => $sumPositif,
                                 'sumSembuh' => $sumSembuh]);
    }
}
