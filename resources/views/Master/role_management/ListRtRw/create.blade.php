@extends('layouts.master')

@section('content')
@include('Surat.SuratPermohonan.css')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Buat Surat Permohonan
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
							<h4>Form Surat Permohonan</h4>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Nama Warga<span class="text-danger">*</span></label>
									<select class="form-control warga" name="anggota_keluarga_id">
										{!! $selectNamaWarga !!}
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Jenis Surat<span class="text-danger">*</span></label>
									<select class="form-control jenis_surat" name="jenis_surat_id">
										{!! $jenisSurat !!}
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Tempat Lahir<span class="text-danger">*</span></label>
									<input type="text" name="tempat_lahir" class="form-control" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Tanggal Lahir<span class="text-danger">*</span></label>
									<input type="date" name="tgl_lahir" class="form-control" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Bangsa<span class="text-danger">*</span></label>
									<input type="text" name="bangsa" class="form-control" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Status Pernikahan<span class="text-danger">*</span></label>
									<select class="form-control status_pernikahan" name="status_pernikahan_id">
										{!! $pernikahan !!}
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Agama<span class="text-danger">*</span></label>
									<select class="form-control agama" name="agama_id">
										{!! $agama !!}
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Pekerjaan<span class="text-danger">*</span></label>
									<input type="text" name="pekerjaan" class="form-control" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>No KK<span class="text-danger">*</span></label>
									<input type="text" name="no_kk" class="form-control no_kk" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>No KTP<span class="text-danger">*</span></label>
									<input type="text" name="no_ktp" class="form-control" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Tanggal Permohonan<span class="text-danger">*</span></label>
									<input type="date" name="tgl_permohonan" class="form-control" />
								</div>
							</div>
						</div>
						<div class="row">
							<h4>Lampiran</h4>
						</div>
						<div class="row lampiran-container">
							<div class="col-md-3">
								<div class="form-group">
									<label>Lampiran 1</label>
									<input class="lampiran" type="file" name="lampiran1" />
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Lampiran 2</label>
									<input class="lampiran" type="file" name="lampiran2" />
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Lampiran 3</label>
									<input class="lampiran" type="file" name="lampiran3" />
								</div>
							</div>
						</div>
						<div class="row btn-keluarga-container">
							<div class="col-md-6"></div>
							<div class="col-md-6">
								<button type="submit" class="btn btn-primary submit float-right">Simpan Surat Permohonan</button>
							</div>
						</div>
						<input type="hidden" name="rt_id">
						<input type="hidden" name="rw_id">
						<input type="hidden" name="nama_lengkap">
						<input type="hidden" name="alamat">
						<input type="hidden" name="hal">
						<input type="hidden" name="lampiran">
					</form>
				</div>
			</div>
			<div class="card-footer">
				<button type="reset" onClick='goBack()' class="btn btn-secondary">Back</button>
			</div>
	</div>

{!! JsValidator::formRequest('App\Http\Requests\Surat\SuratRequest','.form-surat') !!}
@include('Surat.SuratPermohonan.createScript')
@endsection