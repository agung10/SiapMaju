<style>
   .dashboard-container {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: row;
      background: #FFFF;
      padding: 10px;
      border-radius: 5px;
      width: 100%;
   }

   .dahsboard {
      display: flex;
      justify-content: flex-start;
      align-items: flex-start;
      flex-direction: column;
      border-radius: 10px;
      width: 25%;
      padding: 10px;
      margin: 10px;
      height: 70%;
   }

   .jumlah-kk {
      background: #FFCD1C;
   }

   .jumlah-wanita {
      background: #52cd1e;
   }

   .jumlah-pria {
      background: #55aefa;
   }

   .jumlah-balita {
      background: #fca74f;
   }

   .count-number {
      font-weight: bold;
      align-self: center;
   }

   .icon-dashboard {
      height: 2rem !important;
      width: 2rem !important;
      font-size: 3rem;
   }

   @media screen and (max-width:600px) {
      .dashboard-container {
         flex-wrap: wrap;
      }

      .dahsboard {
         width: 40%;
         height: 35%;
      }

      .dahsboard h3 {
         font-size: 13px;
         align-self: center;
      }
   }
</style>
<div class="row">
   <div class="col-lg-12 col-xxl-12">
      <!--begin::Mixed Widget 1-->
      <div class="card bg-gray-100 card-stretch gutter-b">
         <div class="card-header border-0 pb-2">
            <h3 class="card-title align-items-start flex-column">
                  @if ($checkAdmin)
                     <span class="card-label font-weight-bolder">Data Warga</span>
                  @else
                     <span class="card-label font-weight-bolder">Data Warga {{ $rwUser->rw }}</span>
                  @endif
               </h3>
         </div>
         <!--begin::Body-->
         <div class="card-body p-0 position-relative overflow-hidden">
            <!--begin::Chart-->
            <div class="card-rounded-bottom bg-white" style="height: 100px; min-height: 100px;"></div>
            <!--end::Chart-->
            <!--begin::Stats-->
            <div class="card-spacer mt-n40">
               <div class="row">
                  {{-- Jumlah --}}
                  <div class="col-md-4 mb-5">
                     <!--begin::Stats Widget 30-->
                     <div class="card card-custom bg-primary bg-hover-state-primary card-stretchmb-0">
                        <!--begin::Body-->
                        <div class="card-body" style="padding: 0 2.25rem;">
                           <div class="d-flex align-items-center justify-content-between my-3">
                              <span class="symbol symbol-20 symbol-white mr-2">
                                 <i class="fas fa-address-book text-white icon-dashboard"></i>
                              </span>
                              <div class="d-flex flex-column">
                                 <div class="d-flex flex-column text-right">
                                    <span class="text-white font-weight-bolder font-size-h1">
                                       <span class="count-number text-white">
                                          @if(Session::has('dashboardDataParams'))
                                          <span class="count-number text-white">{{\helper::countDataKeluarga('kk', Session::get('dashboardDataParams'))}}</span>
                                          @else
                                          <span class="count-number text-white">{{\helper::countDataKeluarga('kk', [])}}</span>
                                          @endif
                                       </span>
                                    </span>
                                    <span class="text-white font-weight-bold font-size-h4">Jumlah KK</span>
                                 </div>
                                 <div class="pemisah" style="border: 1px solid rgba(255, 255, 255, 0.812)"></div>
                                 <div class="d-flex flex-column text-right">
                                    <span class="text-white font-weight-bolder font-size-h1">
                                       <span class="count-number text-white">
                                          @if(Session::has('dashboardDataParams'))
                                          <span class="count-number text-white">{{\helper::countDataKeluarga('warga', Session::get('dashboardDataParams'))}}</span>
                                          @else
                                          <span class="count-number text-white">{{\helper::countDataKeluarga('warga', [])}}</span>
                                          @endif
                                       </span>
                                    </span>
                                    <span class="text-white font-weight-bold font-size-h4">Jumlah Warga</span>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!--end::Body-->
                     </div>
                     <!--end::Stats Widget 30-->
                  </div>
                  <div class="col-md-4 mb-5">
                     <!--begin::Stats Widget 30-->
                     <div class="card card-custom bg-warning bg-hover-state-warning card-stretchmb-0">
                        <!--begin::Body-->
                        <div class="card-body my-2">
                           <div class="d-flex align-items-center justify-content-between my-3">
                              <span class="symbol symbol-20 symbol-white mr-2">
                                 <i class="fas fa-mars text-white icon-dashboard"></i>
                              </span>
                              <div class="d-flex flex-column text-right">
                                 <span class="text-white font-weight-bolder font-size-h1">
                                    <span class="count-number text-white">
                                       @if(Session::has('dashboardDataParams'))
                                       <span class="count-number text-white">{{\helper::countDataKeluarga('pria', Session::get('dashboardDataParams'))}}</span>
                                       @else
                                       <span class="count-number text-white">{{\helper::countDataKeluarga('pria', [])}}</span>
                                       @endif
                                    </span>
                                 </span>
                                 <span class="text-white font-weight-bold font-size-h4">Jumlah Pria</span>
                              </div>
                           </div>
                        </div>
                        <!--end::Body-->
                     </div>
                     <!--end::Stats Widget 30-->
                  </div>
                  <div class="col-md-4 mb-5">
                     <!--begin::Stats Widget 30-->
                     <div class="card card-custom bg-danger bg-hover-state-danger card-stretchmb-0">
                        <!--begin::Body-->
                        <div class="card-body my-2">
                           <div class="d-flex align-items-center justify-content-between my-3">
                              <span class="symbol symbol-20 symbol-white mr-2">
                                 <i class="fas fa-venus text-white icon-dashboard"></i>
                              </span>
                              <div class="d-flex flex-column text-right">
                                 <span class="text-white font-weight-bolder font-size-h1">
                                    <span class="count-number text-white">
                                       @if(Session::has('dashboardDataParams'))
                                       <span class="count-number text-white">{{\helper::countDataKeluarga('wanita', Session::get('dashboardDataParams'))}}</span>
                                       @else
                                       <span class="count-number text-white">{{\helper::countDataKeluarga('wanita', [])}}</span>
                                       @endif
                                    </span>
                                 </span>
                                 <span class="text-white font-weight-bold font-size-h4">Jumlah Wanita</span>
                              </div>
                           </div>
                        </div>
                        <!--end::Body-->
                     </div>
                     <!--end::Stats Widget 30-->
                  </div>

                  {{-- Usia --}}
                  <div class="col-md-3 mb-5">
                     <!--begin::Stats Widget 30-->
                     <div class="card card-custom bg-primary bg-hover-state-primary card-stretchmb-0">
                        <!--begin::Body-->
                        <div class="card-body px-5">
                           <div class="d-flex align-items-center justify-content-between my-3">
                              <span class="symbol symbol-20 symbol-white mr-2">
                                 <i class="fas fa-baby text-white icon-dashboard"></i>
                              </span>
                              <div class="d-flex flex-column text-right">
                                 <span class="text-white font-weight-bolder font-size-h1">
                                    <span class="count-number text-white">
                                       @if(Session::has('dashboardDataParams'))
                                       <span class="count-number text-white">{{\helper::countDataKeluarga('balita', Session::get('dashboardDataParams'))}}</span>
                                       @else
                                       <span class="count-number text-white">{{\helper::countDataKeluarga('balita', [])}}</span>
                                       @endif
                                    </span>
                                 </span>
                                 <span class="text-white font-weight-bold font-size-h4">Usia Balita</span>
                              </div>
                           </div>
                        </div>
                        <!--end::Body-->
                     </div>
                     <!--end::Stats Widget 30-->
                  </div>
                  <div class="col-md-3 mb-5">
                     <!--begin::Stats Widget 30-->
                     <div class="card card-custom bg-success bg-hover-state-success card-stretchmb-0">
                        <!--begin::Body-->
                        <div class="card-body px-5">
                           <div class="d-flex align-items-center justify-content-between my-3">
                              <span class="symbol symbol-20 symbol-white mr-2">
                                 <i class="fas fa-user-graduate text-white icon-dashboard"></i>
                              </span>
                              <div class="d-flex flex-column text-right">
                                 <span class="text-white font-weight-bolder font-size-h1">
                                    <span class="count-number text-white">
                                       @if(Session::has('dashboardDataParams'))
                                       <span class="count-number text-white">{{\helper::countDataKeluarga('pelajar', Session::get('dashboardDataParams'))}}</span>
                                       @else
                                       <span class="count-number text-white">{{\helper::countDataKeluarga('pelajar', [])}}</span>
                                       @endif
                                    </span>
                                 </span>
                                 <span class="text-white font-weight-bold font-size-h4">Usia Pelajar</span>
                              </div>
                           </div>
                        </div>
                        <!--end::Body-->
                     </div>
                     <!--end::Stats Widget 30-->
                  </div>
                  <div class="col-md-3 mb-5">
                     <!--begin::Stats Widget 30-->
                     <div class="card card-custom bg-warning bg-hover-state-warning card-stretchmb-0">
                        <!--begin::Body-->
                        <div class="card-body px-5">
                           <div class="d-flex align-items-center justify-content-between my-3">
                              <span class="symbol symbol-20 symbol-white mr-2">
                                 <i class="fas fa-user-tie text-white icon-dashboard"></i>
                              </span>
                              <div class="d-flex flex-column text-right">
                                 <span class="text-white font-weight-bolder font-size-h1">
                                    <span class="count-number text-white">
                                       @if(Session::has('dashboardDataParams'))
                                       <span class="count-number text-white">{{\helper::countDataKeluarga('produktif', Session::get('dashboardDataParams'))}}</span>
                                       @else
                                       <span class="count-number text-white">{{\helper::countDataKeluarga('produktif', [])}}</span>
                                       @endif
                                    </span>
                                 </span>
                                 <span class="text-white font-weight-bold font-size-h4">Usia Produktif</span>
                              </div>
                           </div>
                        </div>
                        <!--end::Body-->
                     </div>
                     <!--end::Stats Widget 30-->
                  </div>
                  <div class="col-md-3 mb-5">
                     <!--begin::Stats Widget 30-->
                     <div class="card card-custom bg-danger bg-hover-state-danger card-stretchmb-0">
                        <!--begin::Body-->
                        <div class="card-body px-5">
                           <div class="d-flex align-items-center justify-content-between my-3">
                              <span class="symbol symbol-20 symbol-white mr-2">
                                 <i class="fas fa-user-alt text-white icon-dashboard"></i>
                              </span>
                              <div class="d-flex flex-column text-right">
                                 <span class="text-white font-weight-bolder font-size-h1">
                                    <span class="count-number text-white">
                                       @if(Session::has('dashboardDataParams'))
                                       <span class="count-number text-white">{{\helper::countDataKeluarga('lansia', Session::get('dashboardDataParams'))}}</span>
                                       @else
                                       <span class="count-number text-white">{{\helper::countDataKeluarga('lansia', [])}}</span>
                                       @endif
                                    </span>
                                 </span>
                                 <span class="text-white font-weight-bold font-size-h4">Usia Lansia</span>
                              </div>
                           </div>
                        </div>
                        <!--end::Body-->
                     </div>
                     <!--end::Stats Widget 30-->
                  </div>

                  {{-- Warga --}}
                  <div class="col-md-4 mb-5">
                     <!--begin::Stats Widget 30-->
                     <div class="card card-custom bg-success bg-hover-state-success card-stretchmb-0">
                        <!--begin::Body-->
                        <div class="card-body">
                           <div class="d-flex align-items-center justify-content-center my-3">
                              <div class="d-flex flex-column text-center">
                                 <span class="text-white font-weight-bolder font-size-h1">
                                    <span class="count-number text-white">
                                       @if(Session::has('dashboardDataParams'))
                                       <span class="count-number text-white">{{\helper::countDataKeluarga('warga_asli', Session::get('dashboardDataParams'))}}</span>
                                       @else
                                       <span class="count-number text-white">{{\helper::countDataKeluarga('warga_asli', [])}}</span>
                                       @endif
                                    </span>
                                 </span>
                                 <span class="text-white font-weight-bold font-size-h4">Warga Setempat</span>
                              </div>
                           </div>
                        </div>
                        <!--end::Body-->
                     </div>
                     <!--end::Stats Widget 30-->
                  </div>
                  <div class="col-md-4 mb-5">
                     <!--begin::Stats Widget 30-->
                     <div class="card card-custom bg-primary bg-hover-state-primary card-stretchmb-0">
                        <!--begin::Body-->
                        <div class="card-body">
                           <div class="d-flex align-items-center justify-content-center my-3">
                              <div class="d-flex flex-column text-center">
                                 <span class="text-white font-weight-bolder font-size-h1">
                                    <span class="count-number text-white">
                                       @if(Session::has('dashboardDataParams'))
                                       <span class="count-number text-white">{{\helper::countDataKeluarga('warga_asli_tinggal_diluar', Session::get('dashboardDataParams'))}}</span>
                                       @else
                                       <span class="count-number text-white">{{\helper::countDataKeluarga('warga_asli_tinggal_diluar', [])}}</span>
                                       @endif
                                    </span>
                                 </span>
                                 <span class="text-white font-weight-bold font-size-h4">Warga Asli Tinggal Diluar</span>
                              </div>
                           </div>
                        </div>
                        <!--end::Body-->
                     </div>
                     <!--end::Stats Widget 30-->
                  </div>
                  <div class="col-md-4 mb-5">
                     <!--begin::Stats Widget 30-->
                     <div class="card card-custom bg-warning bg-hover-state-warning card-stretchmb-0">
                        <!--begin::Body-->
                        <div class="card-body">
                           <div class="d-flex align-items-center justify-content-center my-3">
                              <div class="d-flex flex-column text-center">
                                 <span class="text-white font-weight-bolder font-size-h1">
                                    <span class="count-number text-white">
                                       @if(Session::has('dashboardDataParams'))
                                       <span class="count-number text-white">{{\helper::countDataKeluarga('warga_pendatang', Session::get('dashboardDataParams'))}}</span>
                                       @else
                                       <span class="count-number text-white">{{\helper::countDataKeluarga('warga_pendatang', [])}}</span>
                                       @endif
                                    </span>
                                 </span>
                                 <span class="text-white font-weight-bold font-size-h4">Warga Pendatang</span>
                              </div>
                           </div>
                        </div>
                        <!--end::Body-->
                     </div>
                     <!--end::Stats Widget 30-->
                  </div>
               </div>
            </div>
            <!--end::Stats-->
            <!--end::Body-->
         </div>
         <!--end::Mixed Widget 1-->
      </div>
   </div>
</div>

   <script>
      init()

   function init(){
      counterNumberAnimation()
   }

   function counterNumberAnimation()
   {
      
      $('.count-number').each(function(){
         const $this = $(this)
   
         $({Counter:-1}).animate({Counter: $this.text()},{
            duration:2000,
            easing:'swing',
            step:now => $this.text(Math.ceil(now))
         });
      });
   }
   </script>