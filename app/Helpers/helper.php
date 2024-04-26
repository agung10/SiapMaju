<?php

namespace App\Helpers;

use App\Repositories\RoleManagement\UserRepository;
use Carbon\Carbon;

class Helper
{   
    public static function validation($inputs,$rules)
    {   
        return \Validator::make($inputs,$rules);
    }

    public static function select($table,$name,$existId=false,$order=false,$sortOrder=false)
    {
        $id = $table.'_id';     
       
        $model = \DB::table($table,$name)
                     ->select($id,$name)
                     ->when($order,function($query)use($order,$sortOrder){
                        $query->orderBy($order,$sortOrder);
                     })
                     ->get();
       
        $result = '<option></option>';

        foreach($model as $key => $val){
            $result .= '<option value="'.$val->$id.'" '.($val->$id == $existId ? "selected" : "").'>'.$val->$name.'</option>';
        }
  
        return $result;
    }

    public static function datePrint($date)
    {
        $dateCarbon = Carbon::parse($date)->isoFormat('dddd- D-M-Y');
        $monthIndo = array(1 => 'Januari',
                                'Februari',
                                'Maret',
                                'April',
                                'Mei',
                                'Juni',
                                'Juli',
                                'Agustus',
                                'September',
                                'Oktober',
                                'November',
                                'Desember');

        $explodeDate = explode('-',$dateCarbon);
        $day = $explodeDate[1];
        $month = $monthIndo[$explodeDate[2]];
        $year = $explodeDate[3];
        $dayIndo = $explodeDate[0];

        $dateIndo = $dayIndo.','.$day.'/'.$month.'/'.$year;

        return $dateIndo;
    }

