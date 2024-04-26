@extends('layouts.master')

@section('content')

<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Detail Warga </h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<form id="headerForm" enctype="multipart/form-data" method="POST">
			@csrf
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Nama </label>
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
					<div class="col-md-6">
						<div class="form-group">
							<label>Blok </label>
							<input type="text" value="{{!empty($data->nama_blok) ? $data->nama_blok : '' }}" disabled
								name="nama_blok" class="form-control" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Jenis Kelamin </label>
							<input type="text" value="{{!empty($data->jenis_kelamin) ? $data->jenis_kelamin : '' }}"
								disabled name="jenis_kelamin" class="form-control" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Hubungan Keluarga</label>
							<input type="text" value="{{!empty($data->hubungan_kel) ? $data->hubungan_kel : '' }}"
								disabled name="hubungan_kel" class="form-control" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Tahun Lahir </label>
							<input type="text"
								value="{{!empty($data->tgl_lahir) ? date('Y',strtotime($data->tgl_lahir)) : '' }}"
								disabled name="tgl_lahir" class="form-control" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Hubungan Keluarga</label>
							<input type="text" value="{{!empty($data->email) ? $data->email : '' }}" disabled
								name="email" class="form-control" />
						</div>
					</div>
				</div>
				<input type="hidden" class="anggota_keluarga_id" value="{{ $data->anggota_keluarga_id }}">
				@if(($data->is_active == false && !empty($anggota) && $anggota->is_rt == true) || ($data->is_active == false && \Auth::user()->is_admin == true))
				<button type="submit" class="btn btn-warning approve_rt">Approve RT</button>
				@endif
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>
</div>

<script>
	$('.approve_rt').on('click', function(e) {
		e.preventDefault();
		var id = $('input[class=anggota_keluarga_id]').val();

		swal.fire({
			title: "Konfirmasi",
			text: "Apakah anda yakin untuk approve data ini?",
			icon: "warning",
			showCancelButton: true,
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					type: 'PUT',
					url: '{{ route("role_management.ListWarga.ApprovalRT",'') }}' +'/' +id,
					headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					data: $('#headerForm').serialize(),
					success: function(data) {
						swal.fire({
							title: 'Data berhasil diupdate!',
							icon: 'success',
						}).then((result) => {
							window.location = '{{ route('role_management.ListWarga.index') }}'	  
						})
					},
					error: function(err) {
						swal.fire({
							title: 'Maaf, Terjadi Kesalahan!',
							icon: 'error',
						});
					}
				});
			} else if(result.dismiss === Swal.DismissReason.cancel) {
				swal.fire("Proses Dibatalkan");
			}
		});
	});
</script>
@endsection