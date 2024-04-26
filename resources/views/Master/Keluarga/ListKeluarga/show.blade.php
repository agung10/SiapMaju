@extends('layouts.master')

@section('content')
@include('Master.Keluarga.ListKeluarga.css')
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Detail Kepala Keluarga
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		@csrf
		<div class="card-body">
			<div class="form-keluarga-container-edit">
				<form class="form-keluarga" enctype="multipart/form-data">
					@csrf
					<div class="row">
						<h4>Form Kepala Keluarga</h4>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Provinsi</label>
								<input type="text" class="form-control"
									value="{{ $keluargaGetAlamat['province'] ? $keluargaGetAlamat['province'] : 'Belum memilih provinsi' }}"
									disabled>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Kota/Kabupaten</label>
								<input type="text" class="form-control"
									value="{{ $keluargaGetAlamat['type'] ? $keluargaGetAlamat['type'] : 'Belum memasukkan kota/kabupaten' }} {{ $keluargaGetAlamat['city'] ? $keluargaGetAlamat['city'] : '' }}"
									disabled>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Kecamatan</label>
								<input type="text" class="form-control"
									value="{{ $keluargaGetAlamat['subdistrict_name'] ? $keluargaGetAlamat['subdistrict_name'] : 'Belum memilih kecamatan' }}"
									disabled>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Nama Kelurahan</label>
								<select class="form-control" name="kelurahan_id" disabled>
									{!! $kelurahan !!}
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Nama RW</label>
								<select class="form-control" name="rw_id" disabled>
									{!! $rw !!}
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Nama RT</label>
								<select class="form-control" name="rt_id" disabled>
									{!! $rt !!}
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Blok Rumah</label>
								<select class="form-control blok" disabled name="blok_id">
									{!! $blok !!}
								</select>
								<div style="font-size:10px;margin:5px;color:red" class="err-blok_id"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Alamat Domisili</label>
								<textarea type="text" disabled name="alamat"
									class="form-control">{{!empty($data->alamat) ?  $data->alamat : ''}}</textarea>
								<div style="font-size:10px;margin:5px;color:red" class="err-alamat"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Status Domisili</label>
								<select class="form-control" name="status_domisili" disabled>
									{!! $status_domisili !!}
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Alamat Lain</label>
								<textarea type="text" disabled name="alamat_ktp" class="form-control alamat-ktp">{{!empty($data->alamat_ktp) ?  $data->alamat_ktp : ''}}</textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Email</label>
								<input type="email" disabled name="email" class="form-control"
									value="{{!empty($data->email) ?  $data->email : ''}}" />
								<div style="font-size:10px;margin:5px;color:red" class="err-email"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>No Telp</label>
								<input type="text" disabled name="no_telp" class="form-control"
									value="{{!empty($data->no_telp) ?  $data->no_telp : ''}}" />
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>File Upload</label>
								<span><input class="form-control" type="text" value="{{ $data->file }}" disabled /></span>
								<a href="{{ asset('uploaded_files/file/'.$data->file) }}" target="_blank" class="mb-5">Lihat File</a>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="form-anggota-container-edit {{(empty($kepalaKeluarga) ? 'd-none' : '')}} mt-5" style="padding: 0;">
				<form id="anggota-form" enctype="multipart/form-data">
					@csrf
					<div class="row">
						<h4>Form Anggota Keluarga</h4>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Nama Anggota Keluarga</label>
								@if ($mutasi != null)
									@if ($mutasi->status_mutasi_warga_id == 1)
										<input type="text" disabled name="nama" class="form-control" value="{{!empty($kepalaKeluarga->nama) ? $kepalaKeluarga->nama : ''}}" />
									@elseif ($mutasi->status_mutasi_warga_id == 2)
										<input type="text" disabled name="nama" class="form-control" value="{{!empty($kepalaKeluarga->nama) ? $kepalaKeluarga->nama.' (Pindah)' : ''}}" />
									@elseif ($mutasi->status_mutasi_warga_id == 3)
										@if ($data->jenis_kelamin == "L") 
											<input type="text" disabled name="nama" class="form-control" value="{{!empty($kepalaKeluarga->nama) ? 'Alm. '.$kepalaKeluarga->nama : ''}}" />
										@elseif ($data->jenis_kelamin == "P") 
											<input type="text" disabled name="nama" class="form-control" value="{{!empty($kepalaKeluarga->nama) ? 'Almh. '.$kepalaKeluarga->nama : ''}}" />
										@endif
									@endif
								@else
									<input type="text" disabled name="nama" class="form-control" value="{{!empty($kepalaKeluarga->nama) ? $kepalaKeluarga->nama : ''}}" />
								@endif
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Hubungan Keluarga</label>
								<select class="form-control rw" disabled name="hub_keluarga_id">
									<option value="{{$selectKepalaKeluarga->hub_keluarga_id}}">
										{{$selectKepalaKeluarga->hubungan_kel}}</option>
								</select>
								<div style="font-size:10px;margin:5px;color:red" class="err-blok_id"></div>
							</div>
						</div>
						<input type="hidden" class="keluarga-id" name="keluarga_id"
							value="{{!empty($data->keluarga_id) ? $data->keluarga_id : ''}}">
						<input type="hidden" class="anggota-alamat" name="alamat"
							value="{{!empty($data->alamat) ? $data->alamat : ''}}">
						<input type="hidden" class="anggota-email" name="email"
							value="{{!empty($data->email) ? $data->email : ''}}">
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Mobile</label>
								<input type="text" disabled name="mobile" class="form-control"
									value="{{!empty($kepalaKeluarga->mobile) ? $kepalaKeluarga->mobile : ''}}" />
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Tanggal Lahir</label>
								<input type="date" disabled name="tgl_lahir"
									value="{{!empty($kepalaKeluarga->tgl_lahir) ? $kepalaKeluarga->tgl_lahir : ''}}"
									class="form-control" />
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Jenis Kelamin </label>
								@if ($data->jenis_kelamin == "L") 
								<input type="text" value="Laki-Laki" disabled name="jenis_kelamin" class="form-control"/>
								@elseif ($data->jenis_kelamin == "P") 
								<input type="text" value="Perempuan" disabled name="jenis_kelamin" class="form-control"/>
								@endif
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="card-footer">
			@include('partials.buttons.submit')
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$(window).on( "load", function() {
			const status_domisili = $('select[name=status_domisili]')
			var selected = status_domisili.children("option:selected").val();

			if (selected == 1) {
				var alamat_domisili = $('textarea[name=alamat]').val();
				var test = $('.alamat-ktp').val(alamat_domisili);
				$('.alamat-ktp').prop("disabled", true);
			}
		});
	});
</script>
@endsection