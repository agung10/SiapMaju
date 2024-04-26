@extends('layouts.master')

@section('content')
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Tambah Kegiatan
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<form id="headerForm" action="{{ route('Master.ListKegiatan.store') }}" enctype="multipart/form-data"
			method="POST">
			@csrf
			<div class="card-body">
				<h4>Data Kegiatan</h4>
				<hr>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Kategori Kegiatan <span class="text-danger">*</span></label>
							<select name="kat_kegiatan_id" class="form-control">
								{!! $result !!}
							</select>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>Nama Kegiatan <span class="text-danger">*</span></label>
							<input type="text" name="nama_kegiatan" class="form-control" />
						</div>
					</div>
				</div>

				<h4 class="mt-3">Data Detail Alamat</h4>
				<hr>
				<div class="row">
					{{-- <div class="col-md-4">
						<div class="form-group">
							<label>Provinsi<span class="text-danger">*</span></label>
							<select class="form-control" name="province_id" id="province">
								<option></option>
								@foreach($provinces as $province_id => $province)
								<option value="{{ $province_id }}"> {{ $province }} </option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Kota/Kabupaten<span class="text-danger">*</span></label>
							<select class="form-control" name="city_id" id="city">
								<option></option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Kecamatan<span class="text-danger">*</span></label>
							<select class="form-control" name="subdistrict_id" id="subdistrict">
								<option></option>
							</select>
						</div>
					</div> --}}
					<div class="col-md-6">
						<div class="form-group">
							<label>Kelurahan<span class="text-danger">*</span></label>
							<select class="form-control kelurahan" name="kelurahan_id" id="kelurahan">
								{!! $resultKelurahan !!}
							</select>
						</div>
					</div>
					@if ($isAdmin)
					<div class="col-md-3">
						<div class="form-group">
							<label>RW<span class="text-danger">*</span></label>
							<select class="form-control" name="rw_id" id="rw" disabled>
								<option></option>
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>RT<span class="text-danger">*</span></label>
							<select class="form-control" name="rt_id" id="rt" disabled>
								<option></option>
							</select>
						</div>
					</div>
					@else
					<div class="col-md-3">
						<div class="form-group">
							<label>RW<span class="text-danger">*</span></label>
							<select class="form-control" name="rw_id" id="rw">
								{!! $resultRW !!}
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>RT<span class="text-danger">*</span></label>
							<select class="form-control" name="rt_id" id="rt">
								{!! $resultRT !!}
							</select>
						</div>
					</div>
					@endif
				</div>
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>
</div>

{!! JsValidator::formRequest('App\Http\Requests\Kegiatan\KegiatanRequest','#headerForm') !!}
<script>
	// const selectRT = document.querySelector('.rt')
	// const selectRW = document.querySelector('.rw')

	// selectPlaceholder(selectRT, 'RT')
	// selectPlaceholder(selectRW, 'RW')

	// function selectPlaceholder(selector,name){
	// 	selector.childNodes[1].innerHTML = `Pilih ${name}`
	// 	selector.childNodes[1].setAttribute('disabled','')
	// }

	const storeData = async () => {

		const formData = new FormData(document.getElementById('headerForm'));

		const res = await fetch("{{route('Master.ListKegiatan.store')}}",{
						headers:{
							"X-CSRF-TOKEN":"{{ csrf_token() }}"
						},
						method:'post',
						body:formData
					});

		const {status,errors} = await res.json();
		
		if(status === 'success'){

			localStorage.setItem('success','Kegiatan Berhasil Ditambahkan');

			return location.replace('{{route("Master.ListKegiatan.index")}}');
		}else{

			const {kat_kegiatan_id,nama_kegiatan} = errors;

			document.querySelector('.err-kat_kegiatan_id').innerHTML = kat_kegiatan_id ? kat_kegiatan_id : '';
			document.querySelector('.err-nama_kegiatan').innerHTML = nama_kegiatan ? nama_kegiatan : '';
		}
	}

	document.querySelector('#headerForm').addEventListener('submit', (e) => {
		e.preventDefault();
		const form = e.currentTarget
		const valid = $(form).valid()
		if(!valid) return;

		storeData();
	})

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
            const selectCity = $('#city')
            const selectSubdistrict = $('#subdistrict')
            const selectKelurahan = $('#kelurahan')
            const selectRW = $('#rw')
            const selectRT = $('#rt')

            selectCity.html('');
            selectCity.prop("disabled", true);

            selectSubdistrict.html('');
            selectSubdistrict.prop("disabled", true);

            // selectKelurahan.html('');
            // selectKelurahan.prop("disabled", true);

            // selectRW.html('');
            // selectRW.prop("disabled", true);

            // selectRT.html('');
            // selectRT.prop("disabled", true);

			$('body').on('change', 'select[name=province_id]', async function(){
				let selectCity = $('select[name=city_id]')
				let selectSubdistrict = $('select[name=subdistrict_id]')
				let selectKelurahan = $('select[name=kelurahan_id]')
				let selectRW = $('select[name=rw_id]')
				let selectRT = $('select[name=rt_id]')

				let province = $(this).val()
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
			})

			$('body').on('change', 'select[name=city_id]', async function(){
				let selectSubdistrict = $('select[name=subdistrict_id]')
				let selectKelurahan = $('select[name=kelurahan_id]')
				let selectRW = $('select[name=rw_id]')
				let selectRT = $('select[name=rt_id]')

				let city = $(this).val()
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
			})

            $('body').on('change', 'select[name=subdistrict_id]', async function(){
				let selectKelurahan = $('select[name=kelurahan_id]')
				let selectRW = $('select[name=rw_id]')
				let selectRT = $('select[name=rt_id]')

				let subdistrict = $(this).val()
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
			})

            $('body').on('change', 'select[name=kelurahan_id]', async function(){
				let selectRW = $('select[name=rw_id]')
				let selectRT = $('select[name=rt_id]')

				let kelurahan = $(this).val()
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
			})

            $('body').on('change', 'select[name=rw_id]', async function(){
				let selectRT = $('select[name=rt_id]')

				let rw = $(this).val()
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