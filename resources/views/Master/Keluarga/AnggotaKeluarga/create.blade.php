@extends('layouts.master')

@section('content')
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Tambah Keluarga
					</h5>
				</div>
			</div>
		</div>
	</div>
	<div class="card card-custom gutter-b">
		<form id="headerForm" action="{{ route('Master.AnggotaKeluarga.store') }}" enctype="multipart/form-data"
			method="POST">
			@csrf
			<div class="card-body">
				<h4>Data Anggota Keluarga & Akun</h4>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Keluarga<span class="text-danger">*</span></label>
							<select class="form-control" name="keluarga_id" id="keluarga">
								{!! $resultKeluarga !!}
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Nama Anggota<span class="text-danger">*</span></label>
							<input type="text" name="nama" class="form-control" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Jenis Kelamin<span class="text-danger">*</span></label>
							<select class="form-control" name="jenis_kelamin">
								<option></option>
								<option value="L">Laki-Laki</option>
								<option value="P">Perempuan</option>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Tanggal Lahir</label>
							<input type="text" name="tgl_lahir"
								value="{{!empty($kepalaKeluarga->tgl_lahir) ? $kepalaKeluarga->tgl_lahir : ''}}"
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
							<input type="number" name="mobile" class="form-control" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Email<span class="text-danger">*</span></label>
							<input type="email" name="email" class="form-control" />
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
							<select class="form-control" name="agama_id" id="agama">
								<option></option>
								@foreach ($agama as $agama_id => $nama_agama)
									<option value="{{ $agama_id }}">{{ $nama_agama }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Apakah Memiliki UMKM ? </label>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="umkm" value="umkm_yes"
									id="umkm_yes">
								<label class="form-check-label">
									YA
								</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="umkm" value="umkm_no" id="umkm_no"
									checked>
								<label class="form-check-label">
									TIDAK
								</label>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group umkm" style="display: none;">
							<label>Nama UMKM </label>
							<input type="text" name="nama_umkm" class="form-control" />
						</div>
					</div>
				</div>

				<h4 class="mt-3">Data Detail Alamat</h4>
				<hr>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label>Provinsi</label>
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
							<label>Kota/Kabupaten</label>
							<select class="form-control" name="city_id" id="city">
								<option></option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
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
								<option selected></option>
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>RW</label>
							<select class="form-control" name="rw_id" id="rw">
								<option selected></option>
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>RT</label>
							<select class="form-control" name="rt_id" id="rt">
								<option selected></option>
							</select>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>Alamat Lengkap</label>
							<textarea type="text" name="alamat" class="form-control" placeholder="Masukkan alamat lengkap..." id="alamat"></textarea>
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
	$(document).ready(function(){
		$('input:checkbox').click(function() {
			$('input:checkbox').not(this).prop('checked', false);
		});
	});

	$('#umkm_yes').on('change', function(){
		$('.umkm').css('display', 'block')
	});

	$('#umkm_no').on('change', function(){
		$('.umkm').css('display', 'none')
	})

	const storeData = async () => {

		const formData = new FormData(document.getElementById('headerForm'));

		KTApp.blockPage({
			overlayColor: '#000000',
			state: 'primary',
			message: 'Mohon Tunggu Sebentar'
		});
		
		const res = await fetch("{{route('Master.AnggotaKeluarga.store')}}", {
			headers:{
				"X-CSRF-TOKEN":"{{ csrf_token() }}"
			},
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

		if(status === 'error_rt'){
			KTApp.unblockPage()
			swal.fire('Maaf saat ini anda belum bisa mendaftar. Karena Ketua RT yang sesuai dengan alamat anda belum terdaftar !','','error');

			return;
		}
		
		if(status === 'success'){

			localStorage.setItem('success','Anggota Keluarga Berhasil Ditambahkan');

			return location.replace('{{route("Master.AnggotaKeluarga.index")}}');
		}
	}

	document.querySelector('#headerForm').addEventListener('submit',(e) => {
		e.preventDefault()
		
		const form =  e.currentTarget
		const valid = $(form).valid()

		if(!valid) return;
		storeData();
	});

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
								window.location.reload();
							})
							$('select[name=province_id]').val('').trigger('change.select2');
							$('select[name=city_id]').val('').trigger('change.select2');
							$('select[name=subdistrict_id]').val('').trigger('change.select2');
							$('select[name=kelurahan_id]').val('').trigger('change.select2');
							$('select[name=rw_id]').val('').trigger('change.select2');
							$('select[name=rt_id]').val('').trigger('change.select2');
							$('textarea[name=alamat]').val('');
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
					await selectProvince.val(valProvinceId).trigger('change')
				}, 100);
				
				selectProvince.prop("disabled", true);

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

				inputAlamat.prop("disabled", true);
				
				
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
	
						else if(valCityId)
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
	
						else if(valSubdistrictId)
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
	
						else if(valKelurahanId)
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
	
						else if(valRWId)
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
	
						else if(valRTId)
						{
							$('select[name=rt_id]').val(valRTId).trigger('change');
						}
					}
				})

				$('body').on('change', 'select[name=rt_id]', async function(){
					let rt = $(this).val()
					if (rt) {
						let textarea = '<textarea type="text" class="form-control"></textarea>';
	
						$(this).prop("disabled", false);
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