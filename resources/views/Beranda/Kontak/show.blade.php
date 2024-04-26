@extends('layouts.master')

@section('content')

<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Detail Kontak
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<div class="card-body">
			<h4>Data Kontak</h4>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>No Telp </label>
						<input type="text" value="{{!empty($data->no_telp) ? $data->no_telp : '' }}" readonly
							name="judul" class="form-control" />
						<div style="font-size:10px;margin:5px;color:red" class="err-judul"></div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Mobile </label>
						<input type="text" value="{{!empty($data->mobile) ? $data->mobile : '' }}" readonly name="judul"
							class="form-control" />
						<div style="font-size:10px;margin:5px;color:red" class="err-judul"></div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Nama Lokasi </label>
						<input type="text" value="{{!empty($data->nama_lokasi) ? $data->nama_lokasi : '' }}" readonly
							name="judul" class="form-control" />
						<div style="font-size:10px;margin:5px;color:red" class="err-judul"></div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Email </label>
						<input type="text" value="{{!empty($data->email) ? $data->email : '' }}" readonly name="judul"
							class="form-control" />
						<div style="font-size:10px;margin:5px;color:red" class="err-judul"></div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Web </label>
						<input type="text" value="{{!empty($data->web) ? $data->web : '' }}" readonly name="judul"
							class="form-control" />
						<div style="font-size:10px;margin:5px;color:red" class="err-judul"></div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Rekening </label>
						<input type="text" value="{{!empty($data->rekening) ? $data->rekening : '' }}" readonly
							name="judul" class="form-control" />
						<div style="font-size:10px;margin:5px;color:red" class="err-judul"></div>
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
					<div class="form-group mb-1">
						<label for="exampleTextarea">Alamat </label>
						<textarea name="keterangan" class="form-control" readonly id="exampleTextarea"
							rows="3">{{!empty($data->alamat) ? $data->alamat : 'Belum mengisi alamat' }}</textarea>
						<div style="font-size:10px;margin:5px;color:red" class="err-keterangan"></div>
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