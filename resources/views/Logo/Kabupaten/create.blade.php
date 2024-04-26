@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Tambah Logo Kabupaten
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
        <form id="headerForm" action="{{ route('LogoKabupaten.store') }}" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <div class="imageContainer">
                        <img class="imagePicture" src="{{asset('images/NoPic.png')}}"></img>
                    </div>
                    <br>
                    <label>Cap Kelurahan</label>
                    <div class="custom-file">
                        <input name="logo" type="file" class="custom-file-input" id="customFile" />
                        <label class="custom-file-label" for="customFile">Choose file </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Kota/Kabupaten<span class="text-danger">*</span></label>
                    <select class="form-control" name="city_id" id="city">
                        {{!! $resultCity !!}}
                    </select>
                </div>
                <input type="hidden" name="nama_kabupaten" id="nama_kabupaten">
            </div>
            <div class="card-footer">
                @include('partials.buttons.submit')
            </div>
        </form>
    </div>
</div>

{!! JsValidator::formRequest('App\Http\Requests\Logo\LogoKabupatenRequest','#headerForm') !!}
<script>
    const storeData = async () => {
		loading()

		const formData = new FormData(document.getElementById('headerForm'));

		const res = async () => {
			return await fetch("{{route('LogoKabupaten.store')}}",{
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
			localStorage.setItem('success','Berhasil Menambahkan Logo Kabupaten');
			return location.replace('{{route("LogoKabupaten.index")}}');
		}
	}

	document.querySelector('#headerForm').addEventListener('submit', (e) => {
		e.preventDefault();
		const form  = e.currentTarget
		const valid = $(form).valid()
		if(!valid) return;

        var cityVal = document.getElementById("city").value;
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
    const iField = document.querySelector('input[name="logo"]')
    if(iField){
        iField.addEventListener('change',(event)=> {
            const output = document.querySelector('.imagePicture');

            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = () => {
                URL.revokeObjectURL(output.src)
            }
        });
    }

	// Class Select2 definition
    var KTSelect2 = function() {
        return {
            init: function() {
                $('select[name=city_id]').select2({ placeholder: '-- Pilih Kota/Kabupaten --' });
            }
        };
    }();

    // Initialization
    jQuery(document).ready(function() {
        KTSelect2.init();
    });

    var city = document.getElementById('city');
    var nama_kabupaten = document.getElementById('nama_kabupaten');
    city.onchange = function() {
        nama_kabupaten.value = city.options[city.selectedIndex].text;
    }
</script>
@endsection