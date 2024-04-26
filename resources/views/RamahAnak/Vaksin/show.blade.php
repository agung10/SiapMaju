@extends('layouts.master')
@section('content')
    <style type="text/css">
        .switch input:empty ~ span { height:25px; }
        .switch input:checked ~ span:after { margin-left:29px; }
        .switch input:empty ~ span:after { height:19px; width:21px; }
        textarea { overflow-y:auto; resize:none; }
    </style>
    <div class="container">
        <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
            <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <h5 class="text-dark font-weight-bold my-1 mr-5">
                            Detail Vaksin
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
                            <input type="text" class="form-control" value="{{ ((!empty($data)) ? ($data->nama_vaksin) : ('')) }}" disabled />
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea class="form-control" rows="5" disabled>{{ ((!empty($data)) ? ($data->keterangan) : ('')) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Wajib?</label>
                            <label class="switch">
                                <input type="checkbox" {{ ((!empty($data)) ? (($data->wajib) ? ('checked') : ('')) : ('')) }} class="form-control" disabled>
                                <span class="slider round"></span>
                                <code>{{ ((!empty($data)) ? (($data->wajib) ? ('Wajib') : ('Tidak Wajib')) : ('')) }}</code>
                            </label>
                        </div>
                        <div class="form-group">
                            <label>Status?</label>
                            <label class="switch">
                                <input type="checkbox" {{ ((!empty($data)) ? (($data->status_aktif) ? ('checked') : ('')) : ('')) }} class="form-control" disabled>
                                <span class="slider round"></span>
                                <code>{{ ((!empty($data)) ? (($data->status_aktif) ? ('Aktif') : ('Tidak Aktif')) : ('')) }}</code>
                            </label>
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