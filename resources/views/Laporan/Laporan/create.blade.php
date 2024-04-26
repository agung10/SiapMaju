@extends('layouts.master')
@section('content')
<style>
	.imageContainer {
		width:  220px;
		height:  220px;
		border-radius: 3px;
		margin-top: 20px;
	}
	.imagePicture {
		width:  200px;
		height:  200px;
	}
	.materiPicture {
		width:  200px;
		height:  200px;
	}
</style>
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Tambah Laporan
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<form id="headerForm" action="{{ route('Laporan.Laporan.store') }}" enctype="multipart/form-data" method="POST">
			@csrf
			<div class="card-body">
				<h4>Data Laporan</h4>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<div class="col-md-12">
							<div class="form-group">
								<label>Judul <span class="text-danger">*</span></label>
								<input type="text" name="laporan" class="form-control" />
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="exampleSelect1">Kategori <span class="text-danger">*</span></label>
								<select name="kat_laporan_id" class="form-control">
									{!! $resultKat !!}
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="col-md-12">
							<div class="form-group">
								<label>Deskripsi <span class="text-danger">*</span></label>
								<textarea name="detail_laporan" class="form-control" rows="6"></textarea>
							</div>
						</div>
					</div>
				</div>

					<br />
					<h4>Lampiran</h4>
					<br />
					<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Cover</label>
									<div class="custom-file">
										<input name="image" type="file" class="custom-file-input" accept="image/*" />
										<label class="custom-file-label" for="customFile">Choose file </label>
									</div>
									<div class="imageContainer">
										<img class="imagePicture" src="{{asset('images/NoPic.png')}}">
									</div>
								</div>
							</div>
							
							
							<div class="col-md-6 mt-1">
								<div class="form-group">
									<label>Upload Materi</label>
									<div class="custom-file">
										<input name="upload_materi" type="file" class="custom-file-input" accept="image/*,.pdf" />
										<label id="label-upload-materi" class="custom-file-label" for="customFile">Choose file </label>
									</div>
								</div>
							</div>
					</div>

				<br />
				<h4 class="mt-3">Asal Laporan</h4>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Kelurahan</label>
							<select class="form-control" name="kelurahan_id" id="kelurahan">
								{!! $resultKelurahan !!}
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>RW</label>
							<select class="form-control" name="rw_id" id="rw">
								@if ($isAdmin) 
									<option></option>
								@elseif ($isRw || $isRt) 
									{!! $resultRW !!}
								@endif
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>RT</label>
							<select class="form-control" name="rt_id" id="rt">
								@if ($isAdmin) 
									<option></option>
								@elseif ($isRw || $isRt) 
									{!! $resultRT !!}
								@endif
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>
</div>

