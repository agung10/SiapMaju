@extends('layouts.master')

@section('content')
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Detail Pengurus
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<div class="card-body">
			<h4>Data Pengurus</h4>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>Kategori Pengurus<span class="text-danger">*</span></label>
						<input type="text" value="{{ ((!empty($data)) ? ($data->kat_pengurus) : ('')) }}"
							class="form-control" readonly />
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<div class="imageContainer">
							<img class="imagePicture"
								src="{{ ((!empty($data)) ? ((!empty($data->photo)) ? (asset('upload/pengurus/'.$data->photo)) : (asset('images/NoPic.png'))) : (asset('images/NoPic.png'))) }}"
								width="200" height="200" />
						</div>
						<label>Photo</label>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Nama<span class="text-danger">*</span></label>
						<input type="text" value="{{ $data->nama }}" name="nama" class="form-control" readonly />
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Jabatan<span class="text-danger">*</span></label>
						<input type="text" value="{{ $data->jabatan }}" name="jabatan" class="form-control" readonly />
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>No Urut</label>
						<input readonly type="text" value="{{ $data->no_urut }}" name="no_urut" class="form-control"
							readonly />
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
				<div class="col-md-12">
					<div class="form-group">
						<label>Alamat</label>
						<textarea name="alamat" cols="30" rows="3" class="form-control"
							readonly>{{ $data->alamat }}</textarea>
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