    /**
    * Format timestamp without timestamp date into
    * dd mm yyyy with month name using indonesian lang
    *
    * @param $tanggal
    * @return indonesian date with format dd mm yyyy
    */
    public static function tglIndo($tanggal){
        if($tanggal != null){
            $bulan = array (
                1 =>   'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember'
            );
            $pecahkan = explode('-', $tanggal);

            // check if $tanggal has jam menit detik
            if ( preg_match('/\s/',$tanggal)){
                $waktu    = explode(' ', $tanggal);
                $tanggal  = $waktu[0]; // tanggal bulan tahun
                $jam      = $waktu[1]; // jam menit detik
                $pecahkanWaktu = explode('-', $tanggal);

                return $pecahkanWaktu[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkanWaktu[0].' '. $jam;
            } else {
                return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
            }
        } else {
            return null;
        }
    }

    public static function imageLoad($folder='', $fileName='')
    {
        $fileExist = is_file(public_path("uploaded_files/$folder/$fileName"));

        $image = $fileExist === true ? asset("uploaded_files/$folder/$fileName") : asset("images/NoPic.png");

        return $image;
    }

    public static function loadImgUpload($folder,$image)
    {
        $fileExist = is_file(public_path("upload/$folder/$image"));

        $image = $fileExist === true ? asset("upload/$folder/$image") : asset("images/NoPic.png");

        return $image;
    }

    public static function getYoutubeThumbnail($youtubeUrl) {
        $placeholder = 'http://img.youtube.com/vi/youtube_video_id/sddefault.jpg';
        $idVideo     = \Str::afterLast($youtubeUrl, '/');
        $url         = str_replace("youtube_video_id", $idVideo, $placeholder);
        
        return $url;
    }

    public static function number_formats($input, $output = 'view', $len_decimal = 2, $in_thousand = '.', $ignore_decimal = false)
    {
        $ls_in_thousand = array('', '.', ',');

        // check decimal length
        if (!is_int($len_decimal)) {
            die('length decimal is not number');
        }

        // check in_thousand pada array()
        if (!in_array($in_thousand, $ls_in_thousand)) {
            die('symbol in_thousand not found');
        }


        $symbol_decimal = '';

        // check $in_thousand
        // then, set symbol decimal
        if ($in_thousand == '.') {
            $symbol_decimal = ',';
        } elseif ($in_thousand == ',') {
            $symbol_decimal = '.';
        }

        if ($output == 'view') {
            return number_format($input, $len_decimal, $symbol_decimal, $in_thousand);
        } elseif ($output == 'db') {
            
            $tmp_symbol_decimal = str_replace($symbol_decimal, '#', $input); // # = temporary symbol; ex output: 1.234#76
            $reset_in_thousand  = str_replace($in_thousand, '', $tmp_symbol_decimal); // ex output: 1234#76
            $reset_tmp          = str_replace('#', '.', $reset_in_thousand); // ex output: 1234.76

            // fix reset
            // ex: 123457.33
            $reset              = number_format($reset_tmp, $len_decimal, '.', '');
            
            return $reset;

        } elseif ($output == 'js') {

            $js = "{$input}.number(true, $len_decimal, '$symbol_decimal', '$in_thousand');";
            return $js;

        } elseif ($output == 'validation') {

            $decimal_pattern = '';
            if ($len_decimal > 0) {

                if($ignore_decimal == false){
                    // set decimal pattern
                    // 123,123.44 => .44 is decimal
                    $decimal_pattern = "(\\$symbol_decimal\d+)"; // decimal is required
                }else if ($ignore_decimal == true){
                    if($symbol_decimal == '') $symbol_decimal = '.';
                    $decimal_pattern = "((\\$symbol_decimal\d+)?)"; // ? == decimal is optional
                }
            }

            // 123,234.233 => (123)(,234)(.223)
            return "/^(\d{1,3})($in_thousand\d{3})*$decimal_pattern$/"; // * == Zero or more

        }
        elseif ($output == 'clear_formatted_js') {

            return "replace(/\\{$in_thousand}/g, '').replace(/\\{$symbol_decimal}/g, '.');";

        } elseif ($output == 'view_currency') {

            return 'Rp. ' . number_format($input, $len_decimal, $symbol_decimal, $in_thousand);

        } elseif ($output == 'view_discount') {

            return number_format($input, $len_decimal, $symbol_decimal, $in_thousand) . ' %';

        } elseif ($output == 'dynamic_var_jquery') {

            return "number(true, $len_decimal, '$symbol_decimal', '$in_thousand');";

        }
    }

    /**
    * Date Format for 'backend' and 'frontend'
    * @author moko
    *
    * @param $input date | string('$("input[name='date_pickers']")')
    * @param $output string date (view|db|js|format)
    * @param $opt_js array
    * @param $date_db ;date format for DB
    * @param $date_php ;date format for PHP
    * @return any format (date view, date for input(js), date_php)
    */
    public static function date_formats($input, $output = 'view', $opt_js = array('format'=>'dd-mm-yyyy','autoclose'=>true), $date_db = 'Y-m-d', $date_php = 'd-m-Y')
    {
        if ($output == 'view') {

            $reset = date($date_php, strtotime($input));
            return (empty($input) ? '' : $reset);

        } elseif ($output == 'db') {

            $reset = date($date_db, strtotime($input));
            return (empty($input) ? '' : $reset);

        } elseif ($output == 'js') {
            // parsing date format from php to javascript
            if($date_php == 'Y-m-d') $opt_js['format'] = 'yyyy-mm-dd';
            if($date_php == 'Y-d-m') $opt_js['format'] = 'yyyy-dd-mm';
            if($date_php == 'd-m-Y') $opt_js['format'] = 'dd-mm-yyyy';

            $opt_datepicker = json_encode($opt_js);

            $js = "{$input}.datepicker(
                        {$opt_datepicker}
                    );";
            return $js;
        } elseif ($output == 'date_php') {
            return $date_php;
        }
    }

    public static function romanMonth($i)
    {
        $romanMonth = [
            '01' => 'I',
            '02' => 'II',
            '03' => 'III',
            '04' => 'IV',
            '05' => 'V',
            '06' => 'VI',
            '07' => 'VII',
            '08' => 'VIII',
            '09' => 'IX',
            '10' => 'X',
            '11' => 'XI',
            '12' => 'XII',
        ];

        return $romanMonth[$i];
    }

