<div class="card bg-gray-100 gutter-b">
    <div class="card-header border-0 pb-2">
        <div class="row">
            <div class="col-md-9">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder">PBB</span>
                </h3>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <select class="form-control" name="tahun_pajak">
                        <option></option>
                        @foreach($tahun_pajak as $val)
                        <option value="{{ $val->tahun_pajak }}"> Tahun {{ $val->tahun_pajak }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <!--begin::Body-->
    <div class="card-body p-0 position-relative overflow-hidden">
        <!--begin::Chart-->
        <div class="card-rounded-bottom bg-white" style="height: 100px; min-height: 100px;"></div>
        <!--end::Chart-->
        <!--begin::Stats-->
        <div class="card-spacer mt-n40">
            <div class="row">
                <div class="col-md-4 mb-5">
                    <!--begin::Stats Widget 30-->
                    <div class="card card-custom bg-primary bg-hover-state-primary card-stretchmb-0">
                        <!--begin::Body-->
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-center my-3">
                                <div class="d-flex flex-column text-center">
                                    <span class="text-white font-weight-bolder font-size-h1">
                                        <span class="count-number-pbb text-white">
                                            @if(Session::has('dashboardDataParams'))
                                            <span class="count-number-pbb text-white">{{\helper::countDataNOP(Session::get('dashboardDataParams'))}}</span>
                                            @else
                                            <span class="count-number-pbb text-white">{{\helper::countDataNOP([])}}</span>
                                            @endif
                                        </span>
                                    </span>
                                    <span class="text-white font-weight-bold font-size-h4">Jumlah NOP / PBB</span>
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
                                        <span class="count-number-pbb text-white" id="jumlah-pbb">
                                            @if(Session::has('dashboardDataParams'))
                                            <span class="count-number-pbb text-white">{{\helper::countDataPBB('', Session::get('dashboardDataParams'))}}</span>
                                            @else
                                            <span class="count-number-pbb text-white">{{\helper::countDataPBB('', [])}}</span>
                                            @endif
                                        </span>
                                    </span>
                                    <span class="text-white font-weight-bold font-size-h4">Jumlah PBB Terdistribusi</span>
                                </div>
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Stats Widget 30-->
                </div>
                <div class="col-md-4 mb-5">
                    <!--begin::Stats Widget 30-->
                    <div class="card card-custom bg-success bg-hover-state-success card-stretchmb-0">
                        <!--begin::Body-->
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-center my-3">
                                <div class="d-flex flex-column text-center">
                                    <span class="text-white font-weight-bolder font-size-h1">
                                        <span class="count-number-pbb text-white" id="jumlah_sudah_membayar">
                                            @if(Session::has('dashboardDataParams'))
                                            <span class="count-number-pbb text-white">{{\helper::countDataPBB('jumlah_sudah_membayar', Session::get('dashboardDataParams'))}}</span>
                                            @else
                                            <span class="count-number-pbb text-white">{{\helper::countDataPBB('jumlah_sudah_membayar', [])}}</span>
                                            @endif
                                        </span>
                                    </span>
                                    <span class="text-white font-weight-bold font-size-h4">Jumlah Sudah Membayar</span>
                                </div>
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Stats Widget 30-->
                </div>

                <div class="col-md-12 mb-5">
                    <!--begin::Stats Widget 30-->
                    <div class="card card-custom bg-success bg-hover-state-success card-stretchmb-0">
                        <!--begin::Body-->
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-center my-3">
                                <div class="d-flex flex-column text-center">
                                    <span class="text-white font-weight-bolder font-size-h1">
                                        Rp <span class="text-white" id="total_nilai">
                                            @if(Session::has('dashboardDataParams'))
                                                {{\helper::number_formats(\helper::sumNilaiPBBPaid(Session::get('dashboardDataParams')), 'view', 0)}}
                                            @else
                                                {{\helper::number_formats(\helper::sumNilaiPBBPaid([]), 'view', 0)}}
                                            @endif
                                        </span>
                                    </span>
                                    <span class="text-white font-weight-bold font-size-h4">Total Nilai PBB Sudah Dibayar</span>
                                </div>
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Stats Widget 30-->
                </div>
                @if ($arrNilaiPBB3ThnAkhir)
                <div class="col-md-12 mb-5">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b">
                        <!--begin::Header-->
                        <div class="card-header h-auto">
                            <!--begin::Title-->
                            <div class="card-title py-5">
                                <h3 class="card-label">Nilai PBB Warga Yang Sudah Dibayar (Dalam 3 Tahun Terakhir)</h3>
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Header-->
                        <div class="card-body">
                            <!--begin::Chart-->
                            <div id="total_nilai_pbb"></div>
                            <!--end::Chart-->
                        </div>
                    </div>
                    <!--end::Card-->
                </div>    
                @endif
            </div>
        </div>
        <!--end::Stats-->
        <!--end::Body-->
    </div>
    <!--end::Mixed Widget 1-->
</div>