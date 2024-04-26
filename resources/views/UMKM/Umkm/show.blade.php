@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Detail UMKM
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
        <form id="headerForm" action="#" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <div class="imageContainer mx-auto d-block">
                        <img class="imagePicture mx-auto d-block" src="{{ ((!empty($data)) ? ((!empty($data->image)) ? (asset('uploaded_files/umkm/'.$data->image)) : (asset('images/NoPic.png'))) : (asset('images/NoPic.png'))) }}" width="200" height="200"></img>
                    </div>
                    <label class="l-center">LOGO UMKM</label>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleSelect1">Nama UMKM</label>
                            <input type="text" class="form-control" value="{{ ((!empty($data)) ? ($data->nama_umkm): ('')) }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleSelect1">Owner</label>
                            <input type="text" class="form-control" value="{{ ((!empty($data)) ? ($data->nama): ('')) }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea type="text" class="form-control" rows="5" readonly>{{ $data->deskripsi }}</textarea>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Status</label>
                        <br />
                        @if($data->aktif)
                            <input type="text" class="form-control bg-light-primary" value="Aktif" readonly>
                        @else
                            <input type="text" class="form-control bg-light-danger" value="Tidak Aktif" readonly>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label>Disetujui</label>
                        <br />
                        @if($data->disetujui)
                            <input type="text" class="form-control bg-light-primary" value="Disetujui" readonly>
                        @else
                            <input type="text" class="form-control bg-light-danger" value="Tidak Disetujui" readonly>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Promosi</label>
                        <br />
                        @if($data->promosi)
                            <input type="text" class="form-control bg-light-primary" value="Ya" readonly>
                        @else
                            <input type="text" class="form-control bg-light-danger" value="Tidak" readonly>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label>Website</label>
                        <br />
                        @if($data->has_website)
                            <input type="text" class="form-control bg-light-primary" value="Ya" readonly>
                        @else
                            <input type="text" class="form-control bg-light-danger" value="Tidak" readonly>
                        @endif
                    </div>
                </div>
                @if(count($umkmMedsos) > 0)
                <div class="separator separator-dashed my-8"></div>
                <div id="sosmed-repeater">
                    <div class="form-group">
                        <h3 class="card-label">
                            Sosial Media Terkait
                        </h3>
                    </div>
                    @foreach($umkmMedsos as $val)
                    <a href="{{ $val->url }}" target="_blank" data-toggle="tooltip" title=""
                        data-original-title="{{ $val->nama }}">
                        <img alt="Pic" src="{{ \helper::imageLoad('medsos', $val->icon) }}"
                            style="width: 75px; padding: 7px">
                    </a>
                    @endforeach
                </div>
                @endif
            </div>
            <div class="card-footer">
                @include('partials.buttons.submit')
            </div>
        </form>
    </div>
</div>
@endsection