    public static function countDataKeluarga($countBy, $params)
    {   
        $checkRole = app('App\Helpers\helper')->checkUserRole('all');
        $isAdmin = $checkRole['isAdmin'];

        $getData = \DB::table('user_role')->select(
            'user_role.user_role_id',
            'user_role.user_id',
            'user_role.role_id',
            'users.user_id',
            'anggota_keluarga.is_rw',
            'anggota_keluarga.rw_id',
        )
            ->leftJoin('users', 'users.user_id', 'user_role.user_id')
            ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
            ->where('users.user_id', \Auth::user()->user_id)
            ->first();

        $rwID = $getData->rw_id;

        $yearBalita = date('Y',strtotime('-5 year'));
        $yearPelajar = date('Y',strtotime('-22 year'));
        $yearProduktif = date('Y',strtotime('-60 year'));
        $yearLansia = date('Y',strtotime('-61 year'));

        $count = \DB::table('anggota_keluarga')
                    ->join('keluarga', 'keluarga.keluarga_id', 'anggota_keluarga.keluarga_id')
                    ->when($countBy == 'kk',function($query){
                        $query->where('anggota_keluarga.hub_keluarga_id',1);
                    })
                    ->when($countBy == 'pria',function($query){
                        $query->where('anggota_keluarga.jenis_kelamin','L');
                    })
                    ->when($countBy == 'wanita',function($query){
                        $query->where('anggota_keluarga.jenis_kelamin','P');
                    })
                    ->when($countBy == 'balita',function($query)use($yearBalita){
                        $query->whereYear('anggota_keluarga.tgl_lahir','>=',$yearBalita);
                    })
                    ->when($countBy == 'pelajar',function($query)use($yearBalita, $yearPelajar){
                        $query->whereYear('anggota_keluarga.tgl_lahir','<' , $yearBalita);
                        $query->whereYear('anggota_keluarga.tgl_lahir','>=',$yearPelajar);
                    })
                    ->when($countBy == 'produktif',function($query)use($yearPelajar, $yearProduktif){
                        $query->whereYear('anggota_keluarga.tgl_lahir','<',$yearPelajar);
                        $query->whereYear('anggota_keluarga.tgl_lahir','>=',$yearProduktif);
                    })
                    ->when($countBy == 'lansia',function($query)use($yearLansia){
                        $query->whereYear('anggota_keluarga.tgl_lahir','<=',$yearLansia);
                    })
                    ->when($countBy == 'warga',function($query){
                        $query->where('anggota_keluarga.is_active', true);
                    })
                    ->when($countBy == 'warga_asli',function($query){
                        $query->where('keluarga.status_domisili', 1);
                        $query->where('anggota_keluarga.hub_keluarga_id', 1);
                    })
                    ->when($countBy == 'warga_asli_tinggal_diluar',function($query){
                        $query->where('keluarga.status_domisili', 2);
                        $query->where('anggota_keluarga.hub_keluarga_id', 1);
                    })
                    ->when($countBy == 'warga_pendatang',function($query){
                        $query->where('keluarga.status_domisili', 3);
                        $query->where('anggota_keluarga.hub_keluarga_id', 1);
                    })
                    ->when(!$isAdmin, function ($query) use ($rwID) {
                        $query->where('anggota_keluarga.rw_id', $rwID);
                    })
                    ->when($params, function ($query) use ($params) {
                        if (!empty($params['province_id'])) {
                            $query->where('anggota_keluarga.province_id', $params["province_id"]);
                        }
                        if (!empty($params['city_id'])) {
                            $query->where('anggota_keluarga.city_id', $params["city_id"]);
                        }
                        if (!empty($params['subdistrict_id'])) {
                            $query->where('anggota_keluarga.subdistrict_id', $params["subdistrict_id"]);
                        }
                        if (!empty($params['kelurahan_id'])) {
                            $query->where('anggota_keluarga.kelurahan_id', $params["kelurahan_id"]);
                        }
                        if (!empty($params['rw_id'])) {
                            $query->where('anggota_keluarga.rw_id', $params["rw_id"]);
                        }
                        if (!empty($params['rt_id'])) {
                            $query->where('anggota_keluarga.rt_id', $params["rt_id"]);
                        }
                    })
                    ->count();

        return $count;
    }

