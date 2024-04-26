@extends('layouts.master')

@section('content')
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Tambah Peraturan
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<form id="headerForm" action="{{route('Kajian.Konten.store')}}" enctype="multipart/form-data" method="POST">
			@csrf
			<div class="card-body">
				<h4>Data Peraturan</h4>
				<hr>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<div class="imageContainerEdit">
								<img class="imagePicture" src="{{asset('images/NoPic.png')}}" />
							</div><br>
							<label>Image<span class="text-danger">*</span></label>
							<div class="custom-file">
								<input name="image" type="file" class="custom-file-input" id="customFile" />
								<label class="custom-file-label" for="customFile">Choose file </label>
								{{-- <div style="font-size:10px;margin:5px;color:red" class="err-image"></div> --}}
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label for="exampleSelect1">Kategori Peraturan <span class="text-danger">*</span></label>
							<select name="kat_kajian_id" class="form-control" id="exampleSelect1">
								{!! $kat_kajian !!}
							</select>
							{{-- <div style="font-size:10px;margin:5px;color:red" class="err-kat_kajian_id"></div> --}}
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>Judul<span class="text-danger">*</span> </label>
							<input type="text" name="judul" class="form-control" placeholder="Masukan Penulis" />
							{{-- <div style="font-size:10px;margin:5px;color:red" class="err-judul"></div> --}}
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>Penulis<span class="text-danger">*</span> </label>
							<input type="text" name="author" class="form-control" placeholder="Masukan Penulis" />
							{{-- <div style="font-size:10px;margin:5px;color:red" class="err-author"></div> --}}
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label for="exampleTextarea">Peraturan <span class="text-danger">*</span></label>
							<textarea name="kajian" class="form-control" id="exampleTextarea" rows="3"></textarea>
							{{-- <div style="font-size:10px;margin:5px;color:red" class="err-kajian"></div> --}}
						</div>
					</div><br>
					<div class="col-md-12">
						<div class="form-group">
							<label>Materi 1 </label>
							<div class="inputContainer">
								<div class="custom-file" style="width: 790px;">
									<input onChange="putFileName(event)" name="upload_materi_1" type="file"
										class="custom-file-input" id="customFile" />
									<label class="custom-file-label" for="customFile">Choose file </label>
									{{-- <div style="font-size:10px;margin:5px;color:red" class="err-upload_materi_1"></div> --}}
								</div>
								<a style="width:45px;" class="btn btn-primary mt-3 showButton"><i
										class="flaticon2-plus"></i></a>
								<a style="width:45px;display:none" class="btn btn-danger mt-3 hideButton"><i
										class="flaticon2-cross"></i></a>
							</div>
						</div>
					</div>
				</div>

				<h4 class="mt-3">Data Detail Alamat</h4>
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

	<div id="loadingModal" class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content loadingBoostrap">
				<div class="modal-body">
					<div class="spinner-border text-danger mb-4" style="width:80px;height:80px;" role="status">
						<span class="sr-only text-center">Loading...</span>
					</div>
					<div>
						<span class="loadingText">Uploading...</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

{!! JsValidator::formRequest('App\Http\Requests\Kajian\KontenRequest','#headerForm') !!}
<script>
	const storeData = async () => {

		// $('#loadingModal').modal('show');
		loading()

		const formData = new FormData(document.getElementById('headerForm'));

		const res = async () => {
			return await fetch("{{route('Kajian.Konten.store')}}",{
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

		const {status} = await res();
		
		if(status === 'success'){

			// document.querySelector('.loadingText').innerHTML = 'Upload Berhasil'
			// $('#loadingModal').modal('hide');

			localStorage.setItem('success','Peraturan Berhasil Ditambahkan');
			return location.replace('{{route("Kajian.Konten.index")}}');
		}
		// else{

		// 	$('#loadingModal').modal('hide');

		// 	const {kat_kajian_id,
		// 		   kajian,
		// 		   author,
		// 		   judul,
		// 		   upload_materi_1,
		// 		   upload_materi_2,
		// 		   upload_materi_3,
		// 		   upload_materi_4,
		// 		   upload_materi_5,
		// 		   image} = errors;

		// 	document.querySelector('.err-kat_kajian_id').innerHTML = kat_kajian_id ? kat_kajian_id : '';
		// 	document.querySelector('.err-kajian').innerHTML = kajian ? kajian : '';
		// 	document.querySelector('.err-author').innerHTML = author ? author : '';
		// 	document.querySelector('.err-judul').innerHTML = judul ? judul : '';
		// 	document.querySelector('.err-upload_materi_1').innerHTML = upload_materi_1 ? upload_materi_1 : ''; 
		// 	document.querySelector('.err-upload_materi_2').innerHTML = upload_materi_2 ? upload_materi_2 : ''; 
		// 	document.querySelector('.err-upload_materi_3').innerHTML = upload_materi_3 ? upload_materi_3 : ''; 
		// 	document.querySelector('.err-upload_materi_4').innerHTML = upload_materi_4 ? upload_materi_4 : ''; 
		// 	document.querySelector('.err-upload_materi_5').innerHTML = upload_materi_5 ? upload_materi_5 : ''; 
		// 	document.querySelector('.err-image').innerHTML = image ? image : ''; 
		// }
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

	// form Script
	
	document.addEventListener('DOMContentLoaded',(e) => {
		document.querySelector('.showButton').style.display = '';
		localStorage.setItem('indexMateri','2');
	})

	document.querySelector('.showButton').addEventListener('click',(e) => {
		
		const i = localStorage.getItem('indexMateri');
		
		const el = `<div class="form-group materi${i} mt-5">
						<label>Materi ${i} </label>
						<div></div>
						<div class="inputContainer">
							<div class="custom-file" style="width: 790px;">
								<input onChange="putFileName(event)" name="upload_materi_${i}" type="file" class="custom-file-input" id="customFile"/>
								<label class="custom-file-label" for="customFile">Choose file </label>
								<div style="font-size:10px;margin:5px;color:red" class="err-upload_materi_${i}"></div>
							</div>
						</div>
					</div>`

		if(i < 6){

			const formParent = document.querySelector(`.showButton`)
									   ?.parentElement
									   ?.parentElement

			const loadingHTML = '<div class="spinner-border text-primary spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>'

			if(formParent == null){

				swal.fire({
					title:'Mohon Tunggu',
					html:loadingHTML,
					icon:'warning',
					timer:2500,
					showConfirmButton:false,
				});

				return;
			}

			formParent.insertAdjacentHTML('beforeend',el)

			localStorage.setItem('indexMateri',parseInt(i)+1)
		}

		if(i > 1){
			const element = document.querySelector('.hideButton')
									.style.display = ''
		}
	})

	document.querySelector('.hideButton').addEventListener('click',() => {

		const storage = JSON.stringify(parseInt(localStorage.getItem('indexMateri')))

		if(storage > 1){
			
			const i = storage > 2 ? storage-1 : storage-0;

			localStorage.setItem('indexMateri',i)

			const el = document.querySelector(`.materi${i}`)

			if(el){
				el.remove()
			}

			if(i === 2){
				document.querySelector('.hideButton').style.display = 'none'
			}
		}

	})

	const putFileName = (e) => {
		const fileName = e.target.files[0].name;

		e.target.nextSibling.nextSibling.innerHTML = fileName
	}

	var isAdmin = '<?php echo $isAdmin; ?>';

    var KTSelect2 = function() {
        // Private functions
        var demos = function() {
			$('select[name=kat_kajian_id]').select2({ width: '100%', placeholder: '-- Pilih Kategori Peraturan --'})
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