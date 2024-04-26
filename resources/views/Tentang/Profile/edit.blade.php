@extends('layouts.master')

@section('content')
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Edit Profile
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<form id="headerForm" action="{{ route('Tentang.Profile.update', $data->profile_id) }}"
			enctype="multipart/form-data" method="POST">
			@csrf
			{{ method_field('PATCH') }}

			<div class="card-body">
				<h4>Data Profile</h4>
				<hr>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<div class="imageContainer">
								<img class="imagePicture"
									src="{{ ((!empty($data)) ? ((!empty($data->gambar_profile)) ? (asset('upload/profile/'.$data->gambar_profile)) : (asset('images/NoPic.png'))) : (asset('images/NoPic.png'))) }}"
									width="200" height="200" />
							</div>
							<label>Gambar Profile</label>
							<div class="custom-file">
								<input name="gambar_profile" type="file" class="custom-file-input" id="customFile" />
								<label class="custom-file-label" for="customFile">Choose file </label>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group mb-1">
							<label for="exampleTextarea">Isi Profile <span class="text-danger">*</span></label>
							<textarea name="isi_profile" class="form-control" id="exampleTextarea"
								rows="3">{{!empty($data->isi_profile) ? $data->isi_profile : '' }}</textarea>
						</div>
					</div>
				</div>

				<br>

				<h4 class="mt-3">Data Detail Alamat</h4>
				<hr>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label>Provinsi<span class="text-danger">*</span></label>
							<select class="form-control" name="province_id" id="province">
								{!! $resultProvince !!}
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Kota/Kabupaten<span class="text-danger">*</span></label>
							<select class="form-control" name="city_id" id="city">
								{!! $resultCity !!}
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Kecamatan<span class="text-danger">*</span></label>
							<select class="form-control" name="subdistrict_id" id="subdistrict">
								{!! $resultSubdistrict !!}
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Kelurahan<span class="text-danger">*</span></label>
							<select class="form-control" name="kelurahan_id" id="kelurahan">
								{{!! $resultKelurahan !!}}
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>RW<span class="text-danger">*</span></label>
							<select class="form-control" name="rw_id" id="rw">
								{{!! $resultRW !!}}
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>RT<span class="text-danger">*</span></label>
							<select class="form-control" name="rt_id" id="rt">
								{{!! $resultRT !!}}
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

{!! JsValidator::formRequest('App\Http\Requests\Tentang\ProfilRequest','#headerForm') !!}
<script>
	const editData = async () => {
		loading()
		
		const formData = new FormData(document.getElementById('headerForm'));

		const res = async () => {
			return await fetch(`{{route('Tentang.Profile.update', '')}}/{{\Request::segment(3)}}`,{
						headers:{"X-CSRF-TOKEN":"{{ csrf_token() }}"},
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

			localStorage.setItem('success','Profile Berhasil Diupdate');

			return location.replace('{{route("Tentang.Profile.index")}}');
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
    const gmbrField = document.querySelector('input[name="gambar_profile"]')

    if(gmbrField){

        gmbrField.addEventListener('change',(event)=> {
            const output = document.querySelector('.imagePicture');

            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = () => {
                URL.revokeObjectURL(output.src)
            }
        });
    }

    var KTSelect2 = function() {
        // Private functions
        var demos = function() {
			$('select[name=province_id]').select2({ width: '100%', placeholder: '-- Pilih Provinsi --'})
			$('select[name=city_id]').select2({ width: '100%', placeholder: '-- Pilih Kabupaten --'})
			$('select[name=subdistrict_id]').select2({ width: '100%', placeholder: '-- Pilih Kecamatan --'})
			$('select[name=kelurahan_id]').select2({ width: '100%', placeholder: '-- Pilih Kelurahan --'})
			$('select[name=rw_id]').select2({ width: '100%', placeholder: '-- Pilih RW --'})
			$('select[name=rt_id]').select2({ width: '100%', placeholder: '-- Pilih RT --'})

			const spinner = $('<div id="select-spinner" class="spinner spinner-right"></div>');
			const selectCity = $('#city')
			const selectSubdistrict = $('#subdistrict')
			const selectKelurahan = $('#kelurahan')
            const selectRW = $('#rw')
            const selectRT = $('#rt')

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