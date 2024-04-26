@extends('layouts.master')

@section('content')
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Detail Tanda Tangan Kelurahan
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
						<img class="imagePicture mx-auto d-block" src="{{ ((!empty($data)) ? ((!empty($data->tanda_tangan_kelurahan)) ? (asset('uploaded_files/tanda_tangan_kelurahan/'.$data->tanda_tangan_kelurahan)) : (asset('images/NoPic.png'))) : (asset('images/NoPic.png'))) }}" width="100" height="100"></img>
					</div>
					<label class="l-center">Tanda Tangan Kelurahan</label>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Provinsi</label>
							<input type="text" class="form-control" value="{{ $detailAlamat['province'] ? $detailAlamat['province'] : 'Belum memilih provinsi' }}" readonly>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Kota/Kabupaten</label>
							<input type="text" class="form-control" value="{{ $detailAlamat['type'] ? $detailAlamat['type'] : 'Belum memilih kota/kabupaten' }} {{ $detailAlamat['city'] ? $detailAlamat['city'] : '' }}" readonly>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Kecamatan</label>
							<input type="text" class="form-control" value="{{ $detailAlamat['subdistrict_name'] ? $detailAlamat['subdistrict_name'] : 'Belum memilih kecamatan' }}" readonly>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Nama Kelurahan</label>
							<input type="text" value="{{ $data->nama ? $data->nama : 'Belum memilih Kelurahan' }}" class="form-control" readonly />
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