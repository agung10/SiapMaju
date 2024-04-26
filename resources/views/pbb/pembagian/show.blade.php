@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Detail Pembagian PBB
                    </h5>
                </div>
            </div>
        </div>
    </div>
    @include('partials.alert')
    <div class="card card-custom gutter-b">
		<form>
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Blok</label>
							<input type="text" name="blok" value="{{ $pbb->blok->nama_blok }}" class="form-control" disabled />
						</div>
						<div class="form-group">
							<label>Penerima</label>
							<input type="text" name="blok" value="{{ $pbb->anggotaKeluarga->nama }}" class="form-control" disabled />
						</div>
						<div class="form-group">
							<label>Foto Penerimaan PBB<span class="text-danger">*</span></label>
							<div class="imageContainer">
								<img class="imagePicture" src="{{ $pbb->foto_terima ? \helper::imageLoad('pbb', $pbb->foto_terima) : asset('images/placeholder.jpg') }}"></img>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label>NOP <span class="text-danger">*</span></label>
							<input type="text" name="nop" value="{{ $pbb->nop }}" class="form-control" disabled />
						</div>

						<div class="form-group">
							<label>Tanggal Terima <span class="text-danger">*</span></label>
							<div class="input-group">
								<input name="tgl_terima" type="text" class="form-control" value="{{ date('d F Y', strtotime($pbb->tgl_terima)) }}" placeholder="Pilih Tanggal" disabled />
								<div class="input-group-append">
									<span class="input-group-text">
										<i class="la la-calendar icon-lg"></i>
									</span>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label>Tahun Pajak <span class="text-danger">*</span></label>
							<div class="input-group">
								<input name="tahun_pajak" type="text" class="form-control" value="{{ $pbb->tahun_pajak }}" placeholder="Pilih Tanggal" disabled />
								<div class="input-group-append">
									<span class="input-group-text">
										<i class="la la-calendar icon-lg"></i>
									</span>
								</div>
							</div>
						</div>
					</div>


				</div>
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>
@endsection