@extends('layouts.master')

@section('content')
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Edit Anggota Keluarga
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<form id="headerForm" action="{{ route('Master.AnggotaKeluarga.update', $data->anggota_keluarga_id) }}"
			enctype="multipart/form-data" method="POST">
			@csrf
			{{ method_field('PATCH') }}
			<div class="card-body">
				<h4>Data Anggota Keluarga & Akun</h4>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Keluarga<span class="text-danger">*</span></label>
							<select class="form-control" name="keluarga_id">
								{!! $resultKeluarga !!}
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Nama Anggota<span class="text-danger">*</span></label>
							<input type="text" name="nama" class="form-control" value="{{ $data->nama }}" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Jenis Kelamin<span class="text-danger">*</span></label>
							<select class="form-control" name="jenis_kelamin">
								<option selected disabled>Pilih Jenis Kelamin</option>
								<option value="L" {{$data->jenis_kelamin == 'L' ? 'selected' : ''}}>Laki-Laki</option>
								<option value="P" {{$data->jenis_kelamin == 'P' ? 'selected' : ''}}>Perempuan</option>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Tanggal Lahir<span class="text-danger">*</span></label>
							<input type="text" name="tgl_lahir"
								value="{{!empty($data->tgl_lahir) ? date('d-m-Y',strtotime($data->tgl_lahir))  : '-'}}"
								class="form-control date-input" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Hubungan Keluarga<span class="text-danger">*</span></label>
							<select class="form-control" name="hub_keluarga_id">
								{!! $resultHub !!}
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Mobile<span class="text-danger">*</span></label>
							<input type="number" name="mobile" class="form-control"
								value="{{ $data->mobile ? $data->mobile : '0' }}" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Email<span class="text-danger">*</span></label>
							<input type="email" name="email" class="form-control" value="{{ $data->email }}" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Password<span class="text-danger">*</span></label>
							<input type="password" name="password" class="form-control" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Agama</label>
							<select class="form-control" name="agama_id">
								{!! $resultAgama !!}
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Nama UMKM</label>
							<input type="text" name="nama_umkm" class="form-control" value="{{ $data->nama_umkm }}" />
						</div>
					</div>
				</div>

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
					<div class="col-md-12">
						<div class="form-group">
							<label>Alamat<span class="text-danger">*</span></label>
							<textarea type="text" name="alamat"
								class="form-control" id="alamat">{{ $anggotaGetAlamat['alamat'] }}</textarea>
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

