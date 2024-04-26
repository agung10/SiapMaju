@extends('layouts.master')

@section('content')
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Blok Rumah Detail
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<form id="headerForm" action="#" enctype="multipart/form-data" method="POST">
			@csrf
			<div class="card-body">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label>Provinsi</label>
							<input type="text" class="form-control"
								value="{{ $blokGetAlamat['province'] ? $blokGetAlamat['province'] : 'Belum memilih provinsi' }}"
								disabled>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Kota/Kabupaten</label>
							<input type="text" class="form-control"
								value="{{ $blokGetAlamat['type'] ? $blokGetAlamat['type'] : 'Belum memasukkan kota/kabupaten' }} {{ $blokGetAlamat['city'] ? $blokGetAlamat['city'] : '' }}"
								disabled>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Kecamatan</label>
							<input type="text" class="form-control"
								value="{{ $blokGetAlamat['subdistrict_name'] ? $blokGetAlamat['subdistrict_name'] : 'Belum memilih kecamatan' }}"
								disabled>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Nama Kelurahan</label>
							<input type="text" value="{{ $data->nama ? $data->nama : 'Belum mengisi nama Kelurahan' }}" class="form-control" disabled />
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Nama RW</label>
							<input type="text" value="{{ $data->rw ? $data->rw : 'Belum mengisi nama RW' }}" class="form-control" disabled />
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Nama RT</label>
							<input type="text" value="{{ $data->rt ? $data->rt : 'Belum mengisi nama RT' }}" class="form-control" disabled />
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>Nama Blok</label>
							<input type="text" value="{{ $data->nama_blok }}" name="nama_blok" class="form-control"
								disabled />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Longitude </label>
							<input disabled type="text" value="{{ !empty($data->long) ? $data->long : 'Belum mengisi titik longitude' }}" name="long"
								class="form-control" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Latitude</label>
							<input disabled type="text" value="{{ !empty($data->lang) ? $data->lang : 'Belum mengisi titik latitude' }}" name="lang"
								class="form-control" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>NOP</label>
							<input disabled type="text" value="{{ !empty($data->nop) ? $data->nop : 'Belum mengisi nop' }}" name="nop" class="form-control" />
						</div>
					</div>
				</div>
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>
	<script>
		@endsection