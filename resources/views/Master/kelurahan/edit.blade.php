@extends('layouts.master')

@section('content')
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Edit Kelurahan
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<form id="dataForm" action="{{ route('Master.kelurahan.update', $data->kelurahan_id) }}" method="POST">
			@csrf
			{{ method_field('PATCH') }}
			<div class="card-body">
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
					<div class="col-md-8">
						<div class="form-group">
							<label>Nama Kelurahan<span class="text-danger">*</span></label>
							<input type="text" value="{{ $data->nama }}" name="nama" class="form-control" />
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Kode Pos<span class="text-danger">*</span></label>
							<input type="number" value="{{ $data->kode_pos }}" name="kode_pos" class="form-control"/>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>Alamat Lengkap<span class="text-danger">*</span></label>
							<textarea name="alamat" cols="30" rows="2" class="form-control">{{ $data->alamat }}</textarea>
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


{!! JsValidator::formRequest('App\Http\Requests\Master\KelurahanRequest','#dataForm') !!}
<script>
	$(document).ready(() => {
		$('#dataForm').on('submit',(e) => {
			e.preventDefault()

		    const valid = $('#dataForm').valid()
			if(!valid) return;
			
			editData();
		})
	})

	const editData = async () => {
		const formData = new FormData(document.getElementById('dataForm'));

		const res = await fetch(`{{route('Master.kelurahan.update', '')}}/{{\Request::segment(3)}}`,{
			headers:{
				"X-CSRF-TOKEN":"{{ csrf_token() }}",
				'X-Requested-With':'XMLHttpRequest'
			},
			method:'post',
			body:formData
		})
		.then(response => response.json())
		.catch(() => {
			Swal.fire('Maaf Terjadi Kesalahan','','error')
			window.location.reload()
		})
		
		const {status,errors} = await res
		
		if(status === 'success'){
			localStorage.setItem('success','Kelurahan Berhasil di Update');
			return location.replace('{{route("Master.kelurahan.index")}}');
		}
	}

	var KTSelect2 = function() {
        // Private functions
        var demos = function() {
			$('select[name=province_id]').select2({ width: '100%', placeholder: '-- Pilih Provinsi --'})
			$('select[name=city_id]').select2({ width: '100%', placeholder: '-- Pilih Kabupaten --'})
			$('select[name=subdistrict_id]').select2({ width: '100%', placeholder: '-- Pilih Kecamatan --'})

			const spinner = $('<div id="select-spinner" class="spinner spinner-right"></div>');
            const selectCity = $('#city')
            const selectSubdistrict = $('#subdistrict')

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