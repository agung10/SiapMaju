@extends('layouts.master')

@section('content')
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Tambah Blok Rumah
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<form id="headerForm" action="{{ route('Master.Blok.store') }}" enctype="multipart/form-data" method="POST">
			@csrf
			<div class="card-body">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label>Provinsi <span class="text-danger">*</span></label>
							<select class="form-control" name="province_id" id="province">
								{!! $resultProvince !!}
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Kota/Kabupaten <span class="text-danger">*</span></label>
							<select class="form-control" name="city_id" id="city">
								@if ($isRt) 
									{!! $resultCity !!}
								@else
									<option></option>
								@endif
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Kecamatan <span class="text-danger">*</span></label>
							<select class="form-control" name="subdistrict_id" id="subdistrict">
								@if ($isRt) 
									{!! $resultSubdistrict !!}
								@else
									<option></option>
								@endif
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Kelurahan <span class="text-danger">*</span></label>
							<select class="form-control" name="kelurahan_id" id="kelurahan">
								@if ($isRt) 
									{!! $resultKelurahan !!}
								@else
									<option></option>
								@endif
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>RW <span class="text-danger">*</span></label>
							<select class="form-control" name="rw_id" id="rw">
								@if ($isRt) 
									{!! $resultRW !!}
								@else
									<option></option>
								@endif
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>RT <span class="text-danger">*</span></label>
							<select class="form-control rt" name="rt_id" id="rt">
								@if ($isRt) 
									{!! $resultRT !!}
								@else
									<option></option>
								@endif
							</select>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>Nama Blok <span class="text-danger">*</span></label>
							<input type="text" name="nama_blok" class="form-control" id="blokID"/>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Longitude </label>
							<input type="text" name="long" class="form-control" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Latitude</label>
							<input type="text" name="lang" class="form-control" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>NOP </label>
							<input type="text" name="nop" class="form-control" />
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

{!! JsValidator::formRequest('App\Http\Requests\Master\BlokRequest','#headerForm') !!}
<script>
	const storeData = async () => {
		loading()

		const formData = new FormData(document.getElementById('headerForm'));

		const res = async () => {
			return await fetch("{{route('Master.Blok.store')}}",{
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
		
		if(status === 'error_blok'){
			document.querySelector('input[name="nama_blok"]').classList.replace('is-valid','is-invalid')
			KTApp.unblockPage()
			swal.fire('Maaf, Nama Blok di RT Yang Dipilih Sudah Ada !','','error');

			return;
		}

		if(status === 'success'){

			localStorage.setItem('success','Blok Berhasil Ditambahkan');

			return location.replace('{{route("Master.Blok.index")}}');
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

	var isRt = '{{ $isRt }}';
	
	var KTSelect2 = function() {
        // Private functions
        var demos = function() {
			// RajaOngkir
			$('select[name=province_id]').select2({ width: '100%', placeholder: '-- Pilih Provinsi --'})
			$('select[name=city_id]').select2({ width: '100%', placeholder: '-- Pilih Kabupaten --'})
			$('select[name=subdistrict_id]').select2({ width: '100%', placeholder: '-- Pilih Kecamatan --'})
			$('select[name=rt_id]').select2({ width: '100%', placeholder: '-- Pilih RT --'})
			$('select[name=rw_id]').select2({ width: '100%', placeholder: '-- Pilih RW --'})
			$('select[name=kelurahan_id]').select2({ width: '100%', placeholder: '-- Pilih Kelurahan --'})

			const spinner = $('<div id="select-spinner" class="spinner spinner-right"></div>');
            const selectProvince = $('#province')
            const selectCity = $('#city')
            const selectSubdistrict = $('#subdistrict')
            const selectKelurahan = $('#kelurahan')
            const selectRW = $('#rw')
            const selectRT = $('#rt')

			// Get data from local storage form data
			var formData = localStorage.getItem("temporary_form_data");
			if (formData) {
				localStorage.removeItem("temporary_form_data");
				var formDataParse = JSON.parse(formData)
				
				var valProvinceId = formDataParse.province_id;
				var valCityId = formDataParse.city_id;
				var valSubdistrictId = formDataParse.subdistrict_id;
				var valKelurahanId = formDataParse.kelurahan_id;
				var valRWId = formDataParse.rw_id;
				var valRTId = formDataParse.rt_id;
			}

			setTimeout(async () => {
				if (valProvinceId) {
					$('select[name=province_id]').val(valProvinceId).trigger('change');
				}
			}, 100);

            if (!isRt) {
				selectCity.html('');
				selectCity.prop("disabled", true);

				selectSubdistrict.html('');
				selectSubdistrict.prop("disabled", true);

				selectKelurahan.html('');
				selectKelurahan.prop("disabled", true);

				selectRW.html('');
				selectRW.prop("disabled", true);

				selectRT.html('');
				selectRT.prop("disabled", true);
			}

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
	
					selectRW.html('');
					selectRW.prop("disabled", true);
	
					selectRT.html('');
					selectRT.prop("disabled", true);
	
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
					
					selectRW.html('');
					selectRW.prop("disabled", true);
	
					selectRT.html('');
					selectRT.prop("disabled", true);
	
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
					
					selectRW.html('');
					selectRW.prop("disabled", true);
	
					selectRT.html('');
					selectRT.prop("disabled", true);
	
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
	
					if(valKelurahanId)
					{
						$('select[name=kelurahan_id]').val(valKelurahanId).trigger('change');
					}
				}
			})

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

		$('#blokID').keyup(function(){
			$(this).val($(this).val().toUpperCase());
		});
    });
</script>
@endsection