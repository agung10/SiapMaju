@extends('layouts.master')
@section('content')
<div class="container">
	<div style="z-index: -1;" class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
		<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Detail Usulan Urusan
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<form id="Approval-Kelurahan" action="{{ route('Musrenbang.Approval-Kelurahan.update', \Crypt::encryptString($data->usulan_urusan_id)) }}" method="POST">
			{{ csrf_field() }}
			{{ method_field('PATCH') }}

			<div class="card-body">
				<div class="row mb-5">
					<div class="col-4">
						<label>Ketua RW <span class="text-danger">*</span></label>
						<input type="text" class="form-control" value="{{ ($data->User->anggotaKeluarga != null) ? $data->User->anggotaKeluarga->nama : $data->User->username }}" disabled />
					</div>
					<div class="col-4">
						<label>RW <span class="text-danger">*</span></label>
						<input type="text" class="form-control" value="{{ $data->RW->rw ?? '-' }}" disabled />
					</div>
					<div class="col-4">
						<label>RT <span class="text-danger">*</span></label>
						<input type="text" class="form-control" value="{{ $data->RT->rt ?? '-' }}" disabled />
					</div>
				</div>

				<div class="row mb-5">
					<div class="col-4">
						<label>Jenis Usulan <span class="text-danger">*</span></label>
						<input type="text" class="form-control" value="{{ $data->JenisUsulan->nama_jenis ?? '-' }}" disabled />
					</div>
					<div class="col-4">
						<label>Bidang <span class="text-danger">*</span></label>
						<input type="text" class="form-control" value="{{ $data->Bidang->nama_bidang ?? '-' }}" disabled />
					</div>
					<div class="col-4">
						<label>Kegiatan <span class="text-danger">*</span></label>
						<input type="text" class="form-control" value="{{ $data->Kegiatan->nama_kegiatan ?? '-' }}" disabled />
					</div>
					<div class="mt-5 col-12">
						<label>Alamat <span class="text-danger">*</span></label>
						<textarea class="form-control" rows="3" disabled>{{ $data->alamat ?? '-' }}</textarea>
					</div>
					<div class="mt-5 col-6">
						<label>Jumlah <span class="text-danger">*</span></label>
						<input type="text" class="form-control" value="{{ $data->jumlah ?? '-' }}" disabled />
					</div>
					<div class="mt-5 col-6">
						<label>Tahun <span class="text-danger">*</span></label>
						<input type="text" class="form-control" value="{{ $data->tahun ?? '-' }}" disabled />
					</div>
					<div class="mt-5 col-12">
						<label>Keterangan <span class="text-danger">*</span></label>
						<textarea class="form-control" rows="3" disabled>{{ $data->keterangan ?? '-' }}</textarea>
					</div>
				</div>

				<div class="mt-5 row">
					<div class="col-4">
						<label style="margin: 0 !important;">Gambar Wajib <span class="text-danger">*</span></label>
						@include('partials.form-file', [
						'title'       => __(''),
						'name'        => 'image', 
						'multiColumn' => true,
						'value'       => $data->gambar_1,
						'folder'      => 'musrenbang',
						'disabled'    => true
						])
					</div>
					@if ($data->gambar_2 != null)
					<div class="col-4">
						<label style="margin: 0 !important;">Gambar Lainnya <span class="text-danger">*</span></label>
						@include('partials.form-file', [
						'title'       => __(''),
						'name'        => 'image', 
						'multiColumn' => true,
						'value'       => $data->gambar_2,
						'folder'      => 'musrenbang',
						'disabled'    => true
						])
					</div>
					@endif
					@if ($data->gambar_3 != null)
					<div class="col-4">
						<label style="margin: 0 !important;">Gambar Lainnya <span class="text-danger">*</span></label>
						@include('partials.form-file', [
						'title'       => __(''),
						'name'        => 'image', 
						'multiColumn' => true,
						'value'       => $data->gambar_3,
						'folder'      => 'musrenbang',
						'disabled'    => true
						])
					</div>
					@endif
				</div>

				<div class="row">
					<div class="mt-5 col-12" id="mapShow" style="top: 0; bottom: 0; width: 100%; height: 400px;"></div>

					<div class="mt-5 col-6">
						<label>Latitude <span class="text-danger">*</span></label>
						<input type="text" class="form-control" value="{{ $data->latitude ?? '-' }}}" disabled />
					</div>
					<div class="mt-5 col-6">
						<label>Longitude <span class="text-danger">*</span></label>
						<input type="text" class="form-control" value="{{ $data->longitude ?? '-' }}}" disabled />
					</div>
					<div class="mt-5 col-6">
						<label>Status Usulan <span class="text-danger">*</span></label>
						<input type="text" class="form-control" value="{{ $status }}" disabled />
					</div>
				</div>
			</div>
			<div class="card-footer">
				@if ($data->status_usulan == 1)
					<button type="submit" class="btn btn-warning btn-submit">Approval Kelurahan</button>
				@endif
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>
</div>

<script>
	const mapShow = L.map('mapShow').setView(['{{ $data->latitude }}', '{{ $data->longitude }}'], 16);

	const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
		maxZoom: 19,
		attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
	}).addTo(mapShow);

	const marker = L.marker(['{{ $data->latitude }}', '{{ $data->longitude }}']).addTo(mapShow)
		.bindPopup('<b>Lokasi Usulan</b> <br /> {{ $data->alamat }}').openPopup();

</script>
@endsection