@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Tambah Agenda
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
		<form id="headerForm" action="{{ route('Agenda.AgendaKegiatan.store') }}" enctype="multipart/form-data" method="POST">
			@csrf
			<div class="card-body">
				<div class="form-group">
					<label>Nama Agenda<span class="text-danger">*</span></label>
					<input type="text" name="nama_agenda" class="form-control" />
				</div>
				<div class="form-group">
					<label>Lokasi<span class="text-danger">*</span></label>
					<input name="lokasi" type="text" class="form-control" />
				</div>
				<div class="form-group">
					<label>Tanggal<span class="text-danger">*</span></label>
					<input name="tanggal" type="date" class="form-control" />
				</div>
				<div class="form-group">
					<label>Jam<span class="text-danger">*</span></label>
					<input name="jam" type="time" class="form-control" />
				</div>
				<div class="form-group">
					<label>Agenda<span class="text-danger">*</span></label>
					<textarea name="agenda" class="form-control" rows="3"></textarea>
				</div>
				<div class="form-group">
					<label for="exampleSelect1">Program<span class="text-danger">*</span></label>
					<select name="program_id" class="form-control">
						{!! $result !!}
					</select>
				</div>
				<div class="form-group">
					<label for="exampleSelect1">Kategori Sumber Biaya</label>
					<select name="kat_sumber_biaya_id" class="form-control">
						{!! $katSumberBiaya !!}
					</select>
				</div>
				<div class="form-group">
					<label for="exampleSelect1">Nilai</label>
					<input type="number" name="nilai" class="form-control" placeholder="Masukkan nilai">
				</div>
				<div class="form-group">
					<div class="imageContainer">
						<img class="imagePicture" src="{{asset('images/NoPic.png')}}" />
					</div>
					<label>Gambar</label>
					<div class="custom-file">
						<input name="image" type="file" class="custom-file-input" id="customFile"/>
						<label class="custom-file-label" for="customFile">Choose file </label>
					</div>
				</div>
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>

{!! JsValidator::formRequest('App\Http\Requests\Agenda\AgendaKegiatanRequest','#headerForm') !!}
<script>
	const storeData = async () => {
		loading()

		const formData = new FormData(document.getElementById('headerForm'));

		const res = async () => {
			return await fetch("{{route('Agenda.AgendaKegiatan.store')}}",{
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

			localStorage.setItem('success','Berhasil Ditambahkan di Agenda Kegiatan');

			return location.replace('{{route("Agenda.AgendaKegiatan.index")}}');
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
    const imgeField = document.querySelector('input[name="image"]')

    if(imgeField){

        imgeField.addEventListener('change',(event)=> {
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
                $('select[name=program_id]').select2({ width: '100%', placeholder: '-- Pilih Program --'})
                $('select[name=kat_sumber_biaya_id]').select2({ width: '100%', placeholder: '-- Pilih Kategori Sumber Biaya --'})
            }
        };
    }();

    // Initialization
    jQuery(document).ready(function() {
        KTSelect2.init();
    });
</script>
@endsection

