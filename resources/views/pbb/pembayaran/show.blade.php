@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Detail Pembayaran PBB
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
							<label>Nilai </label>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">
										Rp
									</span>
								</div>
								<input type="text" name="nilai" value="{{ \helper::number_formats($pbb->nilai, 'view', 0) }}" class="form-control" disabled />
							</div>
						</div>
						@php
						$desc = $pbb->tgl_bayar ? 'PAID' : 'UNPAID';
						$style = $pbb->tgl_bayar
								? 'border: 1px solid #1BC5BD;background-color: #1BC5BD !important;color:#fff;font-weight: bold;'
								: 'border: 1px solid #F64E60;background-color: #F64E60 !important;color:#fff;font-weight: bold;';
						@endphp
						<div class="form-group">
							<label>Status</label>
							<div style="width: 17%">
								<input type="text" value="{{ $desc }}" class="form-control text-center" disabled style="{{ $style }}"/>
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

						<div class="form-group">
							<label>Tanggal Bayar <span class="text-danger">*</span></label>
							<div class="input-group">
								<input name="tgl_bayar" type="text" class="form-control" value="{{ $pbb->tgl_bayar ? date('d F Y', strtotime($pbb->tgl_bayar)) : '-' }}" placeholder="Pilih Tanggal" disabled />
								<div class="input-group-append">
									<span class="input-group-text">
										<i class="la la-calendar icon-lg"></i>
									</span>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<label>Foto Penerimaan PBB</label>
						<div class="imageContainer">
							<img class="imagePicture" src="{{ $pbb->foto_terima ? \helper::imageLoad('pbb', $pbb->foto_terima) : asset('images/placeholder.jpg') }}"></img>
						</div>
					</div>

					<div class="col-md-6">
						<label>Foto Bukti Pembayaran</label>
						<div class="imageContainer">
							<img class="imagePicture" src="{{ $pbb->bukti_bayar ? \helper::imageLoad('pbb', $pbb->bukti_bayar) : asset('images/placeholder.jpg') }}"></img>
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