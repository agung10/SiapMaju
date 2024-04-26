@extends('layouts.master')

@section('content')
<style type="text/css">
	.lampiran {
		cursor: pointer;
	}
</style>
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Detail Surat Permohonan
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<div class="card-body">
			<div class="form-surat-container">
				<form class="form-surat" enctype="multipart/form-data" method="POST" id="form-edit">
					@csrf
					@method('PUT')
					<div class="mt-3">
						<h4>Form Surat Permohonan</h4>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Nama Warga</label>
								@if(\Auth::user()->is_admin == true)
								<select class="form-control warga" name="anggota_keluarga_id" disabled>
									{!! $selectNamaWarga !!}
								</select>
								@else
								<input type="text" name="nama_lengkap" class="form-control"
									value="{{ $data->nama_lengkap }}" disabled />
								@endif
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Jenis Surat</label>
								<select class="form-control jenis_surat" name="jenis_surat_id" disabled>
									{!! $jenisSurat !!}
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Tempat Lahir</label>
								<input type="text" name="tempat_lahir" class="form-control"
									value="{{ $data->tempat_lahir }}" disabled />
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Tanggal Lahir</label>
								<input type="date" name="tgl_lahir" class="form-control" value="{{ $data->tgl_lahir }}"
									disabled />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Bangsa</label>
								<input type="text" name="bangsa" class="form-control" value="{{ $data->bangsa }}"
									disabled />
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Status Pernikahan</label>
								<select class="form-control status_pernikahan" name="status_pernikahan_id" disabled>
									{!! $pernikahan !!}
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Agama</label>
								<select class="form-control agama" name="agama_id" disabled>
									{!! $agama !!}
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Pekerjaan</label>
								<input type="text" name="pekerjaan" class="form-control form-control"
									value="{{ $data->pekerjaan }}" disabled />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>No KK</label>
								<input type="text" name="no_kk" class="form-control no_kk" value="{{ $data->no_kk }}"
									disabled />
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>No KTP</label>
								<input type="text" name="no_ktp" class="form-control" value="{{ $data->no_ktp }}"
									disabled />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Tanggal Permohonan</label>
								<input type="date" name="tgl_permohonan" class="form-control"
									value="{{ $data->tgl_permohonan }}" disabled />
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Keperluan Pembuatan</label>
								<textarea class="form-control" disabled>{{ $data->keperluan ? $data->keperluan : '-' }}</textarea>
							</div>
						</div>
					</div>
					@if($data->lampiran1 || $data->lampiran2 || $data->lampiran3) 
					<div class="mt-3">
						<h4>Lampiran</h4>
					</div>
					<div class="row lampiran-container">
						@if ($data->lampiran1)
						<div class="col-md-4">
							<div class="form-group">
								<label>Lampiran 1</label>
								<a href="{{ asset('uploaded_files/surat/'.$data->lampiran1) }}" download>
									<input class="lampiran form-control" type="text" name="lampiran1"
										value="{{ $data->lampiran1 }}" disabled />
								</a>
							</div>
						</div>
						@endif
						@if ($data->lampiran2)
						<div class="col-md-4">
							<div class="form-group">
								<label>Lampiran 2</label>
								<a href="{{ asset('uploaded_files/surat/'.$data->lampiran2) }}" download>
									<input class="lampiran form-control" type="text" name="lampiran2"
										value="{{ $data->lampiran2 }}" disabled />
								</a>
							</div>
						</div>
						@endif
						@if ($data->lampiran3)
						<div class="col-md-4">
							<div class="form-group">
								<label>Lampiran 3</label>
								<a href="{{ asset('uploaded_files/surat/'.$data->lampiran3) }}" download>
									<input class="lampiran form-control" type="text" name="lampiran3"
										value="{{ $data->lampiran3 }}" disabled />
								</a>
							</div>
						</div>
						@endif
					</div>
					@elseif (count($lampiranSurat) > 0) 
					<div class="mt-3">
						<h4>Lampiran</h4>
					</div>
					<div class="row lampiran-container">
						@foreach ($lampiranSurat as $res)
						<div class="col-md-4">
							<div class="form-group">
								<label>{{ $res->nama_lampiran }}</label>
								<a href="{{ asset('uploaded_files/surat/'.$res->upload_lampiran) }}" download>
									<input class="lampiran form-control" type="text" value="{{ $res->upload_lampiran }}" disabled />
								</a>
							</div>
						</div>
						@endforeach
					</div>	
					@endif

					@if ($data->catatan_kelurahan)
					<h4 class="mt-3">Catatan</h4>
					<hr>
					<div class="row row-catatan">
						<div class="col-md-12">
							<div class="form-group">
								<textarea class="form-control" rows="9" disabled>{{ strip_tags($data->catatan_kelurahan) }}</textarea>
							</div>
						</div>
					</div>
					@endif

					{{-- <h4 class="mt-3">Data Detail Alamat</h4>
					<hr>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Provinsi</label>
								<input type="text" class="form-control"
									value="{{ $anggotaGetAlamat['province'] ? $anggotaGetAlamat['province'] : 'Belum memilih provinsi' }}"
									disabled>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Kota/Kabupaten</label>
								<input type="text" class="form-control"
									value="{{ $anggotaGetAlamat['type'] ? $anggotaGetAlamat['type'] : 'Belum memasukkan kota/kabupaten' }} {{ $anggotaGetAlamat['city'] ? $anggotaGetAlamat['city'] : '' }}"
									disabled>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Kecamatan</label>
								<input type="text" class="form-control"
									value="{{ $anggotaGetAlamat['subdistrict_name'] ? $anggotaGetAlamat['subdistrict_name'] : 'Belum memilih kecamatan' }}"
									disabled>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Nama Kelurahan</label>
								<input type="text"
									value="{{ $dataAnggotaKel->kelurahan ? $dataAnggotaKel->kelurahan : 'Belum mengisi nama Kelurahan' }}"
									class="form-control" disabled />
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Nama RW</label>
								<input type="text" value="{{ $dataAnggotaKel->rw ? $dataAnggotaKel->rw : 'Belum mengisi nama RW' }}"
									class="form-control" disabled />
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Nama RT</label>
								<input type="text" value="{{ $dataAnggotaKel->rt ? $dataAnggotaKel->rt : 'Belum mengisi nama RT' }}"
									class="form-control" disabled />
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Alamat Lengkap</label>
								<textarea type="text" name="alamat" class="form-control"
									disabled>{{ $anggotaGetAlamat['alamat'] }}</textarea>
							</div>
						</div>
					</div> --}}

					<input type="hidden" value="{{ $data->rt_id }}">
					<input type="hidden" value="{{ $data->rw_id }}">
					<input type="hidden" value="{{ $data->nama_lengkap }}">
					<input type="hidden" value="{{ $data->alamat }}">
					<input type="hidden" value="{{ $data->hal }}">
					<input type="hidden" value="{{ $data->lampiran }}">
					<input type="hidden" class="surat_permohonan_id" value="{{ $data->surat_permohonan_id }}">
					<div class="card-footer">
						@if($data->approve_draft == null && !empty($anggota))
						<button type="submit" class="btn btn-primary approve_warga">Approve Draft</button>
						@endif
						@if($data->petugas_rt_id == null && !empty($anggota) && $anggota->is_rt == true)
						<button type="submit" class="btn btn-primary approve_rt">Approve RT</button>
						@endif
						@if($data->petugas_rw_id == null && $data->petugas_rt_id != null && !empty($anggota) &&
						$anggota->is_rw == true)
						<button type="submit" class="btn btn-primary approve_rw">Approve RW</button>
						@endif
						<a href="{{route('Surat.SuratPermohonan.previewSurat',\Crypt::encryptString($data->surat_permohonan_id))}}"
							class="btn btn-warning" target="_blank">Preview Surat</a>
						<button type="reset" onClick='goBack()' class="btn btn-secondary">Back</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$('.approve_warga').on('click', function(e) {
		e.preventDefault();
			var id = $('input[class=surat_permohonan_id]').val();

			swal.fire({
	        title: "Konfirmasi",
	        text: "Apakah anda yakin untuk approve draft ini?",
	        icon: "warning",
	        showCancelButton: true,
	        }).then((result) => {
                if (result.isConfirmed) {
				   $.ajax({
		                type: 'PUT',
		                url: '{{ route("Surat.SuratPermohonan.ApprovalDraft",'') }}' +'/' +id,
		                headers: {
			            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			            },
		                data: $('#form-edit').serialize(),
		                success: function(data) {
		                    swal.fire({
		                        title: 'Data has been updated!',
		                        icon: 'success',
		                    }).then((result) => {
								window.location = '{{ route('Surat.SuratPermohonan.index') }}'	  
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

		$('.approve_rt').on('click', function(e) {
		e.preventDefault();
			var id = $('input[class=surat_permohonan_id]').val();

			swal.fire({
	        title: "Konfirmasi",
	        text: "Apakah anda yakin untuk approve draft ini?",
	        icon: "warning",
	        showCancelButton: true,
	        }).then((result) => {
                if (result.isConfirmed) {
				   $.ajax({
		                type: 'PUT',
		                url: '{{ route("Surat.SuratPermohonan.ApprovalRT",'') }}' +'/' +id,
		                headers: {
			            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			            },
		                data: $('#form-edit').serialize(),
		                success: function(data) {
		                	console.log(data);
		                    swal.fire({
		                        title: 'Data has been updated!',
		                        icon: 'success',
		                    }).then((result) => {
								window.location = '{{ route('Surat.SuratPermohonan.index') }}'	  
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

		$('.approve_rw').on('click', function(e) {
		e.preventDefault();
			var id = $('input[class=surat_permohonan_id]').val();

			swal.fire({
	        title: "Konfirmasi",
	        text: "Apakah anda yakin untuk approve draft ini?",
	        icon: "warning",
	        showCancelButton: true,
	        }).then((result) => {
                if (result.isConfirmed) {
				   $.ajax({
		                type: 'PUT',
		                url: '{{ route("Surat.SuratPermohonan.ApprovalRW",'') }}' +'/' +id,
		                headers: {
			            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			            },
		                data: $('#form-edit').serialize(),
		                success: function(data) {
		                	console.log(data);
		                    swal.fire({
		                        title: 'Data has been updated!',
		                        icon: 'success',
		                    }).then((result) => {
								window.location = '{{ route('Surat.SuratPermohonan.index') }}'	  
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