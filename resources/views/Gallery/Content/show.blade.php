@extends('layouts.master')

@section('content')
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Show Content
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
					<label for="exampleSelect1">Galeri </label>
					<input type="text" value="{{ $data->detail_galeri }}" class="form-control" readonly>
				</div>
				<div class="form-group">
					<label for="exampleSelect1">Agenda </label>
					<input type="text" value="{{ $data->nama_agenda }}" class="form-control" readonly>
				</div>
				<div class="form-group">
					<label for="exampleSelect1">Kategori File</label>
					<input type="text" class="form-control" value="{{ $data->kategori_file }}">
				</div>
				<div class="form-group">
					<div class="imageContainer">
						<embed class="imagePicture"
							src="{{ ((!empty($data)) ? ((!empty($data->file)) ? (asset('upload/galeri/konten/'.$data->file)) : (asset('images/NoPic.png'))) : (asset('images/NoPic.png'))) }}"
							width="200" height="200"></embed>
					</div>
					<label>Gambar / Video</label>
				</div>
				<div class="form-group">
					<label>Keterangan<span class="text-danger">*</span></label>
					<textarea type="text" name="keterangan" class="form-control" rows="3"
						readonly>{{ $data->keterangan }}</textarea>
				</div>
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>
</div>

@endsection