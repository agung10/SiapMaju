@extends('layouts.master')

@section('content')
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						RW Detail
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
								value="{{ $rwGetAlamat['province'] ? $rwGetAlamat['province'] : 'Belum memilih provinsi' }}"
								disabled>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Kota/Kabupaten</label>
							<input type="text" class="form-control"
								value="{{ $rwGetAlamat['type'] ? $rwGetAlamat['type'] : 'Belum memasukkan kota/kabupaten' }} {{ $rwGetAlamat['city'] ? $rwGetAlamat['city'] : '' }}"
								disabled>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Kecamatan</label>
							<input type="text" class="form-control"
								value="{{ $rwGetAlamat['subdistrict_name'] ? $rwGetAlamat['subdistrict_name'] : 'Belum memilih kecamatan' }}"
								disabled>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>Nama Kelurahan<span class="text-danger">*</span></label>
							<input type="text" value="{{ $data->nama ? $data->nama : 'Belum mengisi nama Kelurahan' }}" class="form-control" disabled />
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>Nama RW<span class="text-danger">*</span></label>
							<input type="text" value="{{ $data->rw }}" class="form-control" disabled />
						</div>
					</div>
				</div>
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>
</div>
@endsection