{!! JsValidator::formRequest('App\Http\Requests\Laporan\LaporanRequest','#headerForm') !!}
<script>
	const storeData = async () => {
		loading()

		const formData = new FormData(document.getElementById('headerForm'));

		const res = async () => {
			return await fetch("{{route('Laporan.Laporan.store')}}",{
						headers:{
							"X-CSRF-TOKEN":"{{ csrf_token() }}"
						},
						method:'post',
						body:formData
					})
					.then(response => response.json())
					.catch(() => {
						KTApp.unblockPage()
						swal.fire('Maaf Terjadi Kesalahan','','error')
							.then(result => {
								if(result.isConfirmed) window.location.reload()
							})
					})
		} 

		const {status} = await res()
		
		if(status === 'success'){

			localStorage.setItem('success','Berhasil Menambahkan Laporan');

			return location.replace('{{route("Laporan.Laporan.index")}}');
		}
	}

	document.querySelector('#headerForm').addEventListener('submit', (e) => {
		e.preventDefault();
		const form  = e.currentTarget
		const valid = $(form).valid()
		if(!valid) return;

		storeData();
	})

	function loading(){
		KTApp.blockPage({
			overlayColor: '#000000',
			state: 'primary',
			message: 'Mohon Tunggu Sebentar'
 		});
	}

	// Image Preview Script
    const imgmateriField = document.querySelector('input[name="image"]')

    if(imgmateriField){

        imgmateriField.addEventListener('change',(event)=> {
            const output = document.querySelector('.imagePicture');

            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = () => {
                URL.revokeObjectURL(output.src)
            }
        });
    }

    $('input[name="upload_materi"]').on('change',function(){
        //get the file name
        var fileName = $(this)[0].files[0].name;
        
        //replace the "Choose a file" label
        $('#label-upload-materi').text(fileName);
    })

	var isAdmin = '<?php echo $isAdmin; ?>';

    var KTSelect2 = function() {
        // Private functions
        var demos = function() {
			$('select[name=kat_laporan_id]').select2({ width: '100%', placeholder: '-- Pilih Kategori Laporan --'})
			
			$('select[name=kelurahan_id]').select2({ width: '100%', placeholder: '-- Pilih Kelurahan --'})
			$('select[name=rw_id]').select2({ width: '100%', placeholder: '-- Pilih RW --'})
			$('select[name=rt_id]').select2({ width: '100%', placeholder: '-- Pilih RT --'})

			const spinner = $('<div id="select-spinner" class="spinner spinner-right"></div>');
            const selectKelurahan = $('#kelurahan')
            const selectRW = $('#rw')
            const selectRT = $('#rt')

			// Get data from local storage form data
			var formData = localStorage.getItem("temporary_form_data");
			if (formData) {
				localStorage.removeItem("temporary_form_data");
				var formDataParse = JSON.parse(formData)
				
				var valKelurahanId = formDataParse.kelurahan_id;
				var valRWId = formDataParse.rw_id;
				var valRTId = formDataParse.rt_id;
			}

			setTimeout(async () => {
				if (valKelurahanId) {
					$('select[name=kelurahan_id]').val(valKelurahanId).trigger('change');
				}
			}, 100);
			
			if (isAdmin) {
                selectRW.html('');
                selectRW.prop("disabled", true);

                selectRT.html('');
                selectRT.prop("disabled", true);
            }

            $('body').on('change', 'select[name=kelurahan_id]', async function(){
				let kelurahan = $(this).val()
				if (kelurahan) {
					let subOption = '<option></option>';
					let url = @json(route('DetailAlamat.getRW'));
						url += `?kelurahanID=${ encodeURIComponent(kelurahan) }`
	
					$(this).prop("disabled", true);
					selectRW.parent().append(spinner);
					selectRW.html('');
					selectRW.prop("disabled", true);
	
					selectRT.html('');
					selectRT.prop("disabled", true);
	
					const fetchRW = await fetch(url).then(res => res.json()).catch((err) => {
						selectRW.prop("disabled", false);
						spinner.remove()
						Swal.fire({
							title: 'Terjadi kesalahan dalam menghubungi server, silahkan coba lagi...',
							icon: 'warning'
						})
					});
	
					for(const data of fetchRW) {
						subOption += `<option value="${data.rw_id}">${data.rw}</option>`;
					}
	
					selectRW.html(subOption);
					selectRW.select2({ 
						placeholder: '-- Pilih RW --', 
						width: '100%'
					});
					$(this).prop("disabled", false);
					selectRW.prop("disabled", false);
					spinner.remove();

					if(valRWId)
					{
						$('select[name=rw_id]').val(valRWId).trigger('change');
					}
				}
			})

            $('body').on('change', 'select[name=rw_id]', async function(){
				let rw = $(this).val()
				if (rw) {
					let subOption = '<option></option>';
					let url = @json(route('DetailAlamat.getRT'));
						url += `?rwID=${ encodeURIComponent(rw) }`
	
					$(this).prop("disabled", true);
					selectRT.parent().append(spinner);
					selectRT.html('');
					selectRT.prop("disabled", true);
	
					
					const fetchRT = await fetch(url).then(res => res.json()).catch((err) => {
						selectRT.prop("disabled", false);
						spinner.remove()
						Swal.fire({
							title: 'Terjadi kesalahan dalam menghubungi server, silahkan coba lagi...',
							icon: 'warning'
						})
					});
	
					for(const data of fetchRT) {
						subOption += `<option value="${data.rt_id}">${data.rt}</option>`;
					}
	
					selectRT.html(subOption);
					selectRT.select2({ 
						placeholder: '-- Pilih RT --', 
						width: '100%'
					});
					$(this).prop("disabled", false);
					selectRT.prop("disabled", false);
					spinner.remove();

					if(valRTId)
					{
						$('select[name=rt_id]').val(valRTId).trigger('change');
					}
				}
			})
        }

        // Functions
        return {
            init: function() {
                demos();
            }
        };
    }();

    // Initialization
    jQuery(document).ready(function() {
        KTSelect2.init();
    });
</script>
@endsection