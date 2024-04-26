@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Edit Gallery
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
		<form id="headerForm" action="{{ route('Gallery.Content.update', ["galeri_id" => Route::current()->parameter('galeri_id'), "GalleryContent" => \Crypt::encryptString($data->galeri_konten_id)]) }}" enctype="multipart/form-data" method="POST">
			@csrf
            {{ method_field('PATCH') }}
			<div class="card-body">
				<div class="form-group">
					<label for="exampleSelect1">Galeri </label>
					<select name="galeri_id" class="form-control" id="exampleSelect1">
						{!! $resultGaleri !!}
					</select>
				</div>
				<div class="form-group">
					<label for="exampleSelect1">Agenda </label>
					<select name="agenda_id" class="form-control" id="exampleSelect1">
						{!! $result !!}
					</select>
				</div>
				<div class="form-group">
					<label for="exampleSelect1">Kategori File</label>
					<select name="kategori_file" class="form-control" id="exampleSelect1">
						<option  disabled>Pilih Kategori</option>
						<option value="image" {{$data->kategori_file == 'image' ? 'selected' : ''}}>Gambar</option>
						<option value="video" {{$data->kategori_file == 'video' ? 'selected' : ''}}>Video</option>
					</select>
				</div>
				<div class="form-group">
					<div class="imageContainer">
						<embed class="imagePicture" src="{{ ((!empty($data)) ? ((!empty($data->file)) ? (asset('upload/galeri/konten/'.$data->file)) : (asset('images/NoPic.png'))) : (asset('images/NoPic.png'))) }}"  width="200" height="200"></embed>
					</div>
					<label>Gambar / Video</label>
					<div class="custom-file">
						<input name="file" type="file" class="custom-file-input" id="customFile"/>
						<label class="custom-file-label" for="customFile">{{ $data->file }}</label>
					</div>						
				</div>
				<div class="form-group">
					<label>Keterangan<span class="text-danger">*</span></label>
					<textarea type="text" name="keterangan" class="form-control" rows="3" >{{ $data->keterangan }}</textarea>
				</div>
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>

{!! JsValidator::formRequest('App\Http\Requests\Gallery\GalleryContentRequest','#headerForm') !!}
<script>

	const editData = async () => {
		loading()

		const formData = new FormData(document.getElementById('headerForm'));

		const res = async () => {
			return await fetch(`{{route('Gallery.Content.update', ["galeri_id" => Route::current()->parameter('galeri_id'), "GalleryContent" => \Crypt::encryptString($data->galeri_konten_id)])}}`,{
									headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},
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

			localStorage.setItem('success','Gallery Berhasil Diupdate');

			return location.replace(`{{route("Gallery.Content.index", Route::current()->parameter('galeri_id'))}}`);
		}
		
	}

	document.querySelector('#headerForm').addEventListener('submit', (e) => {
		e.preventDefault();
		const form  = e.currentTarget
		const valid = $(form).valid()
		if(!valid) return;

		editData();
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
@endsection