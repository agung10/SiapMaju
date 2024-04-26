@extends('layouts.master')
@section('content')
<style>
	.imageContainer {
		width:  220px;
		height:  220px;
		border-radius: 3px;
		margin-top: 20px;
	}
	.imagePicture {
		width:  200px;
		height:  200px;
	}
	.materiPicture {
		width:  200px;
		height:  200px;
	}
</style>
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Detail Laporan
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<div class="card-body">
			<h4>Data Laporan</h4>
			<hr>
			<div class="row">
				<div class="col-md-6">
					<div class="col-md-12">
						<div class="form-group">
							<label>Judul </label>
							<input type="text" name="laporan" value="{{ $data->laporan }}" class="form-control" disabled />
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label for="exampleSelect1">Kategori Laporan </label>
							<input type="text" class="form-control input-sm"
								value="{{ $data->kategori->nama_kategori }}" disabled>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="col-md-12">
						<div class="form-group">
							<label>Deskripsi </span></label>
							<textarea name="detail_laporan" class="form-control" rows="5"
								disabled> {{ $data->detail_laporan }}</textarea>
						</div>
					</div>
				</div>
			</div>

				<br />
					<h4>Lampiran</h4>
					<br />
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Cover </label>
								<div class="imageContainer">
									<img class="imagePicture"
										src="{{ ((!empty($data)) ? ((!empty($data->image)) ? (asset('upload/laporan/'.$data->image)) : (asset('images/NoPic.png'))) : (asset('images/NoPic.png'))) }}"
										width="200" height="200">
								</div>
							</div>
						</div>
				
						<div class="col-md-6">
							<div class="form-group">
								<label>Materi</label>
								<div class="custom-file">
									<input type="text" name="upload_materi" id="mat" value="{{ $data->upload_materi }}"
										class="form-control" disabled/>
								</div>
								<a target="_blank" href="{{ helper::loadImgUpload('laporan/materi/', $data->upload_materi) }}" id="btn-materi"
									class="btn btn-primary font-weight-bolder btn-materi" style="margin-top: 20px;">Lihat
									File</a>	
							</div>
						</div>
					</div>
			

			<h4 class="mt-3">Data Detail Alamat</h4>
			<hr>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Nama Kelurahan</label>
						<input type="text" value="{{ $data->kelurahan->nama }}"
							class="form-control" disabled />
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Nama RW</label>
						<input type="text" value="{{ $data->rw->rw }}"
							class="form-control" disabled />
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Nama RT</label>
						<input type="text" value="{{ $data->rt->rt }}"
							class="form-control" disabled />
					</div>
				</div>
			</div>
		</div>
		<div class="card-footer">
			@include('partials.buttons.submit')
		</div>
	</div>
</div>
<script type="text/javascript">
	let materi = $('input[name=upload_materi]').val();
	var fileExt = materi.split('.').pop();
	if (fileExt === 'pdf') {
		$('.btn-materi').show();
	} else {
		$('.btn-materi').hidden();
	}
</script>
@endsection