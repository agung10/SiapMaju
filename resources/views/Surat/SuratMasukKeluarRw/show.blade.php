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
			<form class="form-surat" enctype="multipart/form-data">
						@csrf
						<div class="row">
							<h4>Form Surat Masuk dan Keluar RW</h4>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Jenis Surat</label>
									<select disabled class="form-control jenis-surat-id" name="jenis_surat_rw_id">
										{!! $jenisSurat !!}
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Sifat Surat</label>
									<select disabled class="form-control sifat-surat-id" name="sifat_surat_id">
										{!! $sifatSurat !!}
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>No Surat</label>
									<input disabled type="text" name="no_surat" value="{{!empty($data->no_surat) ? $data->no_surat : ''}}" class="form-control" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Lampiran</label>
									<input disabled type="text" value="{{!empty($data->lampiran) ? $data->lampiran : ''}}" name="lampiran" class="form-control" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Hal</label>
									<input disabled type="text" value="{{!empty($data->hal) ? $data->hal : ''}}" name="hal" class="form-control" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Tanggal Surat</label>
									<input disabled type="date" value="{{!empty($data->tgl_surat) ? $data->tgl_surat : ''}}" name="tgl_surat" class="form-control" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Asal Surat</label>
									<select disabled class="form-control asal-surat" name="asal_surat">
										{!! $asalSurat !!}
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Nama Pengirim</label>
									<input disabled type="text" value="{{!empty($data->nama_pengirim) ? $data->nama_pengirim : ''}}" name="nama_pengirim" class="form-control" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Tujuan Surat</label>
									<select disabled class="form-control tujuan-surat" name="tujuan_surat">
										{!! $tujuanSurat !!}
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Nama Penerima</label>
									<input disabled type="text" value="{{!empty($data->nama_penerima) ? $data->nama_penerima : ''}}" name="nama_penerima" class="form-control" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Isi Surat</label>
									<textarea disabled name="isi_surat" class="form-control">{{!empty($data->isi_surat) ? $data->isi_surat : ''}}</textarea>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Tujuan Surat</label>
									<select disabled class="form-control warga" name="warga_id">
										{!! $warga !!}
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								@if($data->upload_file)
									<a target="_blank" href='{{asset("uploaded_files/surat_masuk_keluar_rw/$data->upload_file")}}' class="btn btn-primary btn-berkas">Lihat berkas file</a>
								@endif
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Surat Balasan</span></label>
									<select disabled class="form-control surat-balasan" name="surat_balasan_id">
										{!! $suratBalasan !!}
									</select>
								</div>
							</div>
						</div>
						<input type="hidden" name="rt_id">
						<input type="hidden" name="rw_id">
						<div class="card-footer">
							<button type="reset" onClick='goBack()' class="btn btn-secondary">Back</button>
						</div>
					</div>
			</form>
			</div>
		</div>	
	</div>
<script>
	window.addEventListener('DOMContentLoaded',() => {

		berkasFileHandler()

		function berkasFileHandler(){
			const berkasFile = '{{$data->upload_file ?? ""}}' 
			const extension = berkasFile.split('.')[1]
										.toLowerCase()

			if(extension == 'pdf') return;

			$('.btn-berkas').magnificPopup({
						type:'image',
			})
		}
	})
</script>
@endsection