    public static function countJenisSurat($jenis_surat_id, $params)
    {
        $checkRole = app('App\Helpers\helper')->checkUserRole('all');
        $isAdmin = $checkRole['isAdmin'];

        $getData = \DB::table('user_role')->select(
            'user_role.user_role_id',
            'user_role.user_id',
            'user_role.role_id',
            'users.user_id',
            'anggota_keluarga.rw_id',
        )
            ->leftJoin('users', 'users.user_id', 'user_role.user_id')
            ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
            ->where('users.user_id', \Auth::user()->user_id)
            ->first();

        $rwID = $getData->rw_id;

        $count = \DB::table('surat_permohonan') 
                    ->when(!$isAdmin, function ($query) use ($rwID) {
                        $query->where('surat_permohonan.rw_id', $rwID);
                    })
                    ->where('surat_permohonan.jenis_surat_id',$jenis_surat_id)
                    ->when($params, function ($query) use ($params) {
                        if (!empty($params['province_id'])) {
                            $query->where('surat_permohonan.province_id', $params["province_id"]);
                        }
                        if (!empty($params['city_id'])) {
                            $query->where('surat_permohonan.city_id', $params["city_id"]);
                        }
                        if (!empty($params['subdistrict_id'])) {
                            $query->where('surat_permohonan.subdistrict_id', $params["subdistrict_id"]);
                        }
                        if (!empty($params['kelurahan_id'])) {
                            $query->where('surat_permohonan.kelurahan_id', $params["kelurahan_id"]);
                        }
                        if (!empty($params['rw_id'])) {
                            $query->where('surat_permohonan.rw_id', $params["rw_id"]);
                        }
                        if (!empty($params['rt_id'])) {
                            $query->where('surat_permohonan.rt_id', $params["rt_id"]);
                        }
                    })
                    ->count();
                
        return $count;
    }

    public static function countKegiatanPerProgram($program_id, $params)
    {
        $checkRole = app('App\Helpers\helper')->checkUserRole('all');
        $isAdmin = $checkRole['isAdmin'];

        $getData = \DB::table('user_role')->select(
            'user_role.user_role_id',
            'user_role.user_id',
            'user_role.role_id',
            'users.user_id',
            'anggota_keluarga.rw_id',
        )
            ->leftJoin('users', 'users.user_id', 'user_role.user_id')
            ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
            ->where('users.user_id', \Auth::user()->user_id)
            ->first();

        $rwID = $getData->rw_id;

        $yearNow = date('Y');

        $count = \DB::table('agenda') 
                    ->join('program', 'program.program_id', 'agenda.program_id')
                    ->where('agenda.program_id', $program_id)
                    ->whereYear('agenda.tanggal','=',$yearNow)
                    ->when(!$isAdmin, function ($query) use ($rwID) {
                        $query->where('program.rw_id', $rwID);
                    })
                    ->when($params, function ($query) use ($params) {
                        if (!empty($params['province_id'])) {
                            $query->where('program.province_id', $params["province_id"]);
                        }
                        if (!empty($params['city_id'])) {
                            $query->where('program.city_id', $params["city_id"]);
                        }
                        if (!empty($params['subdistrict_id'])) {
                            $query->where('program.subdistrict_id', $params["subdistrict_id"]);
                        }
                        if (!empty($params['kelurahan_id'])) {
                            $query->where('program.kelurahan_id', $params["kelurahan_id"]);
                        }
                        if (!empty($params['rw_id'])) {
                            $query->where('program.rw_id', $params["rw_id"]);
                        }
                        if (!empty($params['rt_id'])) {
                            $query->where('program.rt_id', $params["rt_id"]);
                        }
                    })
                    ->count();
                    
        return $count;
    }

