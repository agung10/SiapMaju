@extends('layouts.master')

@section('content')
@include('Surat.SuratPermohonan.css')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Buat Surat Permohonan
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
			<div class="card-body">
				<div class="form-surat-container">
					<form class="form-surat" enctype="multipart/form-data" novalidate>
						@csrf
						<div class="mt-3">
							<h4>Form Surat Permohonan</h4>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Nama Warga<span class="text-danger">*</span></label>
									@if(\Auth::user()->is_admin == true)
									<select class="form-control warga" name="anggota_keluarga_id">
										{!! $selectNamaWarga !!}
									</select>
									@else
									<select class="form-control warga" name="anggota_keluarga_id">
										{!! $selectNamaAnggota !!}
									</select>
									@endif
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Jenis Surat<span class="text-danger">*</span></label>
									<select class="form-control jenis_surat" name="jenis_surat_id">
										{!! $jenisSurat !!}
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6"></div>
							<div class="col-md-6 keperluan-container" style="display:none"> 
								<div class="form-group">
									<label>Keperluan</label>
									<input type="text" class="form-control keperluan" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Tempat Lahir<span class="text-danger">*</span></label>
									<input type="text" name="tempat_lahir" class="form-control" />
								</div>
							</div>
							<div class="col-md-6">
								<label>Tanggal Lahir<span class="text-danger">*</span></label>
								<input type="date" class="form-control tgl_lahirC" readonly>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Bangsa<span class="text-danger">*</span></label>
									<input type="text" name="bangsa" class="form-control" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Status Pernikahan<span class="text-danger">*</span></label>
									<select class="form-control status_pernikahan" name="status_pernikahan_id">
										{!! $pernikahan !!}
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Agama<span class="text-danger">*</span></label>
									<select class="form-control agama" name="agama_id">
										{!! $agama !!}
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Pekerjaan<span class="text-danger">*</span></label>
									<input type="text" name="pekerjaan" class="form-control" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>No KK<span class="text-danger">*</span></label>
									<input type="text" name="no_kk" class="form-control no_kk" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>No KTP<span class="text-danger">*</span></label>
									<input type="text" name="no_ktp" class="form-control" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Tanggal Permohonan<span class="text-danger">*</span></label>
									<input type="date" name="tgl_permohonan" class="form-control" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Keperluan Pembuatan<span class="text-danger">*</span></label>
									<textarea type="text" name="keperluan" class="form-control"></textarea>
								</div>
							</div>
						</div>
						
						<div id="lampiran-section">
							<div class="mt-3">
								<h4>Lampiran</h4>
							</div>
							<hr>
							<div id="lampiran-form" class="row"></div>
						</div>

						{{-- <div class="row lampiran-container">
							<div class="col-md-4">
								<div class="form-group">
									<label>Lampiran 1</label>
									<input class="lampiran" type="file" name="lampiran1" />
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Lampiran 2</label>
									<input class="lampiran" type="file" name="lampiran2" />
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Lampiran 3</label>
									<input class="lampiran" type="file" name="lampiran3" />
								</div>
							</div>
						</div> --}}
						
						<h4 class="mt-5">Data Detail Alamat</h4>
						<hr>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Provinsi</label>
									<select class="form-control provinceC" id="province">
										@if (!$isKepalaKeluarga || !$isWarga)
											<option></option>
											@foreach($provinces as $province_id => $province)
											<option value="{{ $province_id }}"> {{ $province }} </option>
											@endforeach
										@else
											{!! $resultProvince !!}
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Kota/Kabupaten</label>
									<select class="form-control cityC" id="city">
										@if (!$isKepalaKeluarga || !$isWarga)
											<option></option>
										@else
											{!! $resultCity !!}
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Kecamatan</label>
									<select class="form-control subdistrictC" id="subdistrict">
										@if (!$isKepalaKeluarga || !$isWarga) 
											<option></option>
										@else
											{!! $resultSubdistrict !!}
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Kelurahan</label>
									<select class="form-control kelurahanC" id="kelurahan">
										@if (!$isKepalaKeluarga || !$isWarga) 
											<option></option>
										@else
											{!! $resultKelurahan !!}
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>RW</label>
									<select class="form-control rwC" id="rw">
										@if (!$isKepalaKeluarga || !$isWarga) 
											<option></option>
										@else
											{!! $resultRW !!}
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>RT</label>
									<select class="form-control rtC" id="rt">
										@if (!$isKepalaKeluarga || !$isWarga) 
											<option></option>
										@else
											{!! $resultRT !!}
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Alamat Lengkap</label>
									@if (!$isKepalaKeluarga || !$isWarga)
									<textarea type="text" class="form-control alamatC" placeholder="Masukkan alamat lengkap..." id="alamat"></textarea>
									@else
									<textarea type="text" class="form-control alamatC" placeholder="Masukkan alamat lengkap..." id="alamat">{!! $data->alamat !!}</textarea>
									@endif
								</div>
							</div>
						</div>
						<div class="row btn-keluarga-container">
							<div class="col-md-6"></div>
							<div class="col-md-6">
								<button type="submit" class="btn btn-primary submit float-right">Simpan Surat Permohonan</button>
							</div>
						</div>
						<input type="hidden" name="rt_id">
						<input type="hidden" name="rw_id">
						<input type="hidden" name="kelurahan_id">
						<input type="hidden" name="subdistrict_id">
						<input type="hidden" name="city_id">
						<input type="hidden" name="province_id">
						<input type="hidden" name="nama_lengkap">
						<input type="hidden" name="alamat">
						<input type="hidden" name="tgl_lahir">
						<input type="hidden" name="hal">
						<input type="hidden" name="lampiran">

						<input type="hidden" name="approve_draft" value="">
					</form>
				</div>
			</div>
			<div class="card-footer">
				<button type="reset" onClick='goBack()' class="btn btn-secondary">Back</button>
			</div>
	</div>

