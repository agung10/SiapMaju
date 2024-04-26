@extends('layouts.master')

@section('content')

<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Detail Header
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<div class="card-body">
			<h4>Data Header</h4>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>Gambar Header</label>
						<div class="imageContainer">
							<img class="imagePicture" src="{{ ((!empty($data)) ? ((!empty($data->image)) ? (asset('upload/header/'.$data->image)) : (asset('images/NoPic.png'))) : (asset('images/NoPic.png'))) }}" width="50" height="50">
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Judul </label>
						<input type="text" value="{{!empty($data->judul) ? $data->judul : '' }}" readonly name="judul"
							class="form-control" placeholder="Masukan Judul" />
						<div style="font-size:10px;margin:5px;color:red" class="err-judul"></div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group mb-1">
						<label for="exampleTextarea">Keterangan </label>
						<textarea name="keterangan" class="form-control" readonly id="exampleTextarea"
							rows="3">{{!empty($data->keterangan) ? $data->keterangan : '' }}</textarea>
						<div style="font-size:10px;margin:5px;color:red" class="err-keterangan"></div>
					</div>
				</div>
			</div>
			<br>

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