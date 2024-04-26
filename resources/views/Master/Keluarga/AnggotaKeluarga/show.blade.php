@extends('layouts.master')

@section('content')
@include('Master.Keluarga.AnggotaKeluarga.css')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Detail Anggota Keluarga
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
		<form id="headerForm" action="#" enctype="multipart/form-data" method="POST">
			@csrf
			<div class="card-body">
				<h4>Data Anggota Keluarga & Akun</h4>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Keluarga</label>
							<input class="form-control" name="keluarga_id" value="{{ 'Blok ' . $data->nama_blok.' / '.$kepalaKeluarga->nama }}" disabled />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Nama Anggota</label>
							@if ($mutasi != null)
								@if ($mutasi->status_mutasi_warga_id == 1)
									<input type="text" value="{{!empty($data->nama) ? $data->nama : '' }}" disabled name="nama" class="form-control" />
								@elseif ($mutasi->status_mutasi_warga_id == 2)
									<input type="text" value="{{!empty($data->nama) ? $data->nama.' (Pindah)' : '' }}" disabled name="nama" class="form-control" />
								@elseif ($mutasi->status_mutasi_warga_id == 3)
									@if ($data->jenis_kelamin == "L") 
										<input type="text" value="{{!empty($data->nama) ? 'Alm. '.$data->nama  : '' }}" disabled name="nama" class="form-control" />
									@elseif ($data->jenis_kelamin == "P") 
										<input type="text" value="{{!empty($data->nama) ? 'Almh. '.$data->nama  : '' }}" disabled name="nama" class="form-control" />
									@endif
								@endif
							@else
								<input type="text" value="{{!empty($data->nama) ? $data->nama : '' }}" disabled name="nama" class="form-control" />
							@endif
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Jenis Kelamin<span class="text-danger">*</span></label>
							<select disabled class="form-control" name="jenis_kelamin">
								<option selected disabled>Pilih Jenis Kelamin</option>
								<option value="L" {{$data->jenis_kelamin == 'L' ? 'selected' : ''}}>Laki-Laki</option>
								<option value="P" {{$data->jenis_kelamin == 'P' ? 'selected' : ''}}>Perempuan</option>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Tanggal Lahir </label>
							<input type="text" value="{{ !empty($data->tgl_lahir) ? date('d-m-Y',strtotime($data->tgl_lahir)) : 'Belum memasukkan tanggal lahir' }}" name="tgl_lahir" class="form-control" disabled />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Hubungan Keluarga</label>
							<input class="form-control" name="hub_keluarga_id" value="{{ $data->hubungan_kel }}" disabled />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Mobile</label>
							<input type="number" name="mobile" class="form-control" value="{{ $data->mobile ? $data->mobile : 'Belum memasukkan mobile' }}" disabled />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Email</label>
							<input type="email" name="email" class="form-control" value="{{ $data->email }}" disabled />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Agama</label>
							<input class="form-control" name="agama_id" value="{{ $data->agama ? $data->agama->nama_agama : '-' }}" disabled />
						</div>
					</div>
					@if(!empty($data->nama_umkm))
					<div class="col-md-6">
						<div class="form-group">
							<label>Nama UMKM</label>
							<input type="text" name="nama_umkm" class="form-control" value="{{ $data->nama_umkm }}" disabled />
						</div>
					</div>
					@endif
				</div>	

				<h4 class="mt-3">Data Detail Alamat</h4>
				<hr>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label>Provinsi</label>
							<input type="text" class="form-control" value="{{ $anggotaGetAlamat['province'] ? $anggotaGetAlamat['province'] : 'Belum memilih provinsi' }}" disabled>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Kota/Kabupaten</label>
							<input type="text" class="form-control" value="{{ $anggotaGetAlamat['type'] ? $anggotaGetAlamat['type'] : 'Belum memasukkan kota/kabupaten' }} {{ $anggotaGetAlamat['city'] ? $anggotaGetAlamat['city'] : '' }}" disabled>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Kecamatan</label>
							<input type="text" class="form-control" value="{{ $anggotaGetAlamat['subdistrict_name'] ? $anggotaGetAlamat['subdistrict_name'] : 'Belum memilih kecamatan' }}" disabled>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Nama Kelurahan</label>
							<input type="text" value="{{ $dat->nama ? $dat->nama : 'Belum mengisi nama Kelurahan' }}" class="form-control" disabled />
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Nama RW</label>
							<input type="text" value="{{ $dat->rw ? $dat->rw : 'Belum mengisi nama RW' }}" class="form-control" disabled />
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Nama RT</label>
							<input type="text" value="{{ $dat->rt ? $dat->rt : 'Belum mengisi nama RT' }}" class="form-control" disabled />
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>Alamat Lengkap</label>
							<textarea type="text" name="alamat" class="form-control" disabled>{{ $anggotaGetAlamat['alamat'] }}</textarea>
						</div>
					</div>
				</div>
				@if ($data->is_active == true)
					@if($anggota_keluarga)
						<div class="anggota-keluarga-container">
							<h4>Hubungan Keluarga</h4>
							@foreach($anggota_keluarga as $key => $val)
								@php
									$mutasiKeluarga = \DB::table('mutasi_warga')->where('anggota_keluarga_id', $val->anggota_keluarga_id)->latest("mutasi_warga_id")->first();
								@endphp
								<div class="anggota-keluarga">
									<p class="hub-keluarga">{{$val->hubungan_kel}}:</p>
									@if ($mutasiKeluarga != null)
										@if ($mutasiKeluarga->status_mutasi_warga_id == 1)
											<p>{{$val->nama}}</p>
										@elseif ($mutasiKeluarga->status_mutasi_warga_id == 2)
											<p>{{$val->nama}} (Pindah)</p>
										@elseif ($mutasiKeluarga->status_mutasi_warga_id == 3)
											@if ($val->jenis_kelamin == "L") 
												<p>Alm. {{$val->nama}}</p>
											@elseif ($val->jenis_kelamin == "P") 
												<p>Almh. {{$val->nama}}</p>
											@endif
										@endif
									@else
										<p>{{$val->nama}}</p>
									@endif
								</div>
							@endforeach
						</div>
					@endif
				@endif
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>
@endsection