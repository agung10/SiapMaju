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
                            Mutasi Warga Detail
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
                            <label>Nama Warga</label>
                            <input type="text" value="{{ $data->nama }}" class="form-control" readonly />
                        </div>
                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <input type="text" value="{{ date('d M Y', strtotime($data->tgl_lahir)) }}" class="form-control" readonly />
                        </div>
                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <input type="text" value="{{ (($data->jenis_kelamin == 'L') ? ('Laki-laki') : ('Perempuan')) }}" class="form-control" readonly />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="text" value="{{ $data->email }}" class="form-control" readonly />
                        </div>
                        <div class="form-group">
                            <label>No Telepon</label>
                            <input type="text" value="{{ $data->mobile }}" class="form-control" readonly />
                        </div>
                        <div class="form-group">
                            <label>Alamat Lama</label>
                            <textarea class="form-control" rows="5" readonly>{{ $data->alamat }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="card-body row">
                    <div class="col-md-12">
                        {!! $history !!}
                    </div>
                </div>
                <div class="card-footer">
                    @include('partials.buttons.submit')
                </div>
            </form>
        </div>
    </div>
@endsection