@extends('layouts.master')

@section('content')

<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Detail Program Kegiatan </h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<div class="card-body">
			<h4>Data Program Kegiatan</h4>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>Gambar Program Kegiatan</label>
						<div class="imageContainer">
							<img class="imagePicture"
								src="{{ ((!empty($data)) ? ((!empty($data->image)) ? (asset('upload/program/'.$data->image)) : (asset('images/NoPic.png'))) : (asset('images/NoPic.png'))) }}"
								width="50" height="50">
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Nama Program </label>
						<input type="text" value="{{!empty($data->nama_program) ? $data->nama_program : '' }}" readonly
							name="nama_program" class="form-control" />
						<div style="font-size:10px;margin:5px;color:red" class="err-nama_program"></div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Program </label>
						<input type="text" value="{{!empty($data->program) ? $data->program : '' }}" readonly
							name="program" class="form-control" />
						<div style="font-size:10px;margin:5px;color:red" class="err-program"></div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>PIC </label>
						<input type="text" value="{{!empty($data->pic) ? $data->pic : '' }}" readonly name="pic"
							class="form-control" />
						<div style="font-size:10px;margin:5px;color:red" class="err-pic"></div>
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