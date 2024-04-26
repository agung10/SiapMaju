@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Buat Pembagian PBB
                    </h5>
                </div>
            </div>
        </div>
    </div>
    @include('partials.alert')
    <div class="card card-custom gutter-b">
		<form id="form" action="{{ route('pbb.pembagian.store') }}" enctype="multipart/form-data" method="POST">
			@csrf
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Blok <span class="text-danger">*</span></label>
							<select name="blok_id" class="form-control">
							  <option></option>
							    @foreach($blok as $b)
							        <option value="{{ $b->blok_id }}" data-nop="{{ $b->nop }}"> 
							          {{ $b->nama_blok }}
							        </option>
							    @endforeach
							</select>
						</div>
						<div class="form-group">
							<label>Penerima <span class="text-danger">*</span></label>
							@include('partials.form-select', [
		                      'title'       => 'Penerima',
		                      'name'        => 'anggota_keluarga_id',
		                      'data'        => []
		                    ])
						</div>
						<div>
							<label>Foto Penerimaan</label>
							<div class="form-group">
								<div class="custom-file">
									<input name="foto_terima" type="file" class="custom-file-input" id="customFile" accept="image/*" />
									<label class="custom-file-label" for="customFile" id="file-name">Choose file </label>
								</div>
							</div>
							<div class="imageContainer">
								<img class="imagePicture" src="{{asset('images/placeholder.jpg')}}"></img>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label>NOP <span class="text-danger">*</span></label>
							<input type="text" name="nop" class="form-control" readonly  style="background-color: #F3F6F9;opacity: 1;"/>
						</div>

						<div class="form-group">
							<label>Tanggal Terima <span class="text-danger">*</span></label>
							<div class="input-group">
								<input name="tgl_terima" readonly type="text" class="form-control" value="{{ date('d-m-Y') }}" placeholder="Pilih Tanggal"/>
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
								<input name="tahun_pajak" readonly type="text" class="form-control" value="{{ date('Y') }}" placeholder="Pilih Tanggal"/>
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

{!! JsValidator::formRequest('App\Http\Requests\PBB\PembagianRequest','#form') !!}
<script>
	$("document").ready(() => {
		$("select[name=blok_id]").select2({ 
	      placeholder: '-- Pilih Blok --', 
	      width: '100%'
	    });

	    const getWargaByBlok = async () => {
	        loading();

	        const blokId = $("select[name=blok_id]").val();
	        const nop = $('select[name=blok_id] option:selected').data('nop')
	        
	        let url = @json(route("pbb.pembagian.getWargaByBlok"));
	        url += `?blok_id=${blokId}`;
	        
	        return await fetch(url)
	            .then(async (res) => {
	                const { data: warga } = await res.json();
	                let listWarga = "<option></option>";

	                for (const i in warga) {
	                	listWarga += `<option value="${i}">${warga[i]}</option>`;
	                }

	                $("select[name=anggota_keluarga_id]").html(listWarga);
	                $("select[name=anggota_keluarga_id]").select2({
	                    placeholder: "-- Pilih Penerima --",
	                    width: "100%",
	                });

	                $('input[name=nop]').val( nop )
	                
	                KTApp.unblockPage();
	            })
	            .catch((err) => {
	                KTApp.unblockPage();
	                swal.fire("Maaf Terjadi Kesalahan", "", "error");
	            });
	    };

	    $("body").on("change", "select[name=blok_id]", getWargaByBlok);
	    
	    $("input[name=tgl_terima]").datepicker({
	        format: "dd-mm-yyyy",
	        autoclose: true,
	        todayHighlight: true
	    });

	    $("input[name=tahun_pajak]").datepicker({
	        format: "yyyy",
		    viewMode: "years", 
		    minViewMode: "years",
		    datesDisabled: ["2022"],
		    autoclose: true
	    });

	    $("body").on("change","input[name=foto_terima]", function(e) {
			let that = e.currentTarget
	        if (that.files && that.files[0]) {
	        	
	            $('#file-name').html(that.files[0].name)
				const output = document.querySelector(".imagePicture");
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