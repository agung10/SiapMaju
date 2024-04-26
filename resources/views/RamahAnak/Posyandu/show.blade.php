@extends('layouts.master')
@section('content')
    <style type="text/css">
        textarea { overflow-y:auto; resize:none; }
    </style>
    <div class="container">
        <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
            <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <h5 class="text-dark font-weight-bold my-1 mr-5">
                            Detail Vaksinasi
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-custom gutter-b">
            <form id="headerForm">
                <div class="card-body row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Vaksin</label>
                            <input type="text" class="form-control" value="{{ ((!empty($data)) ? ($data->vaccine->nama_vaksin) : (null)) }}" readonly />
                        </div>
                        <div class="form-group">
                            <label>Nama Anak</label>
                            <input type="text" class="form-control" value="{{ ((!empty($data)) ? ($data->familyMember->nama) : (null)) }}" readonly />
                        </div>
                        <div class="form-group">
                            <label>Tanggal Vaksinasi</label>
                            <input type="text" class="form-control" value="{{ ((!empty($data)) ? (date('d M Y', strtotime($data->tgl_input))) : (null)) }}" readonly />
                        </div>
                        <div class="form-group">
                            <label>Keterangan Vaksinasi</label>
                            <textarea class="form-control" rows="5" readonly>{{ ((!empty($data)) ? ($data->ket_vaksinasi) : (null)) }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Berat Badan (kg)</label>
                            <input type="text" class="form-control" value="{{ ((!empty($data)) ? ($data->berat) : (null)) }}" readonly />
                        </div>
                        <div class="form-group">
                            <label>Tinggi Badan (cm)</label>
                            <input type="text" class="form-control" value="{{ ((!empty($data)) ? ($data->tinggi) : (null)) }}" readonly />
                        </div>
                        <div class="form-group">
                            <label>Lingkar Kepala (cm)</label>
                            <input type="text" class="form-control" value="{{ ((!empty($data)) ? ($data->lingkar_kepala) : (null)) }}" readonly />
                        </div>
                        <div class="form-group">
                            <label>Nilai Stunting</label>
                            <input type="text" class="form-control" value="{{ ((!empty($data)) ? ($data->nilai_stunting) : (null)) }}" readonly />
                        </div>
                        <div class="form-group">
                            <label>Keluhan</label>
                            <textarea class="form-control" rows="5" readonly>{{ ((!empty($data)) ? ($data->keluhan) : (null)) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    @include('partials.buttons.submit')
                </div>
            </form>
        </div>
    </div>
@endsection