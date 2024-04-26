@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Detail Lampiran
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
        <form id="headerForm" action="#" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Jenis Surat Permohonan</label>
                            <input type="text" class="form-control" value="{{ $data->jenis_permohonan }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6 mb-5">
                        <label>Nama Lampiran</label>
                        <input type="text" class="form-control" value="{{ $data->nama_lampiran }}" readonly>
                    </div>
                    <div class="col-md-12">
                        <label>Keterangan</label>
                        <textarea cols="30" rows="2" class="form-control" readonly>{{ $data->keterangan }}</textarea>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kategori</label>
                            @if($data->kategori)
                                <input type="text" class="form-control bg-light-primary" value="Wajib" readonly>
                            @else
                                <input type="text" class="form-control bg-light-danger" value="Tidak Wajib" readonly>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            @if($data->status)
                                <input type="text" class="form-control bg-light-primary" value="Aktif" readonly>
                            @else
                                <input type="text" class="form-control bg-light-danger" value="Tidak Aktif" readonly>
                            @endif
                        </div>
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