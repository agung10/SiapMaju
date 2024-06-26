@extends('layouts.master')

@section('content')
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Tambah Cap RW
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<form id="dataForm" action="{{ route('Master.CapRW.store') }}" enctype="multipart/form-data" method="POST">
			@csrf
			<div class="card-body">
				<div class="form-group">
					<div class="imageContainer">
						<img class="imagePicture" src="{{asset('images/NoPic.png')}}"></img>
					</div>
					<br>
					<label>Cap RW</label>
					<div class="custom-file">
						<input name="cap_rw" type="file" class="custom-file-input" id="customFile" />
						<label class="custom-file-label" for="customFile">Choose file </label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Kelurahan<span class="text-danger">*</span></label>
							<select class="form-control" name="kelurahan_id" id="kelurahan">
								{!! $resultKelurahan !!}
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>RW<span class="text-danger">*</span></label>
							<select class="form-control" name="rw_id" id="rw">
								<option></option>
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

{!! JsValidator::formRequest('App\Http\Requests\Master\CapRWRequest','#dataForm') !!}
<script>
	const storeData = async () => {
		loading()

		const formData = new FormData(document.getElementById('dataForm'));

		const res = async () => {
			return await fetch("{{route('Master.CapRW.store')}}",{
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
			localStorage.setItem('success','Berhasil Menambahkan Cap RW');
			return location.replace('{{route("Master.CapRW.index")}}');
		}
	}

	document.querySelector('#dataForm').addEventListener('submit', (e) => {
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
    const iField = document.querySelector('input[name="cap_rw"]')

    if(iField){
        iField.addEventListener('change',(event)=> {
            const output = document.querySelector('.imagePicture');

            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = () => {
                URL.revokeObjectURL(output.src)
            }
        });
    }

    // Initialization
    jQuery(document).ready(function() {
        $('select[name=kelurahan_id]').select2({ width: '100%', placeholder: '-- Pilih Kelurahan --'})
		$('select[name=rw_id]').select2({ width: '100%', placeholder: '-- Pilih RW --'})

		const spinner = $('<div id="select-spinner" class="spinner spinner-right"></div>');
		const selectKelurahan = $('#kelurahan')
		const selectRW = $('#rw')
		
		selectRW.html('');
		selectRW.prop("disabled", true);

		// Get data from local storage form data
		var formData = localStorage.getItem("temporary_form_data");
		if (formData) {
			localStorage.removeItem("temporary_form_data");
			var formDataParse = JSON.parse(formData)
			
			var valKelurahanId = formDataParse.kelurahan_id;
		}

		setTimeout(async () => {
			if (valKelurahanId) {
				$('select[name=kelurahan_id]').val(valKelurahanId).trigger('change');
			}
		}, 100);

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
			}
		})
    });
</script>
@endsection