    public static function countKegiatan3TahunAkhir($params)
    {
        $checkRole = app('App\Helpers\helper')->checkUserRole('all');
        $isAdmin = $checkRole['isAdmin'];

        $getData = \DB::table('user_role')->select(
            'user_role.user_role_id',
            'user_role.user_id',
            'user_role.role_id',
            'users.user_id',
            'anggota_keluarga.rw_id',
        )
            ->leftJoin('users', 'users.user_id', 'user_role.user_id')
            ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
            ->where('users.user_id', \Auth::user()->user_id)
            ->first();

        $rwID = $getData->rw_id;

        $year = date('Y',strtotime('-3 year'));

        $count = \DB::table('agenda') 
                    ->join('program', 'program.program_id', 'agenda.program_id')
                    ->whereYear('agenda.tanggal','>=',$year)
                    ->when(!$isAdmin, function ($query) use ($rwID) {
                        $query->where('program.rw_id', $rwID);
                    })
                    ->when($params, function ($query) use ($params) {
                        if (!empty($params['province_id'])) {
                            $query->where('program.province_id', $params["province_id"]);
                        }
                        if (!empty($params['city_id'])) {
                            $query->where('program.city_id', $params["city_id"]);
                        }
                        if (!empty($params['subdistrict_id'])) {
                            $query->where('program.subdistrict_id', $params["subdistrict_id"]);
                        }
                        if (!empty($params['kelurahan_id'])) {
                            $query->where('program.kelurahan_id', $params["kelurahan_id"]);
                        }
                        if (!empty($params['rw_id'])) {
                            $query->where('program.rw_id', $params["rw_id"]);
                        }
                        if (!empty($params['rt_id'])) {
                            $query->where('program.rt_id', $params["rt_id"]);
                        }
                    })
                    ->groupBy('agenda.tanggal')
                    ->count();
                    
        return $count;
    }

    public static function sumKomposisiNilaiProgram($program_id, $params)
    {
        $checkRole = app('App\Helpers\helper')->checkUserRole('all');
        $isAdmin = $checkRole['isAdmin'];

        $getData = \DB::table('user_role')->select(
            'user_role.user_role_id',
            'user_role.user_id',
            'user_role.role_id',
            'users.user_id',
            'anggota_keluarga.is_rw',
            'anggota_keluarga.rw_id',
        )
            ->leftJoin('users', 'users.user_id', 'user_role.user_id')
            ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
            ->where('users.user_id', \Auth::user()->user_id)
            ->first();

        $rwID = $getData->rw_id;

        $sumNilai = \DB::table('agenda')
                    ->select('agenda.nilai')
                    ->join('program', 'program.program_id', 'agenda.program_id')
                    ->where('agenda.program_id', $program_id)
                    ->when(!$isAdmin, function ($query) use ($rwID) {
                        $query->where('program.rw_id', $rwID);
                    })
                    ->when($params, function ($query) use ($params) {
                        if (!empty($params['province_id'])) {
                            $query->where('program.province_id', $params["province_id"]);
                        }
                        if (!empty($params['city_id'])) {
                            $query->where('program.city_id', $params["city_id"]);
                        }
                        if (!empty($params['subdistrict_id'])) {
                            $query->where('program.subdistrict_id', $params["subdistrict_id"]);
                        }
                        if (!empty($params['kelurahan_id'])) {
                            $query->where('program.kelurahan_id', $params["kelurahan_id"]);
                        }
                        if (!empty($params['rw_id'])) {
                            $query->where('program.rw_id', $params["rw_id"]);
                        }
                        if (!empty($params['rt_id'])) {
                            $query->where('program.rt_id', $params["rt_id"]);
                        }
                    })
                    ->sum('agenda.nilai');

        return $sumNilai;
    }

