@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Jenis Transaksi Detail
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
					<label>Nama Kegiatan<span class="text-danger">*</span></label>
					<input type="text" value="{{ $data->nama_kegiatan }}" class="form-control" readonly />
				</div>
				<div class="form-group">
					<label>Nama Jenis Transaksi<span class="text-danger">*</span></label>
					<input type="text" value="{{ $data->nama_jenis_transaksi }}" class="form-control" readonly />
				</div>
				<div class="form-group">
					<label>Satuan<span class="text-danger">*</span></label>
					<input type="text" value="{{ $data->satuan }}" class="form-control" readonly />
				</div>
				<div class="form-group">
					<label>Nilai<span class="text-danger">*</span></label>
					<input type="text" value="{{ $data->nilai }}" class="form-control" readonly />
				</div>
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>
<script>
@endsection