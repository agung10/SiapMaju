@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Bayar PBB
                    </h5>
                </div>
            </div>
        </div>
    </div>
    @include('partials.alert')
    <div class="card card-custom gutter-b">
		<form id="form" action="{{ route('pbb.pembayaran.update', $pbb->encryptedId) }}" enctype="multipart/form-data" method="POST">
			@csrf
            {{ method_field('PATCH') }}
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
							<label>Tanggal Bayar <span class="text-danger">*</span></label>
							<div class="input-group">
								<input name="tgl_bayar" type="text" class="form-control" value="{{ date('d F Y') }}" placeholder="Pilih Tanggal"/>
								<div class="input-group-append">
									<span class="input-group-text">
										<i class="la la-calendar icon-lg"></i>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Nilai <span class="text-danger">*</span></label>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">
										Rp
									</span>
								</div>
								<input type="text" name="nilai" value="{{ \helper::number_formats($pbb->nilai, 'view', 0) }}" class="form-control" />
							</div>
						</div>
						<div>
							<label>Bukti Bayar<span class="text-danger">*</span></label>
							<div class="form-group">
								<div class="custom-file">
									<input name="bukti_bayar" type="file" class="custom-file-input" id="customFile" accept="image/*" />
									<label class="custom-file-label" for="customFile" id="file-name">{{ $pbb->bukti_bayar ? $pbb->bukti_bayar : 'Choose file' }} </label>
								</div>
							</div>
							<div class="imageContainer">
								<img class="imagePicture buktiBayar" src="{{ $pbb->bukti_bayar ? \helper::imageLoad('pbb', $pbb->bukti_bayar) : asset('images/placeholder.jpg') }}"></img>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label>NOP</label>
							<input type="text" name="nop" value="{{ $pbb->nop }}" class="form-control" disabled />
						</div>

						<div class="form-group">
							<label>Tanggal Terima</label>
							<div class="input-group">
								<input name="tgl_terima" type="text" class="form-control" value="{{ date('d F Y', strtotime($pbb->tgl_terima)) }}" placeholder="Pilih Tanggal" disabled/>
								<div class="input-group-append">
									<span class="input-group-text">
										<i class="la la-calendar icon-lg"></i>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Tahun Pajak</label>
							<div class="input-group">
								<input name="tahun_pajak" type="text" class="form-control" value="{{ $pbb->tahun_pajak }}" placeholder="Pilih Tanggal" disabled/>
								<div class="input-group-append">
									<span class="input-group-text">
										<i class="la la-calendar icon-lg"></i>
									</span>
								</div>
							</div>
						</div>
						<div>
							<label>Foto Penerimaan</label>
							<div class="imageContainer">
								<img class="imagePicture" src="{{ $pbb->foto_terima ? \helper::imageLoad('pbb', $pbb->foto_terima) : asset('images/placeholder.jpg') }}"></img>
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

{!! JsValidator::formRequest('App\Http\Requests\PBB\PembayaranRequest','#form') !!}
<script type="text/javascript" src="{{ asset('assets/js/jquery-number.min.js') }}"></script>
<script>
	<?= \helper::number_formats('$("input[name=\'nilai\']")', 'js', 0) ?>

	$("document").ready(() => {
		
		$("input[name=tgl_bayar]").datepicker({
	        format: "dd MM yyyy",
	        autoclose: true,
	        todayHighlight: true,
	    });

	    $("body").on("change","input[name=bukti_bayar]", function(e) {
			let that = e.currentTarget
	        if (that.files && that.files[0]) {
	        	
	            $('#file-name').html(that.files[0].name)
				const output = document.querySelector(".buktiBayar");
				let reader   = new FileReader()
	            reader.onload = (e) => {
	              output.src =  e.target.result;
	            }
	            reader.readAsDataURL(that.files[0])
	        }
	    })
	});

</script>
@endsection