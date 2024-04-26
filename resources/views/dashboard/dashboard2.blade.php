<?php
   $logedUSer = \DB::table('users')
                     ->select('anggota_keluarga.is_rt','anggota_keluarga.is_rw','users.is_admin','anggota_keluarga.rt_id','anggota_keluarga.rw_id')
                     ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                     ->where('user_id',\Auth::user()->user_id)
                     ->first();

   $isAdmin = $logedUSer->is_admin == true;
   $rwID = $logedUSer->rw_id;
   
   $proses_surat = \DB::table('surat_permohonan')
                     ->select('surat_permohonan.tgl_permohonan', 'surat_permohonan.tgl_approve_rw')
                     ->when(!$isAdmin, function ($query) use ($rwID) {
                           $query->where('surat_permohonan.rw_id', $rwID);
                     })
                     ->when(1, function ($query) {
                        if (\Request::input('province_id')) {
                           $query->where('surat_permohonan.province_id', \Request::input('province_id'));
                        }
                        if (\Request::input('city_id')) {
                           $query->where('surat_permohonan.city_id', \Request::input('city_id'));
                        }
                        if (\Request::input('subdistrict_id')) {
                           $query->where('surat_permohonan.subdistrict_id', \Request::input('subdistrict_id'));
                        }
                        if (\Request::input('kelurahan_id')) {
                           $query->where('surat_permohonan.kelurahan_id', \Request::input('kelurahan_id'));
                        }
                        if (\Request::input('rw_id')) {
                           $query->where('surat_permohonan.rw_id', \Request::input('rw_id'));
                        }
                        if (\Request::input('rt_id')) {
                           $query->where('surat_permohonan.rt_id', \Request::input('rt_id'));
                        }
                     })
                     ->get();

   $oneDay = 0; $oneToThree = 0; $moreThanThree = 0;
   $arrTotalProsesSurat = [];

   foreach ($proses_surat as $key => $val) {
      if ($val->tgl_approve_rw) {
         $days = (strtotime($val->tgl_approve_rw) - strtotime($val->tgl_permohonan)) / (60 * 60 * 24);
         if ($days == 0) {
            $oneDay++;
         }
         else if ($days <= 3) {
            $oneToThree++;
         }
         else if ($days > 3) {
            $moreThanThree++;
         }
      }
   }

   $arrData = [
      'less_than_1' => $oneDay,
      'between1_and_3' => $oneToThree,
      'more_than_3' => $moreThanThree
   ];

   $duplicate = in_array($arrData, $arrTotalProsesSurat);
   if(!$duplicate){
      array_push($arrTotalProsesSurat, $arrData);
   }

   $isData = 0;
   if ($arrData['less_than_1'] && $arrData['between1_and_3'] && $arrData['more_than_3']) {
      $isData++;
   }
   //End Dashboard Proses Surat Script

   //Dashboard Jenis Surat Script
   $jenis_surat = \DB::table('surat_permohonan')
                     ->select('surat_permohonan.jenis_surat_id','jenis_surat.jenis_permohonan')
                     ->when(!$isAdmin, function ($query) use ($rwID) {
                           $query->where('surat_permohonan.rw_id', $rwID);
                     })
                     ->when(1, function ($query) {
                        if (\Request::input('province_id')) {
                           $query->where('surat_permohonan.province_id', \Request::input('province_id'));
                        }
                        if (\Request::input('city_id')) {
                           $query->where('surat_permohonan.city_id', \Request::input('city_id'));
                        }
                        if (\Request::input('subdistrict_id')) {
                           $query->where('surat_permohonan.subdistrict_id', \Request::input('subdistrict_id'));
                        }
                        if (\Request::input('kelurahan_id')) {
                           $query->where('surat_permohonan.kelurahan_id', \Request::input('kelurahan_id'));
                        }
                        if (\Request::input('rw_id')) {
                           $query->where('surat_permohonan.rw_id', \Request::input('rw_id'));
                        }
                        if (\Request::input('rt_id')) {
                           $query->where('surat_permohonan.rt_id', \Request::input('rt_id'));
                        }
                     })
                     ->join('jenis_surat','jenis_surat.jenis_surat_id','surat_permohonan.jenis_surat_id')
                     ->get();

   $arrTotalSurat = [];

   foreach($jenis_surat as $key => $val){
      $surat = [
         'jenis_surat' => $val->jenis_permohonan, 
         'total_jenis_surat' => \helper::countJenisSurat($val->jenis_surat_id, Session::get('dashboardDataParams'))
      ];

      $duplicate = in_array($surat,$arrTotalSurat);
      if(!$duplicate){
         array_push($arrTotalSurat,$surat);
      }
   }
   //End Dashboard Jenis Surat Script

   //Dashboard Jumlah Kegiatan per Program Tahun Ini
   $yearNow = date('Y');
   $agendaKegiatanThn = \DB::table('agenda')
                        ->select('agenda.nama_agenda', 'agenda.program_id', 'program.nama_program')
                        ->when(!$isAdmin, function ($query) use ($rwID) {
                              $query->where('program.rw_id', $rwID);
                        })
                        ->whereYear('agenda.tanggal','=',$yearNow)
                        ->when(1, function ($query) {
                           if (\Request::input('province_id')) {
                              $query->where('program.province_id', \Request::input('province_id'));
                           }
                           if (\Request::input('city_id')) {
                              $query->where('program.city_id', \Request::input('city_id'));
                           }
                           if (\Request::input('subdistrict_id')) {
                              $query->where('program.subdistrict_id', \Request::input('subdistrict_id'));
                           }
                           if (\Request::input('kelurahan_id')) {
                              $query->where('program.kelurahan_id', \Request::input('kelurahan_id'));
                           }
                           if (\Request::input('rw_id')) {
                              $query->where('program.rw_id', \Request::input('rw_id'));
                           }
                           if (\Request::input('rt_id')) {
                              $query->where('program.rt_id', \Request::input('rt_id'));
                           }
                        })
                        ->join('program', 'program.program_id', 'agenda.program_id')
                        ->get();
   $arrKegiatanThn = [];
   
   foreach($agendaKegiatanThn as $key => $val){
      $kegiatanName = [
         'program' => $val->program_id, 
         'nama_kegiatan' => $val->nama_agenda, 
         'nama_program' => $val->nama_program, 
         'total_kegiatan_per_program_tahun_ini' => \helper::countKegiatanPerProgram($val->program_id, Session::get('dashboardDataParams'))
      ];
      
      $duplicate = in_array($kegiatanName, $arrKegiatanThn);
      if(!$duplicate){
         array_push($arrKegiatanThn, $kegiatanName);
      }
   }
   //End Dashboard Jumlah Kegiatan per Program Tahun Ini

   //Dashboard Jumlah Kegiatan 3 Tahun Terakhir
   $year = date('Y',strtotime('-3 year'));
   $agendaKegiatan3Thn = \DB::table('agenda')
                           ->select('agenda.nama_agenda', 'agenda.program_id', 'program.nama_program', 'agenda.tanggal')
                           ->whereYear('agenda.tanggal','>=',$year)
                           ->when(!$isAdmin, function ($query) use ($rwID) {
                                 $query->where('program.rw_id', $rwID);
                           })
                           ->when(1, function ($query) {
                              if (\Request::input('province_id')) {
                                 $query->where('program.province_id', \Request::input('province_id'));
                              }
                              if (\Request::input('city_id')) {
                                 $query->where('program.city_id', \Request::input('city_id'));
                              }
                              if (\Request::input('subdistrict_id')) {
                                 $query->where('program.subdistrict_id', \Request::input('subdistrict_id'));
                              }
                              if (\Request::input('kelurahan_id')) {
                                 $query->where('program.kelurahan_id', \Request::input('kelurahan_id'));
                              }
                              if (\Request::input('rw_id')) {
                                 $query->where('program.rw_id', \Request::input('rw_id'));
                              }
                              if (\Request::input('rt_id')) {
                                 $query->where('program.rt_id', \Request::input('rt_id'));
                              }
                           })
                           ->join('program', 'program.program_id', 'agenda.program_id')
                           ->orderBy('agenda.tanggal', 'ASC')
                           ->get();
   $arrKegiatan3Thn = [];

   foreach($agendaKegiatan3Thn as $key => $val){
      $kegiatan3Thn = [
         'program' => $val->program_id, 
         'nama_kegiatan' => $val->nama_agenda, 
         'nama_program' => $val->nama_program, 
         'total_kegiatan_per_program_3_thn' => \helper::countKegiatan3TahunAkhir(Session::get('dashboardDataParams')),
         'tahun' => date('Y', strtotime($val->tanggal))
      ];

      $duplicate = in_array($kegiatan3Thn,$arrKegiatan3Thn);
      if(!$duplicate){
         array_push($arrKegiatan3Thn, $kegiatan3Thn);
      }
   }
   //End Dashboard Jumlah Kegiatan 3 Tahun Terakhir

   //Dashboard Komposisi Nilai Project per Program
   $nilaiProgram = \DB::table('agenda')
                     ->select('agenda.nilai', 'agenda.program_id', 'program.nama_program')
                     ->when(!$isAdmin, function ($query) use ($rwID) {
                           $query->where('program.rw_id', $rwID);
                     })
                     ->when(1, function ($query) {
                        if (\Request::input('province_id')) {
                           $query->where('program.province_id', \Request::input('province_id'));
                        }
                        if (\Request::input('city_id')) {
                           $query->where('program.city_id', \Request::input('city_id'));
                        }
                        if (\Request::input('subdistrict_id')) {
                           $query->where('program.subdistrict_id', \Request::input('subdistrict_id'));
                        }
                        if (\Request::input('kelurahan_id')) {
                           $query->where('program.kelurahan_id', \Request::input('kelurahan_id'));
                        }
                        if (\Request::input('rw_id')) {
                           $query->where('program.rw_id', \Request::input('rw_id'));
                        }
                        if (\Request::input('rt_id')) {
                           $query->where('program.rt_id', \Request::input('rt_id'));
                        }
                     })
                     ->join('program', 'program.program_id', 'agenda.program_id')
                     ->orderBy('program.nama_program', 'ASC')
                     ->get();
   $arrTotalKomposisiNilaiProgram = [];

   foreach($nilaiProgram as $key => $val){
      $program = [
         'nama_program' => $val->nama_program,
         'komposisi_nilai_program' => \helper::sumKomposisiNilaiProgram($val->program_id, Session::get('dashboardDataParams'))
      ];
      
      $duplicate = in_array($program,$arrTotalKomposisiNilaiProgram);
      if(!$duplicate){
         array_push($arrTotalKomposisiNilaiProgram,$program);
      }
   }
   //End Dashboard Komposisi Nilai Project per Program

   //Dashboard Komposisi Nilai Project per Sumber Biaya
   $nilaiSumberBiaya = \DB::table('agenda')
                     ->select('agenda.nilai', 'agenda.kat_sumber_biaya_id', 'kat_sumber_biaya.nama_sumber')
                     ->when(!$isAdmin, function ($query) use ($rwID) {
                           $query->where('program.rw_id', $rwID);
                     })
                     ->when(1, function ($query) {
                        if (\Request::input('province_id')) {
                           $query->where('program.province_id', \Request::input('province_id'));
                        }
                        if (\Request::input('city_id')) {
                           $query->where('program.city_id', \Request::input('city_id'));
                        }
                        if (\Request::input('subdistrict_id')) {
                           $query->where('program.subdistrict_id', \Request::input('subdistrict_id'));
                        }
                        if (\Request::input('kelurahan_id')) {
                           $query->where('program.kelurahan_id', \Request::input('kelurahan_id'));
                        }
                        if (\Request::input('rw_id')) {
                           $query->where('program.rw_id', \Request::input('rw_id'));
                        }
                        if (\Request::input('rt_id')) {
                           $query->where('program.rt_id', \Request::input('rt_id'));
                        }
                     })
                     ->join('program', 'program.program_id', 'agenda.program_id')
                     ->join('kat_sumber_biaya', 'kat_sumber_biaya.kat_sumber_biaya_id', 'agenda.kat_sumber_biaya_id')
                     ->orderBy('kat_sumber_biaya.nama_sumber', 'ASC')
                     ->get();
   $arrTotalKomposisiNilaiSumberBiaya = [];

   foreach($nilaiSumberBiaya as $key => $val){
      $sumber_biaya = [
         'nama_sumber' => $val->nama_sumber,
         'komposisi_nilai_sumber_biaya' => \helper::sumKomposisiNilaiSumberBiaya($val->kat_sumber_biaya_id, Session::get('dashboardDataParams'))
      ];
      
      $duplicate = in_array($sumber_biaya,$arrTotalKomposisiNilaiSumberBiaya);
      if(!$duplicate){
         array_push($arrTotalKomposisiNilaiSumberBiaya,$sumber_biaya);
      }
   }
   //End Dashboard Komposisi Nilai Project per Sumber Biaya

   //Dashboard Total Nilai PBB Yang Sudah Dibayar Dalam 3 Tahun Terakhir
   $year = date('Y',strtotime('-3 year'));

   $nilaiPBB3TahunAkhir = \DB::table('pbb')->select(
                              \DB::raw("(sum(nilai)) as nilai_pbb"),
                              \DB::raw("tahun_pajak")
                        )
                        ->join('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'pbb.anggota_keluarga_id')
                        ->where('pbb.tahun_pajak','>=',$year)
                        ->when(!$isAdmin, function ($query) use ($rwID) {
                           $query->where('anggota_keluarga.rw_id', $rwID);
                        })
                        ->when(1, function ($query) {
                           if (\Request::input('province_id')) {
                              $query->where('anggota_keluarga.province_id', \Request::input('province_id'));
                           }
                           if (\Request::input('city_id')) {
                              $query->where('anggota_keluarga.city_id', \Request::input('city_id'));
                           }
                           if (\Request::input('subdistrict_id')) {
                              $query->where('anggota_keluarga.subdistrict_id', \Request::input('subdistrict_id'));
                           }
                           if (\Request::input('kelurahan_id')) {
                              $query->where('anggota_keluarga.kelurahan_id', \Request::input('kelurahan_id'));
                           }
                           if (\Request::input('rw_id')) {
                              $query->where('anggota_keluarga.rw_id', \Request::input('rw_id'));
                           }
                           if (\Request::input('rt_id')) {
                              $query->where('anggota_keluarga.rt_id', \Request::input('rt_id'));
                           }
                        })
                        ->groupBy(\DB::raw("tahun_pajak"))
                        ->orderBy('tahun_pajak', 'asc')
                        ->get();
         
   $arrNilaiPBB3ThnAkhir = [];

   foreach($nilaiPBB3TahunAkhir as $key => $val){
      $nilaiPBB3ThnAkhir = [
         'nilai_pbb_3_thn_akhir' => $val->nilai_pbb,
         'tahun' => $val->tahun_pajak
      ];

      $duplicate = in_array($nilaiPBB3ThnAkhir,$arrNilaiPBB3ThnAkhir);
      if(!$duplicate){
         array_push($arrNilaiPBB3ThnAkhir, $nilaiPBB3ThnAkhir);
      }
   }
   //End Dashboard Total Nilai PBB Yang Sudah Dibayar Dalam 3 Tahun Terakhir
?>

<div class="row">
   {{-- Proses Surat --}}
   @if ($isData == 1)
   <div class="col-lg-5">
      <!--begin::Card-->
      <div class="card card-custom gutter-b">
         <div class="card-header">
            <div class="card-title">
               <h3 class="card-label">Proses Surat</h3>
            </div>
         </div>
         <div class="card-body">
            <!--begin::Chart-->
            <div id="chart_proses_surat" class="d-flex justify-content-center"></div>
            <!--end::Chart-->
         </div>
      </div>
      <!--end::Card-->
   </div>
   @endif
   {{-- End Proses Surat --}}

   {{-- Surat Berdasarkan Kategori Surat --}}
   @if ($arrTotalSurat)
   <div class="col-lg-7">
      <!--begin::Card-->
      <div class="card card-custom gutter-b">
         <div class="card-header">
            <div class="card-title">
               <h3 class="card-label">Surat Berdasarkan Kategori Surat</h3>
            </div>
         </div>
         <div class="card-body">
            <!--begin::Chart-->
            <div id="chart_surat_by_kat"></div>
            <!--end::Chart-->
         </div>
      </div>
      <!--end::Card-->
   </div>
   @endif
   {{-- End Surat Berdasarkan Kategori Surat --}}

   {{-- Dashboard Jumlah Kegiatan per Program per Tahun ini --}}
   @if ($arrKegiatanThn)
   <div class="col-lg-6">
      <!--begin::Card-->
      <div class="card card-custom gutter-b">
         <div class="card-header">
            <div class="card-title">
               <h3 class="card-label">Jumlah Kegiatan per Program per Tahun ini</h3>
            </div>
         </div>
         <div class="card-body">
            <!--begin::Chart-->
            <div id="jmlh_kegiatan_per_program_per_thn_ini"></div>
            <!--end::Chart-->
         </div>
      </div>
      <!--end::Card-->
   </div>
   @endif
   {{-- End Dashboard Jumlah Kegiatan per Program per Tahun ini --}}
   
   {{-- Dashboard Jumlah Kegiatan 3 tahun Terakhir --}}
   @if ($arrKegiatan3Thn)
   <div class="col-lg-6">
      <!--begin::Card-->
      <div class="card card-custom gutter-b">
         <div class="card-header">
            <div class="card-title">
               <h3 class="card-label">Jumlah Kegiatan per Program 3 Tahun Terakhir</h3>
            </div>
         </div>
         <div class="card-body">
            <!--begin::Chart-->
            <div id="jumlah_kegiatan_3_thn_akhir"></div>
            <!--end::Chart-->
         </div>
      </div>
      <!--end::Card-->
   </div>
   @endif
   {{-- End Dashboard Jumlah Kegiatan 3 tahun Terakhir --}}

   {{-- Dashboard Komposisi Nilai Project per Program --}}
   @if ($arrTotalKomposisiNilaiProgram)
   <div class="col-lg-6">
      <!--begin::Card-->
      <div class="card card-custom gutter-b">
         <div class="card-header">
            <div class="card-title">
               <h3 class="card-label">Komposisi Nilai Project per Program</h3>
            </div>
         </div>
         <div class="card-body">
            <!--begin::Chart-->
            <div id="komposisi_nilai_project_per_program" class="d-flex justify-content-center"></div>
            <!--end::Chart-->
         </div>
      </div>
      <!--end::Card-->
   </div>
   @endif
   {{-- End Dashboard Komposisi Nilai Project per Program --}}

   {{-- Dashboard Komposisi Nilai Project per Sumber Biaya --}}
   @if ($arrTotalKomposisiNilaiSumberBiaya)
   <div class="col-lg-6">
      <!--begin::Card-->
      <div class="card card-custom gutter-b">
         <div class="card-header">
            <div class="card-title">
               <h3 class="card-label">Komposisi Nilai Project per Sumber Biaya</h3>
            </div>
         </div>
         <div class="card-body">
            <!--begin::Chart-->
            <div id="komposisi_nilai_project_per_sumber_biaya"></div>
            <!--end::Chart-->
         </div>
      </div>
      <!--end::Card-->
   </div>
   @endif
   {{-- End Dashboard Komposisi Nilai Project per Sumber Biaya --}}

   <div class="col-md-12">
      @include('dashboard.dashboardPBB')
   </div>
</div>

<script>
   // Shared Colors Definition
   const primary = '#6993FF';
   const success = '#1BC5BD';
   const info = '#8950FC';
   const warning = '#FFA800';
   const danger = '#F64E60';

   var KTApexChartsDemo = function () {
      // Private functions
      var _prosesSuratPie = function () {
         const all_proses_surat = JSON.parse('<?= json_encode($arrTotalProsesSurat) ?>');
         const apexChart = "#chart_proses_surat";

         var options = {
            series: [
               all_proses_surat[0].less_than_1,
               all_proses_surat[0].between1_and_3,
               all_proses_surat[0].more_than_3,
            ],
            chart: {
               width: 477,
               type: 'donut',
            },
            labels: ['Kurang Dari 1 Hari', 'Antara 1 - 3 Hari', 'Lebih Dari 3 Hari'],
            legend: {
               position: 'bottom',
            },
            tooltip: {
               y: {
                  formatter: function (val) {
                     return val + " Surat"
                  }
               }
            },
            responsive: [{
               breakpoint: 480,
               options: {
                  chart: {
                     width: 250,
                     height: 300
                  },
                  legend: {
                     position: 'bottom'
                  }
               }
            }],
            colors: [primary, warning, danger]
         };

         var chart = new ApexCharts(document.querySelector(apexChart), options);
         chart.render();
      }   

      var _suratByKategoriSurat = function () {
         const all_jenis_surat = @json($arrTotalSurat);
         
         const apexChart = "#chart_surat_by_kat";
         var options = {
            series: [{
               name: 'Total Surat',
               data: all_jenis_surat.map(({total_jenis_surat}) => total_jenis_surat),
            },],
            chart: {
               type: 'bar',
               height: 350
            },
            plotOptions: {
               bar: {
                  horizontal: false,
                  columnWidth: '70%',
                  distributed: true,
               },
            },
            dataLabels: {
               enabled: false
            },
            stroke: {
               show: true,
               width: 2,
            },
            xaxis: {
               categories: all_jenis_surat.map(({jenis_surat}) => jenis_surat),
               labels: {
                  show: true,
                  rotate: -45,
                  trim: true,
               }
            },
            yaxis: {
               title: {
                  text: "Total Surat Berdasarkan Kategori Surat",
                  style: {
                     color: primary,
                  }
               }
            },
            fill: {
               opacity: 1
            },
            tooltip: {
               tooltip: {
                     enabled: true
               }
            },
         };

         var chart = new ApexCharts(document.querySelector(apexChart), options);
         chart.render();
      }

      var _jumlahKegiatanPerProgramPerTahunIni = function () {
         var total_agenda_kegiatan_thn_ini = @json($arrKegiatanThn);

         const newMapData = total_agenda_kegiatan_thn_ini.map(function (val, index, curr) {
            return {program: val.program, nama_program: val.nama_program, total_kegiatan_per_program_tahun_ini: val.total_kegiatan_per_program_tahun_ini}
         })

         let filterData = [];
         if (newMapData) {
            newMapData.forEach(element => {
               if (filterData.findIndex(val => val.program == element.program ) == - 1 ) {
                  filterData.push({program: element.program, category: element.nama_program, data: element.total_kegiatan_per_program_tahun_ini})
               }
            });
         }

         let titles = [];
         titles = filterData.map(function (val, index, curr) {
            return val.category
         })

         const apexChart = "#jmlh_kegiatan_per_program_per_thn_ini";
         var options = {
            series: [
               {
                  name: 'Total Kegiatan',
                  data: filterData.map(val => val.data)
               }
            ],
            chart: {
               type: 'bar',
               height: 400
            },
            plotOptions: {
               bar: {
                  horizontal: false,
                  columnWidth: '35%',
                  distributed: true,
               },
            },
            dataLabels: {
               enabled: false
            },
            stroke: {
               show: true,
               width: 2,
               colors: ['transparent']
            },
            xaxis: {
               categories: titles,
               labels: {
                  show: true,
                  rotate: -45,
                  trim: true,
               }
            },
            yaxis: {
               title: {
                  text: 'Total Kegiatan Per Program Per Tahun Ini'
               },
            },
            legend: {
               position: 'bottom',
               horizontalAlign: 'left'
            },
            fill: {
               opacity: 1
            },
            tooltip: {
               y: {
                  formatter: function (val) {
                     return val + " Kegiatan"
                  }
               },
               fixed: {
                  enabled: true,
                  position: 'topRight',
                  offsetX: 10,
                  offsetY: 0,
               },
            },
            colors: [primary, success, warning, danger, info]
         };

         var chart = new ApexCharts(document.querySelector(apexChart), options);
         chart.render();
      }

      var _jumlahKegiatan3ThnAkhir = function () {
         var total_kegiatan_per_program_3thn = @json($arrKegiatan3Thn);

         let newMapData = [];
         total_kegiatan_per_program_3thn.forEach(element => {
         if (newMapData.findIndex(val => val.name == element.tahun ) == - 1 ) {
               newMapData.push({name: element.tahun, total_kegiatan_per_program_3_thn: element.total_kegiatan_per_program_3_thn})
         } else {
               const index = newMapData.findIndex(val => val.name == element.tahun )
               newMapData[index] = {name: newMapData[index].name, total_kegiatan_per_program_3_thn: newMapData[index].total_kegiatan_per_program_3_thn + 1}
            }
         });

         let filterData = [];
         if (newMapData) {
            newMapData.forEach(element => {
               if (filterData.findIndex(val => val.category == element.tahun ) == - 1 ) {
                  filterData.push({category: element.name, data: element.total_kegiatan_per_program_3_thn})
               }
            });
         }

         let titles = [];
            titles = filterData.map(function (val, index, curr) {
               return val.category
         })

         const apexChart = "#jumlah_kegiatan_3_thn_akhir";
         var options = {
            series: [
               {
                  name: 'Total Kegiatan',
                  data: filterData.map(val => val.data)
               }
            ],
            chart: {
               type: 'bar',
               height: 350,
            },
            plotOptions: {
               bar: {
                  horizontal: true,
                  columnWidth: '50%',
                  distributed: true,
               },
            },
            dataLabels: {
               enabled: false
            },
            stroke: {
               show: true,
               width: 2,
               colors: ['transparent']
            },
            xaxis: {
               categories: titles,
               title: {
                  text: 'Total Kegiatan Per Program Per 3 Tahun Terakhir'
               },
            },
            fill: {
               opacity: 1
            },
            tooltip: {
               y: {
                  formatter: function (val) {
                     return val + " Kegiatan"
                  }
               }
            },
            colors: [primary, success, warning, danger, info]
         };

         var chart = new ApexCharts(document.querySelector(apexChart), options);
         chart.render();
      }

      var _komposisiNilaiProjectPerProgram = function () {
         const all_komposisi_nilai_program = @json($arrTotalKomposisiNilaiProgram);
         const arrOfStr = all_komposisi_nilai_program.map(({komposisi_nilai_program}) => komposisi_nilai_program);
         const arrOfNum = arrOfStr.map(str => {
            return Number(str);
         });

         const apexChart = "#komposisi_nilai_project_per_program";
         var options = {
            series: arrOfNum,
            chart: {
               width: 500,
               type: 'pie',
            },
            labels: all_komposisi_nilai_program.map(({nama_program}) => nama_program),
            legend: {
               position: 'bottom',
               horizontalAlign: 'left'
            },
            tooltip: {
               y: {
                  formatter: function (val) {
                     var number_string = val.toString(),
                        sisa   = number_string.length % 3,
                        rupiah = number_string.substr(0, sisa),
                        ribuan = number_string.substr(sisa).match(/\d{3}/g);
                           
                     if (ribuan) {
                        separator = sisa ? '.' : '';
                        rupiah += separator + ribuan.join('.');
                     }

                     return "Rp" + rupiah
                  }
               }
            },
            responsive: [{
               breakpoint: 480,
               options: {
                  chart: {
                     width: 250,
                     height: 400
                  },
                  legend: {
                     position: 'bottom'
                  }
               }
            }],
            colors: [primary, success, warning, danger, info]
         };

         var chart = new ApexCharts(document.querySelector(apexChart), options);
         chart.render();
      }

      var _komposisiNilaiProjectPerSumberBiaya = function () {
         const all_komposisi_nilai_sumber_biaya = @json($arrTotalKomposisiNilaiSumberBiaya);
         const arrOfStr = all_komposisi_nilai_sumber_biaya.map(({komposisi_nilai_sumber_biaya}) => komposisi_nilai_sumber_biaya);
         const arrOfNum = arrOfStr.map(str => {
            return Number(str);
         });

         const apexChart = "#komposisi_nilai_project_per_sumber_biaya";

         var options = {
            series: arrOfNum,
            chart: {
               width: 477,
               type: 'donut',
            },
            labels: all_komposisi_nilai_sumber_biaya.map(({nama_sumber}) => nama_sumber),
            legend: {
               position: 'bottom',
            },
            tooltip: {
               y: {
                  formatter: function (val) {
                     var number_string = val.toString(),
                        sisa   = number_string.length % 3,
                        rupiah = number_string.substr(0, sisa),
                        ribuan = number_string.substr(sisa).match(/\d{3}/g);
                           
                     if (ribuan) {
                        separator = sisa ? '.' : '';
                        rupiah += separator + ribuan.join('.');
                     }

                     return "Rp" + rupiah
                  }
               }
            },
            responsive: [{
               breakpoint: 480,
               options: {
                  chart: {
                     width: 250,
                     height: 250
                  },
                  legend: {
                     position: 'bottom'
                  }
               }
            }],
            colors: [primary, warning, danger]
         };

         var chart = new ApexCharts(document.querySelector(apexChart), options);
         chart.render();
      }

      var _totalNilaiPBB3TahunAkhir = function () {
         var nilaiPBB = @json($arrNilaiPBB3ThnAkhir);

         let newMapData = [];
         nilaiPBB.forEach(element => {
         if (newMapData.findIndex(val => val.name == element.tahun ) == - 1 ) {
               newMapData.push({name: element.tahun, nilai_pbb_3_thn_akhir: element.nilai_pbb_3_thn_akhir})
         } else {
               const index = newMapData.findIndex(val => val.name == element.tahun )
               newMapData[index] = {name: newMapData[index].name, nilai_pbb_3_thn_akhir: newMapData[index].nilai_pbb_3_thn_akhir + 1}
            }
         });

         let filterData = [];
         if (newMapData) {
            newMapData.forEach(element => {
               if (filterData.findIndex(val => val.category == element.tahun ) == - 1 ) {
                  filterData.push({category: element.name, data: element.nilai_pbb_3_thn_akhir})
               }
            });
         }

         let titles = [];
            titles = filterData.map(function (val, index, curr) {
               return val.category
         })
         
         const apexChart = "#total_nilai_pbb";
         var options = {
            series: [{
               name: "Nilai PBB Warga",
               data: filterData.map(val => val.data)
            }],
            chart: {
               height: 350,
               type: 'line',
               zoom: {
                  enabled: false
               }
            },
            dataLabels: { 	
               enabled: false
            },
            stroke: {
               curve: 'straight'
            },
            grid: {
               row: {
                  colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                  opacity: 0.5
               },
            },
            tooltip: {
               y: {
                  formatter: function (val) {
                     var number_string = val.toString(),
                        sisa   = number_string.length % 3,
                        rupiah = number_string.substr(0, sisa),
                        ribuan = number_string.substr(sisa).match(/\d{3}/g);
                           
                     if (ribuan) {
                        separator = sisa ? '.' : '';
                        rupiah += separator + ribuan.join('.');
                     }

                     return "Rp" + rupiah
                  }
               }
            },
            yaxis: {
               title: {
                  text: 'Nilai PBB Warga'
               },
            },
            xaxis: {
               categories: titles,
            },
            colors: [primary]
         };

         var chart = new ApexCharts(document.querySelector(apexChart), options);
         chart.render();
      }

      return {
         // public functions
         init: function () {
            _prosesSuratPie();
            _suratByKategoriSurat();
            _jumlahKegiatanPerProgramPerTahunIni();
            _jumlahKegiatan3ThnAkhir();
            _komposisiNilaiProjectPerProgram();
            _komposisiNilaiProjectPerSumberBiaya();
            _totalNilaiPBB3TahunAkhir();
         }
      };
   }();

   jQuery(document).ready(function () {
      KTApexChartsDemo.init();
   });
</script>

<script>
   init()

   function init(){
      counterNumberAnimation()
   }

   function counterNumberAnimation()
   {
      $('.count-number-pbb').each(function(){
         const $this = $(this)
   
         $({Counter:-1}).animate({Counter: $this.text()},{
            duration:2000,
            easing:'swing',
            step:now => $this.text(Math.ceil(now))
         });
      });
   }

   $(document).ready(function () {
      $('select[name=tahun_pajak]').select2({ placeholder: '-- Pilih Tahun Pajak --', width: '100%' });
      
      $('select[name=tahun_pajak]').change(function() {
         var tahunPajak = $(this).val();

         $.ajax({
            type: "get",
            url: `/searchTahunPajak/${tahunPajak}`,
            dataType: 'json',
            success: function(response) {
               var jumlahPBB = response['jumlah_pbb_terdistribusi'];
               $('#jumlah-pbb').text(jumlahPBB);

               var jumlahSudahBayar = response['jumlah_sudah_bayar'];
               $('#jumlah_sudah_membayar').text(jumlahSudahBayar);

               var totalNilai = response['total'];
               $('#total_nilai').text(numberWithCommas(totalNilai));
            },
            error: function(err) {
               swal.fire({
                  title: 'Maaf, Terjadi Kesalahan!',
                  icon: 'error',
               });
            }
         });
      })
   });

   function numberWithCommas(val) {
      var number_string = val.toString(),
         sisa   = number_string.length % 3,
         rupiah = number_string.substr(0, sisa),
         ribuan = number_string.substr(sisa).match(/\d{3}/g);
            
      if (ribuan) {
         separator = sisa ? '.' : '';
         rupiah += separator + ribuan.join('.');
      }

      return rupiah
   }
</script>
