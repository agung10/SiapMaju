@extends('layouts.master')

@section('content')
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Gallery Show
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
						<img class="imagePicture mx-auto d-block"
							src="{{ ((!empty($data)) ? ((!empty($data->image_cover)) ? (asset('upload/galeri/'.$data->image_cover)) : (asset('images/NoPic.png'))) : (asset('images/NoPic.png'))) }}"
							width="200" height="200"></img>
					</div>
					<label class="l-center">Gambar / Video</label>
				</div>
				<div class="form-group">
					<label for="exampleSelect1">Agenda </label>
					<input type="text" class="form-control"
						value="{{ ((!empty($data)) ? ($data->nama_agenda): ('')) }}">
					<div style="font-size:10px;margin:5px;color:red" class="err-agenda_id"></div>
				</div>
				<div class="form-group">
					<label>Detail <span class="text-danger">*</span></label>
					<textarea type="text" name="detail_galeri" class="form-control" rows="3"
						readonly>{{ $data->detail_galeri }}</textarea>
					<div style="font-size:10px;margin:5px;color:red" class="err-detail_galeri"></div>
				</div>
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>
</div>

@endsection