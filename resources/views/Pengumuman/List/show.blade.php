@extends('layouts.master')

@section('content')

<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Detail Pengumuman </h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<div class="card-body">
			<h4>Data Pengumuman</h4>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group mb-1">
						<label for="exampleTextarea">Keterangan </label>
						<textarea style="height:200px;" name="pengumuman" class="form-control" readonly
							id="exampleTextarea"
							rows="3">{{!empty($data->pengumuman) ? $data->pengumuman : '' }}</textarea>
						<div style="font-size:10px;margin:5px;color:red" class="err-pengumuman"></div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="imagePengumumanContainer">
						<div class="form-group">
							<div class="pengumumanPictureLabel">Gambar 1</div>
							<div></div>
							<div class="imageContainer">
								<img class="imagePicture" src="{{$data->image1 ? asset('upload/pengumuman/'.$data->image1) 
																   : asset('images/NoPic.png') }}" />
							</div>
						</div>
						<div class="form-group">
							<div class="pengumumanPictureLabel">Gambar 2</div>
							<div></div>
							<div class="imageContainer">
								<img class="imagePicture" src="{{$data->image2 ? asset('upload/pengumuman/'.$data->image2) 
																   : asset('images/NoPic.png') }}" />
							</div>
						</div>
						<div class="form-group">
							<div class="pengumumanPictureLabel">Gambar 3</div>
							<div></div>
							<div class="imageContainer">
								<img class="imagePicture" src="{{$data->image3 ? asset('upload/pengumuman/'.$data->image3) 
																   : asset('images/NoPic.png') }}" />
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Tanggal </label>
						<input type="text" value="{{!empty($data->tanggal) ? $data->tanggal : '' }}" readonly
							name="tanggal" class="form-control" />
						<div style="font-size:10px;margin:5px;color:red" class="err-tanggal"></div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Status </label>
						@if($data->aktif === true)
						<span class="badge badge-pill badge-primary ml-3">Active</span>
						@else
						<span class="badge badge-pill badge-danger ml-3">Inactive</span>
						@endif
					</div>
				</div>
			</div>

			<h4 class="mt-3">Data Detail Alamat</h4>
			<hr>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Nama Kelurahan</label>
						<input type="text" value="{{ $data->nama ? $data->nama : 'Belum mengisi nama Kelurahan' }}"
							class="form-control" readonly />
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Nama RW</label>
						<input type="text" value="{{ $data->rw ? $data->rw : 'Belum mengisi nama RW' }}"
							class="form-control" readonly />
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Nama RT</label>
						<input type="text" value="{{ $data->rt ? $data->rt : 'Belum mengisi nama RT' }}"
							class="form-control" readonly />
					</div>
				</div>
			</div>
		</div>
		<div class="card-footer">
			@include('partials.buttons.submit')
		</div>
	</div>
</div>
@endsection