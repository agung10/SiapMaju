@extends('layouts.master')
@section('content')
<div class="container">
	<div style="z-index: -1;" class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
		<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Detail keluh kesan
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<div class="card-body">
			<h4>Data Fasilitas</h4>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>Nama Fasilitas </label>
						<input type="text" name="nama_fasilitas" class="form-control"
							placeholder="Masukan nama fasilitas" value="{{ $data->nama_fasilitas }}" readonly />
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Lokasi </label>
						<input type="text" name="lokasi" class="form-control" placeholder="Masukan lokasi"
							value="{{ $data->lokasi }}" readonly />
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<div class="imageContainerEdit">
							<img class="imagePicture" src="{{ helper::imageLoad('fasilitas', $data->pict1) }}" />
						</div>
						<label>Gambar 1</label>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<div class="imageContainerEdit">
							<img class="imagePicture" src="{{ helper::imageLoad('fasilitas', $data->pict2) }}" />
						</div>
						<label>Gambar 2</label>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group mb-1">
						<label>Keterangan</label>
						<textarea name="keterangan" class="form-control" rows="3"
							readonly>{{ $data->keterangan }}</textarea>
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