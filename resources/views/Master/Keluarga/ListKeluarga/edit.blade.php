@extends('layouts.master')

@section('content')
@include('Master.Keluarga.ListKeluarga.css')
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Edit Kepala Keluarga
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<div class="card-body">
			<div class="form-keluarga-container-edit">
				<form class="form-keluarga"
					action="{{route('Master.ListKeluarga.updateKeluarga',\Crypt::encryptString($data->keluarga_id))}}"
					enctype="multipart/form-data" method="POST">
					<input type="hidden" class="keluarga-id" name="keluarga_id" value="{{!empty($data->keluarga_id) ? \Crypt::encryptString($data->keluarga_id) : ''}}">
					@csrf
					<div class="row">
						<h4>Form Kepala Keluarga</h4>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Provinsi <span class="text-danger">*</span></label>
								<select class="form-control" name="province_id" id="province">
									{!! $resultProvince !!}
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Kota/Kabupaten <span class="text-danger">*</span></label>
								<select class="form-control" name="city_id" id="city">
									{!! $resultCity !!}
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Kecamatan <span class="text-danger">*</span></label>
								<select class="form-control" name="subdistrict_id" id="subdistrict">
									{!! $resultSubdistrict !!}
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Kelurahan <span class="text-danger">*</span></label>
								<select class="form-control" name="kelurahan_id" id="kelurahan">
									{{!! $resultKelurahan !!}}
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>RW <span class="text-danger">*</span></label>
								<select class="form-control" name="rw_id" id="rw">
									{{!! $resultRW !!}}
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>RT <span class="text-danger">*</span></label>
								<select class="form-control" name="rt_id" id="rt">
									{{!! $resultRT !!}}
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Blok Rumah<span class="text-danger">*</span></label>
								<select class="form-control blok" name="blok_id" id="blok">
									{!! $blok !!}
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Alamat Domisili<span class="text-danger">*</span></label>
								<textarea type="text" name="alamat"
									class="form-control">{{!empty($data->alamat) ?  $data->alamat : ''}}</textarea>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Status Domisili <span class="text-danger">*</span></label>
								<select class="form-control" name="status_domisili">
									{!! $status_domisili !!}
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Alamat Lain<span class="text-danger">*</span></label>
								<textarea type="text" class="form-control alamat-ktp" name="alamat_ktp">{{!empty($data->alamat_ktp) ?  $data->alamat_ktp : ''}}</textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Email<span class="text-danger">*</span></label>
								<input type="email" name="email" class="form-control"
									value="{{!empty($data->email) ?  $data->email : ''}}" />
								<div style="font-size:10px;margin:5px;color:red" class="err-email"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>No Telp<span class="text-danger">*</span></label>
								<input type="text" name="no_telp" class="form-control"
									value="{{!empty($data->no_telp) ?  $data->no_telp : ''}}" />
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>File Upload</label>
								<div class="custom-file">
									<input name="file" type="file" class="custom-file-input" id="customFile"/>
									<label class="custom-file-label" for="customFile" id="file-name">{{ $data->file ? $data->file : 'Choose file' }} </label>
								</div>
								@if ($data->file) 
									<a href="{{ asset('uploaded_files/file/'.$data->file) }}" target="_blank" class="mb-5">Lihat File</a>
								@endif
							</div>
						</div>
					</div>
					<div class="row btn-keluarga-container">
						<div class="col-md-6"></div>
						<div class="col-md-6">
							<button type="submit" class="btn btn-primary submit float-right">Ubah Keluarga</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="form-anggota-container-edit {{(empty($kepalaKeluarga) ? 'd-none' : '')}}">
			<form id="anggota-form" enctype="multipart/form-data">
				@csrf
				<div class="row">
					<h4>Form Anggota Keluarga</h4>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Nama Anggota Keluarga <span class="text-danger">*</span></label>
							<input type="text" name="nama" class="form-control"
								value="{{!empty($kepalaKeluarga->nama) ? $kepalaKeluarga->nama : ''}}" />
							<div style="font-size:10px;margin:5px;color:red" class="err-no_rumah"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Hubungan Keluarga <span class="text-danger">*</span></label>
							<select class="form-control rw" name="hub_keluarga_id">
								<option value="{{$selectKepalaKeluarga->hub_keluarga_id}}">
									{{$selectKepalaKeluarga->hubungan_kel}}
								</option>
							</select>
						</div>
					</div>
					<input type="hidden" class="keluarga-id" name="keluarga_id" value="{{!empty($data->keluarga_id) ? \Crypt::encryptString($data->keluarga_id) : ''}}">
					<input type="hidden" class="anggota-alamat" name="alamat" value="{{!empty($data->alamat) ? $data->alamat : ''}}">
					<input type="hidden" class="anggota-email" name="email" value="{{!empty($data->email) ? $data->email : ''}}">
					<input type="hidden" class="anggota-rt_id" name="rt_id" value="{{!empty($data->rt_id) ? $data->rt_id : ''}}">
					<input type="hidden" class="anggota-rw_id" name="rw_id" value="{{!empty($data->rw_id) ? $data->rw_id : ''}}">
					<input type="hidden" class="anggota-kelurahan_id" name="kelurahan_id" value="{{!empty($data->kelurahan_id) ? $data->kelurahan_id : ''}}">
					<input type="hidden" class="anggota-subdistrict_id" name="subdistrict_id" value="{{!empty($data->subdistrict_id) ? $data->subdistrict_id : ''}}">
					<input type="hidden" class="anggota-city_id" name="city_id" value="{{!empty($data->city_id) ? $data->city_id : ''}}">
					<input type="hidden" class="anggota-province_id" name="province_id" value="{{!empty($data->province_id) ? $data->province_id : ''}}">
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Mobile <span class="text-danger">*</span></label>
							<input type="text" name="mobile" class="form-control anggota-mobile"
								value="{{!empty($data->no_telp) ? $data->no_telp : ''}}" readonly/>
							<div style="font-size:10px;margin:5px;color:red" class="err-no_rumah"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Tanggal Lahir <span class="text-danger">*</span></label>
							<input type="date" name="tgl_lahir"
								value="{{!empty($kepalaKeluarga->tgl_lahir) ? $kepalaKeluarga->tgl_lahir : ''}}"
								class="form-control" />
							<div style="font-size:10px;margin:5px;color:red" class="err-no_rumah"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Jenis Kelamin <span class="text-danger">*</span></label>
							<select class="form-control" name="jenis_kelamin">
								<option selected disabled>Pilih Jenis Kelamin</option>
								<option value="L" {{$data->jenis_kelamin == 'L' ? 'selected' : ''}}>Laki-Laki</option>
								<option value="P" {{$data->jenis_kelamin == 'P' ? 'selected' : ''}}>Perempuan</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6"></div>
					<div class="col-md-6">
						<button type="submit" class="btn btn-primary submit float-right">Simpan Anggota
							Keluarga</button>
					</div>
				</div>
			</form>
		</div>
		@if(empty($kepalaKeluarga))
		<div class="no-data-container">
			<h3>Kepala Keluarga Belum Ditambahkan</h3>
			<button class="btn btn-primary add-kepala">Tambah Kepala Keluarga</button>
		</div>
		@endif
	</div>
	<div class="card-footer">
		<button type="reset" onClick='goBack()' class="btn btn-secondary">Back</button>
	</div>
</div>
{!! JsValidator::formRequest('App\Http\Requests\Master\KeluargaRequest','.form-keluarga') !!}
{!! JsValidator::formRequest('App\Http\Requests\Master\AnggotaKeluargaRequest','#anggota-form') !!}
@include('Master.Keluarga.ListKeluarga.editScript')

@endsection