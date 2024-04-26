<?php
   $logedUSer = \DB::table('users')
                     ->select('anggota_keluarga.is_rt','anggota_keluarga.is_rw','users.is_admin','keluarga.rt_id','keluarga.rw_id')
                     ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                     ->leftJoin('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                     ->where('user_id',\Auth::user()->user_id)
                     ->first();

   $isRt = $logedUSer->is_rt == true;
   $isRw = $logedUSer->is_rw == true;
   $isAdmin = $logedUSer->is_admin == true;
   $isPetugas = $isRt == true || $isRw == true || $isAdmin == true;
   $isWarga = $isRt != true && $isRw != true && $isAdmin != true;

   //Dashobard List Permohonan
        $listPermohonan = \DB::table('surat_permohonan')
                              ->select('surat_permohonan.no_surat',
                                       'surat_permohonan.surat_permohonan_id',
                                       'surat_permohonan.hal',
                                       'surat_permohonan.nama_lengkap',
                                       'surat_permohonan.approve_draft',
                                       'surat_permohonan.petugas_rt_id',
                                       'surat_permohonan.petugas_rw_id'
                                       )
                              ->join('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','surat_permohonan.anggota_keluarga_id')
                              ->join('rt','rt.rt_id','surat_permohonan.rt_id')
                              ->join('rw','rw.rw_id','surat_permohonan.rw_id')
                              ->when($isPetugas !== true, function($query){
                                 $query->where('surat_permohonan.anggota_keluarga_id',\Auth::user()->anggota_keluarga_id)
                                       ->orderBy('surat_permohonan.updated_at','desc');
                              })
                              ->when($isRt == true,function($query)use($logedUSer){
                                 $query->where('surat_permohonan.approve_draft',true)
                                       ->where('surat_permohonan.rt_id',$logedUSer->rt_id)
                                       ->orWhere('surat_permohonan.anggota_keluarga_id',\Auth::user()->anggota_keluarga_id)
                                       ->orderBy('surat_permohonan.petugas_rt_id','desc');
                              })
                              ->when($isRw== true,function($query)use($logedUSer){
                                 $query->whereNotNull('surat_permohonan.petugas_rt_id')
                                       ->where('surat_permohonan.rw_id',$logedUSer->rw_id)
                                       ->orWhere('surat_permohonan.anggota_keluarga_id',\Auth::user()->anggota_keluarga_id)
                                       ->orderBy('surat_permohonan.petugas_rw_id','desc');;
                              })
                              ->when($isAdmin == true,function($query){
                                 $query->orderBy('surat_permohonan.updated_at','desc');
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
                              ->take(5)
                              ->get();

   //End Dashobard List Permohonan

   // Dashboard List Transaksi

         $listTransaksi = \DB::table('header_trx_kegiatan')
                              ->select('header_trx_kegiatan.header_trx_kegiatan_id','transaksi.nama_transaksi','kat_kegiatan.nama_kat_kegiatan','anggota_keluarga.nama','header_trx_kegiatan.no_pendaftaran','header_trx_kegiatan.no_bukti','header_trx_kegiatan.user_approval')
                              ->join('transaksi','transaksi.transaksi_id','header_trx_kegiatan.transaksi_id')
                              ->join('kat_kegiatan','kat_kegiatan.kat_kegiatan_id','header_trx_kegiatan.kat_kegiatan_id')
                              ->join('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','header_trx_kegiatan.anggota_keluarga_id')
                              ->join('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                              ->when($isWarga,function($query){
                                 $query->where('header_trx_kegiatan.anggota_keluarga_id',\Auth::user()->anggota_keluarga_id)
                                       ->orderBy('header_trx_kegiatan.updated_at','ASC');
                              })
                              ->when($isRt,function($query)use($logedUSer){
                                 $query->where('keluarga.rt_id',$logedUSer->rt_id)
                                       ->orderBy('header_trx_kegiatan.tgl_approval','DESC');
                              })
                              ->when($isRw,function($query)use($logedUSer){
                                 $query->where('keluarga.rw_id',$logedUSer->rw_id)
                                       ->orderBy('header_trx_kegiatan.tgl_approval','DESC');
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
                              ->take(5)
                              ->get();
   // End Dashboard List Transaksi
?>

<style>
   table {
      text-align: center;
   }

   .dashboard3-container {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: row;
      background: #FFFF;
      padding: 10px;
      border-radius: 5px;
      margin: 20px 0px 20px 0px;
      width: 100%;
   }

   .dashboard-laporan-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 49%;
      padding: 10px;
      background: #EFEFEF;
      border-radius: 10px;
      height: 100%;
      margin: 0px 10px 0px 10px;
   }

   /* ScrollBar CSS */

   .table-light::-webkit-scrollbar {
      width: 8px;
   }

   /* Handle */
   .table-light::-webkit-scrollbar-thumb {
      background: #898888;
      border-radius: 10px;
   }

   /* Handle on hover */
   .table-light::-webkit-scrollbar-thumb:hover {
      background: #474747;
   }

   /* End ScrollBar CSS */

   @media screen and (max-width:600px) {
      .dashboard3-container {
         flex-direction: column;
      }

      .dashboard-laporan-container {
         width: 100%;
         margin: 10px;
      }
   }
</style>

<div class="row">
   {{-- List Surat Permohonan --}}
   <div class="col-lg-12">
      <div class="card card-custom gutter-b">
         <!--begin::Header-->
         <div class="card-header border-0 py-5">
            <h3 class="card-title align-items-start flex-column">
               <span class="card-label font-weight-bolder text-dark">List Surat Permohonan</span>
               <span class="text-muted mt-3 font-weight-bold font-size-sm">Data List Surat Permohonan</span>
            </h3>
         </div>
         <!--end::Header-->
         <!--begin::Body-->
         <div class="card-body py-0">
            <!--begin::Table-->
            <div class="table-responsive">
               <table
                  class="table table-head-custom table-vertical-center table-head-bg table-hover table-responsive-md">
                  <thead>
                     <tr>
                        <th scope="col">No</th>
                        <th scope="col">No Surat</th>
                        <th scope="col">Jenis Permohonan</th>
                        <th scope="col">Nama Pemohonan</th>
                        <th scope="col">Aksi</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($listPermohonan as $key => $val)
                     <tr>
                        <th>{{$loop->iteration}}</th>
                        <td>{{$val->no_surat}}</td>
                        <td>{{$val->hal}}</td>
                        <td>{{$val->nama_lengkap}}</td>
                        @if($isPetugas && $val->approve_draft == true)
                        <td>
                           <a class="btn btn-light-primary" href="{{route('Surat.SuratPermohonan'.'.show', \Crypt::encryptString($val->surat_permohonan_id))}}" target="_blank">
                              {{!$val->petugas_rt_id && $isRt ? 'Approval RT' : (!$val->petugas_rw_id && $isRw ? 'Approval RW' : 'Detail')}}
                           </a>
                        </td>
                        @elseif($isAdmin)
                        <td>
                           <span class="label label-lg label-light-warning label-inline">Draft Belum Di Approve</span>
                        </td>
                        @elseif($isWarga)
                        <td>
                           <a class="btn btn-primary" href="{{route('Surat.SuratPermohonan'.'.show', \Crypt::encryptString($val->surat_permohonan_id))}}" target="_blank">
                              {{$val->approve_draft == true ? 'Detail' : 'Approve Draft'}}
                           </a>
                        </td>
                        @endif
                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
            <!--end::Table-->
         </div>
         <!--end::Body-->
      </div>
   </div>

   {{-- List Transaksi --}}
   <div class="col-lg-12">
      <div class="card card-custom gutter-b">
         <!--begin::Header-->
         <div class="card-header border-0 py-5">
            <h3 class="card-title align-items-start flex-column">
               <span class="card-label font-weight-bolder text-dark">List Transaksi</span>
               <span class="text-muted mt-3 font-weight-bold font-size-sm">Data List Transaksi</span>
            </h3>
         </div>
         <!--end::Header-->
         <!--begin::Body-->
         <div class="card-body py-0">
            <!--begin::Table-->
            <div class="table-responsive">
               <table
                  class="table table-head-custom table-vertical-center table-head-bg table-hover table-responsive-md">
                  <thead>
                     <tr>
                        <th scope="col">No</th>
                        <th scope="col">Transaksi</th>
                        <th scope="col">Kategori Kegiatan</th>
                        <th scope="col">No Pendaftaran</th>
                        <th scope="col">Keluarga</th>
                        <th scope="col">Aksi</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($listTransaksi as $key => $val)
                     <tr>
                        <th>{{$loop->iteration}}</th>
                        <td>{{$val->nama_transaksi}}</td>
                        <td>{{$val->nama_kat_kegiatan}}</td>
                        <td>
                           <span class="label label-lg label-light-primary label-inline">{{$val->no_pendaftaran}}</span>
                        </td>
                        <td>{{$val->nama}}</td>
                        @if($isPetugas && !$val->user_approval)
                        <td>
                           <a class="btn btn-light-primary" href="{{route('Transaksi.Approval'.'.show', \Crypt::encryptString($val->header_trx_kegiatan_id))}}" target="_blank">
                              Approval
                           </a>
                        </td>
                        @else
                        <td>
                           <a class="btn btn-light-primary" href="{{route('Transaksi.Header'.'.show', \Crypt::encryptString($val->header_trx_kegiatan_id))}}" target="_blank">
                              Detail
                           </a>
                        </td>
                        @endif
                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
            <!--end::Table-->
         </div>
         <!--end::Body-->
      </div>
   </div>
</div>