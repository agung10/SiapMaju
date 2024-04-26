@extends('layouts.master')

@section('content')
@include('Surat.SuratPermohonan.css')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Buat Surat Masuk dan Keluar RW
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
									<label>Jenis Surat<span class="text-danger">*</span></label>
									<select class="form-control jenis-surat-id" name="jenis_surat_rw_id">
										{!! $jenisSurat !!}
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Sifat Surat<span class="text-danger">*</span></label>
									<select class="form-control sifat-surat-id" name="sifat_surat_id">
										{!! $sifatSurat !!}
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>No Surat<span class="text-danger">*</span></label>
									<input type="text" name="no_surat" class="form-control" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Lampiran</label>
									<input type="text" name="lampiran" class="form-control" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Hal<span class="text-danger">*</span></label>
									<input type="text" name="hal" class="form-control" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Tanggal Surat<span class="text-danger">*</span></label>
									<input type="date" name="tgl_surat" class="form-control" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Asal Surat<span class="text-danger">*</span></label>
									<select class="form-control asal-surat" name="asal_surat">
										{!! $asalTujuanSurat !!}
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Nama Pengirim<span class="text-danger">*</span></label>
									<input type="text" name="nama_pengirim" class="form-control" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Tujuan Surat<span class="text-danger">*</span></label>
									<select class="form-control tujuan-surat" name="tujuan_surat">
										{!! $asalTujuanSurat !!}
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Nama Penerima<span class="text-danger">*</span></label>
									<input type="text" name="nama_penerima" class="form-control" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Isi Surat<span class="text-danger">*</span></label>
									<textarea name="isi_surat" class="form-control"></textarea>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Tujuan Surat<span class="text-danger">*</span></label>
									<select class="form-control warga" name="warga_id">
										{!! $warga !!}
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>File Upload</label>
									<input type="file" name="upload_file" class="form-control-file">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Surat Balasan</span></label>
									<select class="form-control surat-balasan" name="surat_balasan_id">
										{!! $suratBalasan !!}
									</select>
								</div>
							</div>
						</div>
						<div class="row btn-keluarga-container">
							<div class="col-md-6"></div>
							<div class="col-md-6">
								<button type="submit" class="btn btn-primary submit float-right">Simpan Surat Masuk dan Keluar RW</button>
							</div>
						</div>
						<input type="hidden" name="rt_id">
						<input type="hidden" name="rw_id">
					</form>
				</div>
			</div>
			<div class="card-footer">
				<button type="reset" onClick='goBack()' class="btn btn-secondary">Back</button>
			</div>
	</div>

{!! JsValidator::formRequest('App\Http\Requests\Surat\SuratMasukKeluarRwRequest','.form-surat') !!}
@include('Surat.SuratMasukKeluarRw.createScript')
@endsection