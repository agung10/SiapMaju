@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                       Detail Telepon Penting
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
		<form id="headerForm" action="#" enctype="multipart/form-data" method="POST">
			@csrf
			<div class="card-body">
				<h4 class="mt-3">Data Telepon Penting</h4>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Nama Instansi </label>
							<input type="text" name="nama_instansi" value="{{ $data->nama_instansi }}" class="form-control"  disabled/>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>No. Telepon </label>
							<input type="number" name="no_tlp" value="{{ $data->no_tlp }}" class="form-control"  disabled/>
						</div>
					</div>
				</div>

				<h4 class="mt-3">Data Detail Alamat</h4>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Nama Kelurahan</label>
							<input type="text" value="{{ $detail->nama ? $detail->nama : 'Belum mengisi nama Kelurahan' }}"
								class="form-control" disabled />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Nama RW</label>
							<input type="text" value="{{ $detail->rw ? $detail->rw : 'Belum mengisi nama RW' }}"
								class="form-control" disabled />
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>Alamat </label>
							<textarea type="text" name="alamat" class="form-control" disabled>{{ $data->alamat }}</textarea>
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