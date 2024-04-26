@extends('layouts.master')

@section('content')
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Tambah Konten
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<form id="headerForm" action="{{ route('Gallery.Content.store', Route::current()->parameter('galeri_id')) }}" enctype="multipart/form-data" method="POST">
			@csrf
			<div class="card-body">
				<div class="form-group">
					<label for="exampleSelect1">Gallery <span class="text-danger">*</span></label>
					
					<input type="hidden" class="form-control" id="galeri_id" name="galeri_id" value="{{ $resultGaleriID }}" readonly/>
					<input type="text" class="form-control" value="{{ $resultGaleri }}" readonly/>
				</div>
				<div class="form-group">
					<label for="exampleSelect1">Agenda<span class="text-danger">*</span> </label>
					<input type="hidden" class="form-control" id="agenda_id" name="agenda_id" value="{{ $resultAgendaID }}" readonly/>
					<input type="text" class="form-control" value="{{ $resultAgenda }}" readonly/>
				</div>
				<div class="form-group">
					<label for="exampleSelect1">Kategori File<span class="text-danger">*</span></label>
					<select name="kategori_file" class="form-control" id="exampleSelect1">
						<option disabled selected>Pilih Kategori</option>
						<option value="gambar">Gambar</option>
						<option value="video">Video</option>
					</select>
				</div>
				<div class="form-group">
					<label>Gambar</label>
					<div class="upload-container">
						<input type="file" id="file-input" name="file[]" multiple />
					</div>

					<div id="imageUploadContainer"></div>
				</div>
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>
</div>

{!! JsValidator::formRequest('App\Http\Requests\Gallery\GalleryContentRequest','#headerForm') !!}
<script>
	const storeData = async () => {
		let is_valid = false
		const collection = Array.from(document.getElementsByClassName('keteranganClass'))
		
		if (collection.length) is_valid = true
		collection.forEach(element => {
			if (!element.classList.contains('is-valid')) {
				is_valid = false
			} else {
				is_valid = true
			}
		});

		if (!is_valid) {
			swal.fire('Anda belum memasukkan isian data dengan lengkap !','','error');
			return 
		} 

		loading()

		const formData = new FormData(document.getElementById('headerForm'));

		const res = async () => {
			return await fetch("{{route('Gallery.Content.store', Route::current()->parameter('galeri_id'))}}",{
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

			localStorage.setItem('success','Berhasil Ditambahkan di Gallery');

			return location.replace(`{{route("Gallery.Content.index", Route::current()->parameter('galeri_id'))}}`);
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
    const fileField = document.querySelector('input[name="file"]')

    if(fileField){
        fileField.addEventListener('change',(event)=> {
            const output = document.querySelector('.imagePicture');

            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = () => {
                URL.revokeObjectURL(output.src)
            }
        });
    }

	// Delete image & detail
	function deleteElement(elementClass, fileName){
		if(!$(`.${elementClass}`)) return
		removeFiles(fileName);
		$(`.${elementClass}`).remove()
	}
	function removeFiles(fileName){
		const removedFiles = getFiles(dataTransfer)
		const newFiles = removedFiles.filter((val) => val.name !== fileName)

		dataTransfer = new DataTransfer()

		if(newFiles.length === 0) {
			$('#file-input')[0].files = dataTransfer.files
			return	
		}

		for(const file of newFiles)
			dataTransfer.items.add(file)
		$('#file-input')[0].files = dataTransfer.files
	}

	var KTSelect2 = function() {
        // Functions
        return {
            init: function() {
                $('select[name=kategori_file]').select2({ width: '100%', placeholder: '-- Pilih Kategori File --'})
            }
        };
    }();

    // Initialization
    jQuery(document).ready(function() {
        KTSelect2.init();
    });
</script>
@include('Gallery.Content.script')
@endsection

@push('custom-css')
@include('Gallery.Content.css')
@endpush