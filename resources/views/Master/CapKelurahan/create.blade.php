@extends('layouts.master')

@section('content')
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Tambah Cap Kelurahan
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<form id="headerForm" action="{{ route('Master.CapKelurahan.store') }}" enctype="multipart/form-data"
			method="POST">
			@csrf
			<div class="card-body">
				<div class="form-group">
					<div class="imageContainer">
						<img class="imagePicture" src="{{asset('images/NoPic.png')}}"></img>
					</div>
					<br>
					<label>Cap Kelurahan</label>
					<div class="custom-file">
						<input name="cap_kelurahan" type="file" class="custom-file-input" id="customFile" />
						<label class="custom-file-label" for="customFile">Choose file </label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Provinsi</label>
							<select class="form-control" name="province_id" id="province">
								{!! $resultProvince !!}
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Kota/Kabupaten</label>
							<select class="form-control" name="city_id" id="city">
								<option></option>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Kecamatan</label>
							<select class="form-control" name="subdistrict_id" id="subdistrict">
								<option></option>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Kelurahan</label>
							<select class="form-control" name="kelurahan_id" id="kelurahan">
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

{!! JsValidator::formRequest('App\Http\Requests\Master\CapKelurahanRequest','#headerForm') !!}
<script>
	const storeData = async () => {
		loading()

		const formData = new FormData(document.getElementById('headerForm'));

		const res = async () => {
			return await fetch("{{route('Master.CapKelurahan.store')}}",{
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
			localStorage.setItem('success','Berhasil Menambahkan Cap Kelurahan');
			return location.replace('{{route("Master.CapKelurahan.index")}}');
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
    const iField = document.querySelector('input[name="cap_kelurahan"]')

    if(iField){
        iField.addEventListener('change',(event)=> {
            const output = document.querySelector('.imagePicture');

            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = () => {
                URL.revokeObjectURL(output.src)
            }
        });
    }
	
    jQuery(document).ready(function() {
		$('select[name=province_id]').select2({ width: '100%', placeholder: '-- Pilih Provinsi --'})
		$('select[name=city_id]').select2({ width: '100%', placeholder: '-- Pilih Kabupaten --'})
		$('select[name=subdistrict_id]').select2({ width: '100%', placeholder: '-- Pilih Kecamatan --'})
		$('select[name=kelurahan_id]').select2({ width: '100%', placeholder: '-- Pilih Kelurahan --'})

		const spinner = $('<div id="select-spinner" class="spinner spinner-right"></div>');
		const selectProvince = $('#province')
		const selectCity = $('#city')
		const selectSubdistrict = $('#subdistrict')
		const selectKelurahan = $('#kelurahan')

		// Get data from local storage form data
		var formData = localStorage.getItem("temporary_form_data");
		if (formData) {
			localStorage.removeItem("temporary_form_data");
			var formDataParse = JSON.parse(formData)
			
			var valProvinceId = formDataParse.province_id;
			var valCityId = formDataParse.city_id;
			var valSubdistrictId = formDataParse.subdistrict_id;
		}

		setTimeout(async () => {
			if (valProvinceId) {
				$('select[name=province_id]').val(valProvinceId).trigger('change');
			}
		}, 100);

		selectCity.html('');
		selectCity.prop("disabled", true);

		selectSubdistrict.html('');
		selectSubdistrict.prop("disabled", true);

		selectKelurahan.html('');
		selectKelurahan.prop("disabled", true);

		$('body').on('change', 'select[name=province_id]', async function(){
			let province = $(this).val()
			if (province) {
				let citiesOption = '<option></option>';
				let url = @json(route('DetailAlamat.getCities'));
					url += `?province_id=${ encodeURIComponent(province) }`

				$(this).prop("disabled", true);
				selectCity.parent().append(spinner);
				selectCity.html('');
				selectCity.prop("disabled", true);

				selectSubdistrict.html('');
				selectSubdistrict.prop("disabled", true);

				selectKelurahan.html('');
				selectKelurahan.prop("disabled", true);

				const cities = await fetch(url).then(res => res.json()).catch(err => {
					selectCity.prop("disabled", false);
					spinner.remove()
					Swal.fire({
						title: 'Terjadi kesalahan dalam menghubungi server, silahkan coba lagi...',
						icon: 'warning'
					})
				});

				for(const city of cities) {
					citiesOption += `<option value="${city.city_id}">${city.type} ${city.city_name}</option>`;
				}

				selectCity.html(citiesOption);
				selectCity.select2({ 
					placeholder: '-- Pilih Kabupaten --', 
					width: '100%'
				});

				$(this).prop("disabled", false);
				selectCity.prop("disabled", false);
				spinner.remove();

				if(valCityId)
				{
					$('select[name=city_id]').val(valCityId).trigger('change');
				}
			}
		})

		$('body').on('change', 'select[name=city_id]', async function(){
			let city = $(this).val()
			if (city) {
				let subOption = '<option></option>';
				let url = @json(route('DetailAlamat.getSubdistricts'));
					url += `?city_id=${ encodeURIComponent(city) }`

				$(this).prop("disabled", true);
				selectSubdistrict.parent().append(spinner);
				selectSubdistrict.html('');
				selectSubdistrict.prop("disabled", true);
				
				selectKelurahan.html('');
				selectKelurahan.prop("disabled", true);
				
				const subdistricts = await fetch(url).then(res => res.json()).catch((err) => {
					selectSubdistrict.prop("disabled", false);
					spinner.remove()
					Swal.fire({
						title: 'Terjadi kesalahan dalam menghubungi server, silahkan coba lagi...',
						icon: 'warning'
					})
				});

				for(const sub of subdistricts) {
					subOption += `<option value="${sub.subdistrict_id}">${sub.subdistrict_name}</option>`;
				}

				selectSubdistrict.html(subOption);
				selectSubdistrict.select2({ 
					placeholder: '-- Pilih Kecamatan --', 
					width: '100%'
				});
				$(this).prop("disabled", false);
				selectSubdistrict.prop("disabled", false);
				spinner.remove();

				if(valSubdistrictId)
				{
					$('select[name=subdistrict_id]').val(valSubdistrictId).trigger('change');
				}
			}
		})

		$('body').on('change', 'select[name=subdistrict_id]', async function(){
			let subdistrict = $(this).val()
			if (subdistrict) {
				let subOption = '<option></option>';
				let url = @json(route('DetailAlamat.getKelurahan'));
					url += `?subdistrictID=${ encodeURIComponent(subdistrict) }`

				$(this).prop("disabled", true);
				selectKelurahan.parent().append(spinner);
				selectKelurahan.html('');
				selectKelurahan.prop("disabled", true);

				const fetchKelurahan = await fetch(url).then(res => res.json()).catch((err) => {
					selectKelurahan.prop("disabled", false);
					spinner.remove()
					Swal.fire({
						title: 'Terjadi kesalahan dalam menghubungi server, silahkan coba lagi...',
						icon: 'warning'
					})
				});

				for(const data of fetchKelurahan) {
					subOption += `<option value="${data.kelurahan_id}">${data.nama}</option>`;
				}

				selectKelurahan.html(subOption);
				selectKelurahan.select2({ 
					placeholder: '-- Pilih Kelurahan --', 
					width: '100%'
				});
				$(this).prop("disabled", false);
				selectKelurahan.prop("disabled", false);
				spinner.remove();
			}
		})
    });
</script>
@endsection