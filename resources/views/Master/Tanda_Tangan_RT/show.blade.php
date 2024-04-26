@extends('layouts.master')

@section('content')
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
		<div class="container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Detail Tanda Tangan RT
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<div class="card-body">
			<div class="form-group">
				<div class="imageContainer mx-auto d-block">
					<img class="imagePicture mx-auto d-block" src="{{ ((!empty($data)) ? ((!empty($data->tanda_tangan_rt)) ? (asset('uploaded_files/tanda_tangan_rt/'.$data->tanda_tangan_rt)) : (asset('images/NoPic.png'))) : (asset('images/NoPic.png'))) }}" width="100" height="100"></img>
				</div>
				<label class="l-center">Tanda Tangan RT</label>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Nama Kelurahan</label>
						<input type="text" value="{{ $data->nama ? $data->nama : 'Belum memilih Kelurahan' }}" class="form-control" readonly />
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Nama RW</label>
						<input type="text" value="{{ $data->rw ? $data->rw : 'Belum memilih RW' }}" class="form-control" readonly />
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Nama RT</label>
						<input type="text" value="{{ $data->rt ? $data->rt : 'Belum memilih RT' }}" class="form-control" readonly />
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