{!! JsValidator::formRequest('App\Http\Requests\Surat\SuratRequest','.form-surat') !!}
@include('Surat.SuratPermohonan.createScript')

@if ($isKepalaKeluarga || $isWarga) 
<script>
	var v_rt = '<?php echo $data_rt; ?>';
	var v_rw = '<?php echo $data_rw; ?>';
	var v_kelurahan = '<?php echo $data_kelurahan; ?>';
	var v_subdistrict = '<?php echo $data_subdistrict; ?>';
	var v_city = '<?php echo $data_city; ?>';
	var v_province = '<?php echo $data_province; ?>';
	var v_alamat = '<?php echo $data_alamat; ?>';
	var v_tgl_lahir = '<?php echo $data_tgl_lahir; ?>';
	if (!v_rt || !v_rw || !v_kelurahan || !v_subdistrict || !v_city || !v_province || !v_alamat) {
		swal({
			title: "Informasi!",
			text: "Silahkan lengkapi/perbarui data anda terlebih dahulu!",
			type: "warning"
		}).then(function() {
			window.location = `{{ url('Master/Keluarga/AnggotaKeluarga') }}`;
		});
	}

	if (v_tgl_lahir) {
		$(".tgl_lahirC").val(v_tgl_lahir);
	}
</script>
@endif