    public static function sumKomposisiNilaiSumberBiaya($kat_sumber_biaya_id, $params)
    {
        $checkRole = app('App\Helpers\helper')->checkUserRole('all');
        $isAdmin = $checkRole['isAdmin'];

        $getData = \DB::table('user_role')->select(
            'user_role.user_role_id',
            'user_role.user_id',
            'user_role.role_id',
            'users.user_id',
            'anggota_keluarga.is_rw',
            'anggota_keluarga.rw_id',
        )
            ->leftJoin('users', 'users.user_id', 'user_role.user_id')
            ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
            ->where('users.user_id', \Auth::user()->user_id)
            ->first();

        $rwID = $getData->rw_id;

        $sumNilai = \DB::table('agenda')
                    ->select('agenda.nilai')
                    ->join('program', 'program.program_id', 'agenda.program_id')
                    ->join('kat_sumber_biaya', 'kat_sumber_biaya.kat_sumber_biaya_id', 'agenda.kat_sumber_biaya_id')
                    ->where('agenda.kat_sumber_biaya_id', $kat_sumber_biaya_id)
                    ->when(!$isAdmin, function ($query) use ($rwID) {
                        $query->where('program.rw_id', $rwID);
                    })
                    ->when($params, function ($query) use ($params) {
                        if (!empty($params['province_id'])) {
                            $query->where('program.province_id', $params["province_id"]);
                        }
                        if (!empty($params['city_id'])) {
                            $query->where('program.city_id', $params["city_id"]);
                        }
                        if (!empty($params['subdistrict_id'])) {
                            $query->where('program.subdistrict_id', $params["subdistrict_id"]);
                        }
                        if (!empty($params['kelurahan_id'])) {
                            $query->where('program.kelurahan_id', $params["kelurahan_id"]);
                        }
                        if (!empty($params['rw_id'])) {
                            $query->where('program.rw_id', $params["rw_id"]);
                        }
                        if (!empty($params['rt_id'])) {
                            $query->where('program.rt_id', $params["rt_id"]);
                        }
                    })
                    ->sum('agenda.nilai');

        return $sumNilai;
    }

    public static function countHubunganKeluarga($hub_keluarga_id, $params)
    {
        $checkRole = app('App\Helpers\helper')->checkUserRole('all');
        $isAdmin = $checkRole['isAdmin'];

        $getData = \DB::table('user_role')->select(
            'user_role.user_role_id',
            'user_role.user_id',
            'user_role.role_id',
            'users.user_id',
            'anggota_keluarga.is_rw',
            'anggota_keluarga.rw_id',
        )
            ->leftJoin('users', 'users.user_id', 'user_role.user_id')
            ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
            ->where('users.user_id', \Auth::user()->user_id)
            ->first();

        $rwID = $getData->rw_id;

        $count = \DB::table('anggota_keluarga') 
                    ->where('anggota_keluarga.hub_keluarga_id',$hub_keluarga_id)
                    ->when(!$isAdmin, function ($query) use ($rwID) {
                        $query->where('anggota_keluarga.rw_id', $rwID);
                    })
                    ->when($params, function ($query) use ($params) {
                        if (!empty($params['province_id'])) {
                            $query->where('anggota_keluarga.province_id', $params["province_id"]);
                        }
                        if (!empty($params['city_id'])) {
                            $query->where('anggota_keluarga.city_id', $params["city_id"]);
                        }
                        if (!empty($params['subdistrict_id'])) {
                            $query->where('anggota_keluarga.subdistrict_id', $params["subdistrict_id"]);
                        }
                        if (!empty($params['kelurahan_id'])) {
                            $query->where('anggota_keluarga.kelurahan_id', $params["kelurahan_id"]);
                        }
                        if (!empty($params['rw_id'])) {
                            $query->where('anggota_keluarga.rw_id', $params["rw_id"]);
                        }
                        if (!empty($params['rt_id'])) {
                            $query->where('anggota_keluarga.rt_id', $params["rt_id"]);
                        }
                    })
                    ->count();
                
        return $count;
    }