{!! JsValidator::formRequest('App\Http\Requests\Master\AddAnggotaKeluargaRequest','#headerForm') !!}
<script>
	const updateData = async () => {

		const formData = new FormData(document.getElementById('headerForm'));

		KTApp.blockPage({
			overlayColor: '#000000',
			state: 'primary',
			message: 'Mohon Tunggu Sebentar'
		});
		
		const res = await fetch(`{{route('Master.AnggotaKeluarga.update', '')}}/{{\Request::segment(4)}}`,{
			headers:{"X-CSRF-TOKEN":"{{ csrf_token() }}"},
			method:'post',
			body:formData
		});

		const {status,errors} = await res.json();

		if(status === 'error_email'){
			document.querySelector('input[name="email"]').classList.replace('is-valid','is-invalid')
			KTApp.unblockPage()
			swal.fire('Maaf Alamat Email Sudah Digunakan !','','error');

			return;
		}
		
		if(status === 'success'){

			localStorage.setItem('success','Anggota Keluarga Berhasil Diupdate');

			return location.replace('{{route("Master.AnggotaKeluarga.index")}}');
		}
	}

	document.querySelector('#headerForm').addEventListener('submit',(e) => {
		e.preventDefault()
		
		const form =  e.currentTarget
		const valid = $(form).valid()

		if(!valid) return;
		updateData();
	})

	async function fetchDataKeluarga(id) {
		const keluarga_id = id

		const response = async () => {
			const url = `{{route('Master.AnggotaKeluarga.getDataKeluarga','')}}/${keluarga_id}`
		
			return await fetch(url,{
				headers:{
					'X-CSRF-TOKEN':'{{csrf_token()}}',
					'X-Requested-With':'XMLHttpRequest'
				},
					method:'post'
			})
			.then(response => response.json())
			.catch(() => {
				swal.fire('Maaf Terjadi Kesalahan','','error')
				.then(result => {
					if(result.isConfirmed) window.location.reload()
				})
			})
		}

		return await response()
	}

	var KTSelect2 = function() {
        // Functions
        return {
            init: function() {
                $('select[name=agama_id]').select2({ width: '100%', placeholder: '-- Pilih Agama --'})
                $('select[name=keluarga_id]').select2({ width: '100%', placeholder: '-- Pilih Keluarga --'})
				$('select[name=jenis_kelamin]').select2({ width: '100%', placeholder: '-- Pilih Jenis Kelamin --'})
				$('select[name=hub_keluarga_id]').select2({ width: '100%', placeholder: '-- Pilih Hubungan Keluarga --'})

				$('select[name=province_id]').select2({ width: '100%', placeholder: '-- Pilih Provinsi --'})
				$('select[name=city_id]').select2({ width: '100%', placeholder: '-- Pilih Kabupaten --'})
				$('select[name=subdistrict_id]').select2({ width: '100%', placeholder: '-- Pilih Kecamatan --'})
				$('select[name=kelurahan_id]').select2({ width: '100%', placeholder: '-- Pilih Kelurahan --'})
				$('select[name=rw_id]').select2({ width: '100%', placeholder: '-- Pilih RW --'})
				$('select[name=rt_id]').select2({ width: '100%', placeholder: '-- Pilih RT --'})


				var getDataAlamat
				var selectKeluargaID
				$('body').on('change', 'select[name=keluarga_id]', async function(){
					selectKeluargaID = this.value

					if(selectKeluargaID)
					{
						getDataAlamat = await fetchDataKeluarga(selectKeluargaID)

						const {province_id} = getDataAlamat.result;

						if (province_id == null) {
							swal.fire({
		                        title: 'Data Detail Alamat tidak ada/belum lengkap, silahkan perbarui data terlebih dahulu...',
		                        icon: 'warning',
		                    }).then((data) => {
								// var test = window.location = `{{ url('Master/Keluarga/AnggotaKeluarga/${selectKeluargaID}/edit') }}`;
								window.location.reload();
							})
							$('select[name=province_id]').val('').trigger('change.select2');
							$('select[name=province_id]').prop("disabled", true);

							$('select[name=city_id]').val('').trigger('change.select2');
							$('select[name=city_id]').prop("disabled", true);

							$('select[name=subdistrict_id]').val('').trigger('change.select2');
							$('select[name=subdistrict_id]').prop("disabled", true);

							$('select[name=kelurahan_id]').val('').trigger('change.select2');
							$('select[name=kelurahan_id]').prop("disabled", true);

							$('select[name=rw_id]').val('').trigger('change.select2');
							$('select[name=rw_id]').prop("disabled", true);

							$('select[name=rt_id]').val('').trigger('change.select2');
							$('select[name=rt_id]').prop("disabled", true);

							$('textarea[name=alamat]').val('');
							$('textarea[name=alamat]').prop("disabled", true);
						} else {
							$('select[name=province_id]').val(province_id).trigger('change');
						}
					}
				})

				let spinner = $('<div id="select-spinner" class="spinner spinner-right"></div>');
				let selectProvince = $('#province')
				let selectCity = $('#city')
				let selectSubdistrict = $('#subdistrict')
				let selectKelurahan = $('#kelurahan')
				let selectRW = $('#rw')
				let selectRT = $('#rt')
				let inputAlamat = $('#alamat')
				
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
						
						inputAlamat.html('');
						inputAlamat.prop("disabled", true);
	
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
	
						if(province && selectKeluargaID)
						{
							const {city_id} = getDataAlamat.result;
							$('select[name=city_id]').val(city_id).trigger('change');
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
	
						inputAlamat.html('');
						inputAlamat.prop("disabled", true);
	
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
	
						if(city && selectKeluargaID)
						{
							const {subdistrict_id} = getDataAlamat.result;
							
							$('select[name=subdistrict_id]').val(subdistrict_id).trigger('change');
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
	
						inputAlamat.html('');
						inputAlamat.prop("disabled", true);
	
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
	
						if(subdistrict && selectKeluargaID)
						{
							const {kelurahan_id} = getDataAlamat.result;
							
							$('select[name=kelurahan_id]').val(kelurahan_id).trigger('change');
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
	
						inputAlamat.html('');
						inputAlamat.prop("disabled", true);
	
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
	
						if(kelurahan && selectKeluargaID)
						{
							const {rw_id} = getDataAlamat.result;
							
							$('select[name=rw_id]').val(rw_id).trigger('change');
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
	
						inputAlamat.html('');
						inputAlamat.prop("disabled", true);
						
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
	
						if(rw && selectKeluargaID)
						{
							const {rt_id} = getDataAlamat.result;
							
							$('select[name=rt_id]').val(rt_id).trigger('change');
						}
					}
				})

				$('body').on('change', 'select[name=rt_id]', async function(){
					let rt = $(this).val()
					if (rt) {
						let textarea = '<textarea type="text" class="form-control"></textarea>';
	
						$(this).prop("disabled", false);
						inputAlamat.html('');
						inputAlamat.prop("disabled", false);
	
						if(rt && selectKeluargaID)
						{
							const {alamat} = getDataAlamat.result;
							
							$('textarea[name=alamat]').val(alamat);
						}
					}
				})
            }
        };
    }();

    // Initialization
    jQuery(document).ready(function() {
        KTSelect2.init();
    });
</script>
@endsection