<script>
	var isKepalaKeluarga = '<?php echo $isKepalaKeluarga; ?>';
	var isWarga = '<?php echo $isWarga; ?>';

	async function fetchDataKeluarga(id) {
		const anggota_keluarga_id = id

		const response = async () => {
			const url = `{{route('Surat.SuratPermohonan.getDataWarga','')}}/${anggota_keluarga_id}`
		
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

	async function fetchDataLampiran(id) {
		const lampiran_id = id

		const response = async () => {
			const url = `{{route('Surat.SuratPermohonan.getDataLampiran','')}}/${lampiran_id}`
		
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
				$('select[name=anggota_keluarga_id]').select2({ width: '100%', placeholder: '-- Pilih Nama warga --'})
				$('select[name=jenis_surat_id]').select2({ width: '100%', placeholder: '-- Pilih Jenis Surat --'})
				$('select[name=status_pernikahan_id]').select2({ width: '100%', placeholder: '-- Status Pernikahan --'})
				$('select[name=agama_id]').select2({ width: '100%', placeholder: '-- Pilih Agama --'})

				$('.provinceC').select2({ width: '100%', placeholder: '-- Pilih Provinsi --'})
				$('.cityC').select2({ width: '100%', placeholder: '-- Pilih Kabupaten --'})
				$('.subdistrictC').select2({ width: '100%', placeholder: '-- Pilih Kecamatan --'})
				$('.kelurahanC').select2({ width: '100%', placeholder: '-- Pilih Kelurahan --'})
				$('.rwC').select2({ width: '100%', placeholder: '-- Pilih RW --'})
				$('.rtC').select2({ width: '100%', placeholder: '-- Pilih RT --'})
				
				var getDataAlamat
				var selectKeluargaID
				$('body').on('change', 'select[name=anggota_keluarga_id]', async function(){
					selectKeluargaID = this.value

					if(selectKeluargaID)
					{
						getDataAlamat = await fetchDataKeluarga(selectKeluargaID)

						const {province_id, tgl_lahir} = getDataAlamat.data;

						if (tgl_lahir != null) {
							$(".tgl_lahirC").val(tgl_lahir);
						}

						if (province_id == null) {
							swal.fire({
		                        title: 'Data Detail Alamat tidak ada/belum lengkap, silahkan perbarui data terlebih dahulu...',
		                        icon: 'warning',
		                    }).then((data) => {
								window.location.reload();
							})
							$('.provinceC').val('').trigger('change.select2');
							$('.cityC').val('').trigger('change.select2');
							$('.subdistrictC').val('').trigger('change.select2');
							$('.kelurahanC').val('').trigger('change.select2');
							$('.rwC').val('').trigger('change.select2');
							$('.rtC').val('').trigger('change.select2');
							$('.alamatC').val('');
						} else {
							$('.provinceC').val(province_id).trigger('change');
						}

						$('input[name=nama_lengkap]').val(getDataAlamat.data.nama)
						$('input[name=tgl_lahir]').val(getDataAlamat.data.tgl_lahir)
						$('input[name=rt_id]').val(getDataAlamat.data.rt_id)
						$('input[name=rw_id]').val(getDataAlamat.data.rw_id)
						$('input[name=kelurahan_id]').val(getDataAlamat.data.kelurahan_id)
						$('input[name=subdistrict_id]').val(getDataAlamat.data.subdistrict_id)
						$('input[name=city_id]').val(getDataAlamat.data.city_id)
						$('input[name=province_id]').val(getDataAlamat.data.province_id)
						$('input[name=alamat]').val(getDataAlamat.data.alamat)
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
				
				if (isKepalaKeluarga || isWarga) {
					setTimeout(async () => {
						await selectProvince.val(v_province).trigger('change')
					}, 100);
				}

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
				
				
				$('body').on('change', '.provinceC', async function(){
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
	
						inputAlamat.val('');
						inputAlamat.prop("disabled", true);
	
						const cities = await fetch(url).then(res => res.json()).catch(err => {
							// selectCity.prop("disabled", false);
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
	
						// $(this).prop("disabled", false);
						// selectCity.prop("disabled", false);
						spinner.remove();
	
						if(province && selectKeluargaID)
						{
							const {city_id} = getDataAlamat.data;
							$('.cityC').val(city_id).trigger('change');
						}
	
						else if (v_city) {
							selectCity.val(v_city).trigger('change')
						}
					}
				})

				$('body').on('change', '.cityC', async function(){
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
	
						inputAlamat.val('');
						inputAlamat.prop("disabled", true);
	
						const subdistricts = await fetch(url).then(res => res.json()).catch((err) => {
							// selectSubdistrict.prop("disabled", false);
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
						// $(this).prop("disabled", false);
						// selectSubdistrict.prop("disabled", false);
						spinner.remove();
	
						if(city && selectKeluargaID)
						{
							const {subdistrict_id} = getDataAlamat.data;
							
							$('.subdistrictC').val(subdistrict_id).trigger('change');
						}
	
						else if (v_subdistrict) {
							selectSubdistrict.val(v_subdistrict).trigger('change')
						}
					}
				})

				$('body').on('change', '.subdistrictC', async function(){
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
	
						inputAlamat.val('');
						inputAlamat.prop("disabled", true);
	
						const fetchKelurahan = await fetch(url).then(res => res.json()).catch((err) => {
							// selectKelurahan.prop("disabled", false);
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
						// $(this).prop("disabled", false);
						// selectKelurahan.prop("disabled", false);
						spinner.remove();
	
						if(subdistrict && selectKeluargaID)
						{
							const {kelurahan_id} = getDataAlamat.data;
							
							$('.kelurahanC').val(kelurahan_id).trigger('change');
						}
	
						else if (v_kelurahan) {
							selectKelurahan.val(v_kelurahan).trigger('change')
						}
					}
				})

				$('body').on('change', '.kelurahanC', async function(){
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
	
						inputAlamat.val('');
						inputAlamat.prop("disabled", true);
	
						const fetchRW = await fetch(url).then(res => res.json()).catch((err) => {
							// selectRW.prop("disabled", false);
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
						// $(this).prop("disabled", false);
						// selectRW.prop("disabled", false);
						spinner.remove();
	
						if(kelurahan && selectKeluargaID)
						{
							const {rw_id} = getDataAlamat.data;
							
							$('.rwC').val(rw_id).trigger('change');
						}
	
						else if (v_rw) {
							selectRW.val(v_rw).trigger('change')
						}
					}
				})

				$('body').on('change', '.rwC', async function(){
					let rw = $(this).val()
					if (rw) {
						let subOption = '<option></option>';
						let url = @json(route('DetailAlamat.getRT'));
							url += `?rwID=${ encodeURIComponent(rw) }`
	
						$(this).prop("disabled", true);
						selectRT.parent().append(spinner);
						selectRT.html('');
						selectRT.prop("disabled", true);
	
						inputAlamat.val('');
						inputAlamat.prop("disabled", true);
						
						const fetchRT = await fetch(url).then(res => res.json()).catch((err) => {
							// selectRT.prop("disabled", false);
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
						// $(this).prop("disabled", false);
						// selectRT.prop("disabled", false);
						spinner.remove();
	
						if(rw && selectKeluargaID)
						{
							const {rt_id} = getDataAlamat.data;
							
							$('.rtC').val(rt_id).trigger('change');
						}
	
						else if (v_rt) {
							selectRT.val(v_rt).trigger('change')
						}
					}
				})

				$('body').on('change', '.rtC', async function(){
					let rt = $(this).val()
					if (rt) {
						let textarea = '<textarea type="text" class="form-control"></textarea>';
	
						// $(this).prop("disabled", false);
						inputAlamat.val('');
						// inputAlamat.prop("disabled", false);
	
						if(rt && selectKeluargaID)
						{
							const {alamat} = getDataAlamat.data;
							
							$('.alamatC').val(alamat);
						}
	
						else if (v_alamat) {
							inputAlamat.val(v_alamat)
						}
					}
				})
            }
        };
    }();

    // Initialization
    jQuery(document).ready(function() {
        KTSelect2.init();

		$('#lampiran-section').hide(300)
		$('body').on('change', 'select[name=jenis_surat_id]', async function(e){
			const jenisSurat = e.currentTarget.value
			const halSurat = getHalSurat(e);
			const inputHalSurat = document.querySelector('input[name=hal]')
			const keperluan = document.querySelector('.keperluan-container')
			inputHalSurat.value = halSurat

			if(jenisSurat == 15){
				$(keperluan).fadeIn('slow')
			}else{
				$(keperluan).fadeOut('slow')
			}

			selectJenisSuratID = this.value
			if(selectJenisSuratID){
				getDataLampiran = await fetchDataLampiran(selectJenisSuratID)
				const data = getDataLampiran.data;
                
                $('#lampiran-section').hide(300)
                $('#lampiran-form').html("")

				if (data && data.length > 0) {
                    for (let i = 0; i < data.length; i++) {
                        $('#lampiran-section').show(300)
                        var template_lampiran = `<div class="col-md-12">
                            <label>${data[i].nama_lampiran}</label>`
                            if (data[i].kategori == 1) {
                                template_lampiran += `<span class="pl-2 font-weight-bold text-danger">(Wajib Diisi)</span>`
                            }
                            else {
                                template_lampiran += `<span class="pl-2 font-weight-bold" style="color: #3F4254;">(Tidak Wajib Diisi)</span>`
                            }
                        template_lampiran += `</div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div id="custom-file-${i}" class="custom-file">
                                            <input id="custom-file-input-${i}" type="file" class="custom-file-input upload-lampiran" name="upload_lampiran[]" ${data[i].kategori == 1 ? 'required' : ''} multiple>
                                            <label class="custom-file-label">Masukkan File...</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <span class="font-weight-bold btn btn-secondary btn-sm text-justify mb-5 w-100" style="cursor: default; margin-top:-1.3em;">
                                <i class="fas fa-info-circle"></i>
                                ${data[i].keterangan}
                            </span>
                            <input type="hidden" name="lampiran_id[]" value="${data[i].lampiran_id}">
                        </div>`
                        $('#lampiran-form').append(template_lampiran)
                    }
				}
			}
            else {
                $('#lampiran-section').hide(300)
                $('#lampiran-form').html("")
            }
			$('.custom-file-input').on('change', function () {
				var files = this.files;
				$('#'+$(this).parent().attr('id')+' .custom-file-label').empty();
				for (var i = 0, l = files.length; i < l; i++) {
					$('#'+$(this).parent().attr('id')+' .custom-file-label').append(files[i].name + '\n');
				}
			});	
		})

		function getHalSurat(e){
			const select = e.currentTarget
			const selectedText = select.options[select.selectedIndex].text
			return selectedText
		}
    });
</script>
@endsection