    public static function countDataNOP($params)
    {   
        $checkRole = app('App\Helpers\helper')->checkUserRole('all');
        $isAdmin = $checkRole['isAdmin'];

        $getData = \DB::table('user_role')->select(
            'user_role.user_role_id',
            'user_role.user_id',
            'user_role.role_id',
            'users.user_id',
            'anggota_keluarga.is_rw',
            'anggota_keluarga.rw_id',
        )
            ->leftJoin('users', 'users.user_id', 'user_role.user_id')
            ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
            ->where('users.user_id', \Auth::user()->user_id)
            ->first();

        $rwID = $getData->rw_id;

        $count = \DB::table('blok')
                    ->whereNotNull('nop')
                    ->when(!$isAdmin, function ($query) use ($rwID) {
                        $query->where('blok.rw_id', $rwID);
                    })
                    ->when($params, function ($query) use ($params) {
                        if (!empty($params['province_id'])) {
                            $query->where('blok.province_id', $params["province_id"]);
                        }
                        if (!empty($params['city_id'])) {
                            $query->where('blok.city_id', $params["city_id"]);
                        }
                        if (!empty($params['subdistrict_id'])) {
                            $query->where('blok.subdistrict_id', $params["subdistrict_id"]);
                        }
                        if (!empty($params['kelurahan_id'])) {
                            $query->where('blok.kelurahan_id', $params["kelurahan_id"]);
                        }
                        if (!empty($params['rw_id'])) {
                            $query->where('blok.rw_id', $params["rw_id"]);
                        }
                        if (!empty($params['rt_id'])) {
                            $query->where('blok.rt_id', $params["rt_id"]);
                        }
                    })
                    ->count();

        return $count;
    }

