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
                            Detail Pertanyaan Polling
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-custom gutter-b">
            <form class="form" id="form-pertanyaan-tambah">
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Provinsi</label>
                            <input type="text" class="form-control" value="{{ $province }}" disabled>
                        </div>
                        <div class="col-lg-6">
                            <label>Kelurahan</label>
                            <input type="text" class="form-control" value="{{ $data->nama_kelurahan }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Kota</label>
                            <input type="text" class="form-control" value="{{ $city }}" disabled>
                        </div>
                        <div class="col-lg-6">
                            <label>RW</label>
                            <input type="text" class="form-control" value="{{ $data->rw }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Kecamatan</label>
                            <input type="text" class="form-control" value="{{ $subdistrict }}" disabled>
                        </div>
                        <div class="col-lg-6">
                            <label>RT</label>
                            <input type="text" class="form-control" value="{{ $data->rt }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Penutupan</label>
                            <input type="date" class="form-control" value="{{ $data->close_date }}" disabled>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Isi Pertanyaan</label>
                            <textarea class="form-control" rows="5" disabled>{{ $data->isi_pertanyaan }}</textarea>
                        </div>
                        <div class="col-lg-6">
                            @if (count($data->answer) > 0)
                                <label>Pilihan Jawaban</label>
                                @foreach ($data->answer as $key => $value)
                                    <input type="text" class="form-control" value="{{ $value->isi_pilih_jawaban }}" disabled style="margin-bottom:5px;">
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 text-right headerSaveButtonContainer">
                    <button onClick="goBack()" class="btn btn-secondary">Back</button>
                </div>
            </form>
        </div>
    </div>
@endsection