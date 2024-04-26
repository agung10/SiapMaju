@extends('layouts.master')

@section('content')
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Detail Profile
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<div class="card-body">
			<h4>Data Profile</h4>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<div class="imageContainer">
							<img class="imagePicture"
								src="{{ ((!empty($data)) ? ((!empty($data->gambar_profile)) ? (asset('upload/profile/'.$data->gambar_profile)) : (asset('images/NoPic.png'))) : (asset('images/NoPic.png'))) }}"
								width="200" height="200" />
						</div>
						<label>Gambar Profile</label>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group mb-1">
						<label for="exampleTextarea">Isi Profile <span class="text-danger">*</span></label>
						<textarea readonly name="isi_profile" class="form-control" id="exampleTextarea"
							rows="3">{{!empty($data->isi_profile) ? $data->isi_profile : '' }}</textarea>
						<div style="font-size:10px;margin:5px;color:red" class="err-isi_profile"></div>
					</div>
				</div>
			</div>

			<br>

			<h4 class="mt-3">Data Detail Alamat</h4>
			<hr>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label>Provinsi</label>
						<input type="text" class="form-control" value="{{ $profileGetAlamat['province'] ? $profileGetAlamat['province'] : 'Belum memilih provinsi' }}" readonly>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Kota/Kabupaten</label>
						<input type="text" class="form-control" value="{{ $profileGetAlamat['type'] ? $profileGetAlamat['type'] : 'Belum memasukkan kota/kabupaten' }} {{ $profileGetAlamat['city'] ? $profileGetAlamat['city'] : '' }}" readonly>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Kecamatan</label>
						<input type="text" class="form-control" value="{{ $profileGetAlamat['subdistrict_name'] ? $profileGetAlamat['subdistrict_name'] : 'Belum memilih kecamatan' }}" readonly>
					</div>
				</div>
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