    public static function countDataPBB($countBy, $params, $tahun_pajak = false)
    {   
        $checkRole = app('App\Helpers\helper')->checkUserRole('all');
        $isAdmin = $checkRole['isAdmin'];

        $getData = \DB::table('user_role')->select(
            'user_role.user_role_id',
            'user_role.user_id',
            'user_role.role_id',
            'users.user_id',
            'anggota_keluarga.is_rw',
            'anggota_keluarga.rw_id',
        )
            ->leftJoin('users', 'users.user_id', 'user_role.user_id')
            ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
            ->where('users.user_id', \Auth::user()->user_id)
            ->first();

        $rwID = $getData->rw_id;

        $yearNow = date('Y');

        $count = \DB::table('pbb')
                    ->join('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'pbb.anggota_keluarga_id')
                    ->join('blok', 'blok.blok_id', 'pbb.blok_id')
                    ->where('pbb.tahun_pajak','=',$yearNow)
                    ->when($countBy == 'jumlah_sudah_membayar',function($query){
                        $query->where('pbb.nilai', '!=', 0);
                        $query->whereNotNull('pbb.tgl_bayar');
                    })
                    ->when(!$isAdmin, function ($query) use ($rwID) {
                        $query->where('anggota_keluarga.rw_id', $rwID);
                    })
                    ->when($params, function ($query) use ($params) {
                        if (!empty($params['province_id'])) {
                            $query->where('anggota_keluarga.province_id', $params["province_id"]);
                        }
                        if (!empty($params['city_id'])) {
                            $query->where('anggota_keluarga.city_id', $params["city_id"]);
                        }
                        if (!empty($params['subdistrict_id'])) {
                            $query->where('anggota_keluarga.subdistrict_id', $params["subdistrict_id"]);
                        }
                        if (!empty($params['kelurahan_id'])) {
                            $query->where('anggota_keluarga.kelurahan_id', $params["kelurahan_id"]);
                        }
                        if (!empty($params['rw_id'])) {
                            $query->where('anggota_keluarga.rw_id', $params["rw_id"]);
                        }
                        if (!empty($params['rt_id'])) {
                            $query->where('anggota_keluarga.rt_id', $params["rt_id"]);
                        }
                    })
                    ->when(($tahun_pajak != false),function($query) use ($tahun_pajak) {
                        $query->where('pbb.tahun_pajak', $tahun_pajak);
                    })
                    ->count();

        return $count;
    }
    
    public static function sumNilaiPBBPaid($params)
    {   
        $checkRole = app('App\Helpers\helper')->checkUserRole('all');
        $isAdmin = $checkRole['isAdmin'];

        $getData = \DB::table('user_role')->select(
            'user_role.user_role_id',
            'user_role.user_id',
            'user_role.role_id',
            'users.user_id',
            'anggota_keluarga.is_rw',
            'anggota_keluarga.rw_id',
        )
            ->leftJoin('users', 'users.user_id', 'user_role.user_id')
            ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
            ->where('users.user_id', \Auth::user()->user_id)
            ->first();

        $rwID = $getData->rw_id;

        $yearNow = date('Y');

        $sumNilai = \DB::table('pbb')
                ->join('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'pbb.anggota_keluarga_id')
                ->join('blok', 'blok.blok_id', 'pbb.blok_id')
                ->where('pbb.tahun_pajak','=',$yearNow)
                ->when(!$isAdmin, function ($query) use ($rwID) {
                    $query->where('anggota_keluarga.rw_id', $rwID);
                })
                ->when($params, function ($query) use ($params) {
                    if (!empty($params['province_id'])) {
                        $query->where('anggota_keluarga.province_id', $params["province_id"]);
                    }
                    if (!empty($params['city_id'])) {
                        $query->where('anggota_keluarga.city_id', $params["city_id"]);
                    }
                    if (!empty($params['subdistrict_id'])) {
                        $query->where('anggota_keluarga.subdistrict_id', $params["subdistrict_id"]);
                    }
                    if (!empty($params['kelurahan_id'])) {
                        $query->where('anggota_keluarga.kelurahan_id', $params["kelurahan_id"]);
                    }
                    if (!empty($params['rw_id'])) {
                        $query->where('anggota_keluarga.rw_id', $params["rw_id"]);
                    }
                    if (!empty($params['rt_id'])) {
                        $query->where('anggota_keluarga.rt_id', $params["rt_id"]);
                    }
                })
                ->where('pbb.nilai', '!=', 0)
                ->whereNotNull('pbb.tgl_bayar')
                ->sum('pbb.nilai');
        
        return $sumNilai;
    }

    public static function checkUserRole($checkRole){
        $logedUSer = \DB::table('users')
                         ->select('anggota_keluarga.is_rt','anggota_keluarga.is_rw','anggota_keluarga.is_dkm','users.is_admin','keluarga.rt_id','keluarga.rw_id',
                                  'anggota_keluarga.anggota_keluarga_id','rt.rt','anggota_keluarga.hub_keluarga_id')
                         ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                         ->leftJoin('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                         ->leftJoin('rt','rt.rt_id','keluarga.rt_id')
                         ->where('user_id',\Auth::user()->user_id)
                         ->first();

        $isRt = $logedUSer->is_rt == true;
        $isRw = $logedUSer->is_rw == true;
        $isAdmin = $logedUSer->is_admin == true;
        $isDKM = $logedUSer->is_dkm == true;
        $isWarga = $isRt != true && $isRw != true && $isAdmin != true;
        $isKepalaKeluarga = $logedUSer->hub_keluarga_id === 1;

        if($checkRole == 'kelurahan'){
            $userRole = \DB::table('user_role')
                            ->select('role.role_name')
                            ->join('role','role.role_id','user_role.role_id')
                            ->where('user_role.user_id',\Auth::user()->user_id)
                            ->first()
                            ->role_name;
            
            return strtolower($userRole) === 'kelurahan';
        }

        if($checkRole == 'warga'){
            return $isWarga;
        }elseif($checkRole == 'rw'){
            return $isRw;
        }   
        elseif($checkRole == 'rt'){
            return $isRt;
        }
        elseif($checkRole == 'admin'){
            return $isAdmin;
        
        }elseif($checkRole == 'DKM'){
            return $isDKM;
        }elseif($checkRole === 'kepalaKeluarga'){
            return $isKepalaKeluarga;
        }elseif($checkRole == 'all'){
            return compact('isRt','isRw','isAdmin','isWarga','isDKM','isKepalaKeluarga');
        }
    }
}
