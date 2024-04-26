@include('Master.Keluarga.ListKeluarga.script')
<script>
	const storeData = async () => {
		loading();

		const formKeluarga = document.querySelector('.form-keluarga')
		const formData = new FormData(formKeluarga)

		const res = await fetch("{{route('Master.ListKeluarga.store')}}",{
						headers:{
							"X-CSRF-TOKEN":"{{ csrf_token() }}",
							'X-Requested-With':'XMLHttpRequest'
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

		const {status,no_telp,alamat,email,keluarga_id,rt_id,rw_id,kelurahan_id,subdistrict_id,city_id,province_id} = res;

		if(status === 'error_email'){
			document.querySelector('input[name="email"]').classList.replace('is-valid','is-invalid')
			KTApp.unblockPage()
			swal.fire('Maaf Alamat Email Sudah Digunakan !','','error');
		}
		
		if(status === 'success'){
			document.querySelector('.anggota-alamat').value = alamat
			document.querySelector('.anggota-email').value = email
			document.querySelector('.keluarga-id').value = keluarga_id
			document.querySelector('.anggota-rt_id').value = rt_id
			document.querySelector('.anggota-rw_id').value = rw_id
			document.querySelector('.anggota-kelurahan_id').value = kelurahan_id
			document.querySelector('.anggota-subdistrict_id').value = subdistrict_id
			document.querySelector('.anggota-city_id').value = city_id
			document.querySelector('.anggota-province_id').value = province_id

			toggleInput(formKeluarga,'disabled')
			// toggleButton(formKeluarga,'Edit')
			$('.createButton').hide();

			KTApp.unblockPage()
			$('.form-anggota-container').fadeIn('slow')
			
			document.querySelector('.anggota-mobile').value = no_telp;
		}
	}

	const updateKeluargaHandler = async (keluarga_id) => {
		// function updateKeluarga & toggle from ListKeluarga/script.blade.php
		const formKeluarga = document.querySelector('.form-keluarga')

		const {status,no_telp,alamat,email,rt_id,rw_id,kelurahan_id,subdistrict_id,city_id,province_id} = await updateKeluarga(formKeluarga,keluarga_id)

		if(status === 'error_email'){
			document.querySelector('input[name="email"]').classList.replace('is-valid','is-invalid')
			KTApp.unblockPage()
			swal.fire('Maaf Alamat Email Sudah Digunakan !','','error');

			return;
		}

		if(status !== 'success') return;

		document.querySelector('.anggota-mobile').value = no_telp
		document.querySelector('.anggota-alamat').value = alamat
		document.querySelector('.anggota-email').value = email
		document.querySelector('.anggota-rt_id').value = rt_id
		document.querySelector('.anggota-rw_id').value = rw_id
		document.querySelector('.anggota-kelurahan_id').value = kelurahan_id
		document.querySelector('.anggota-subdistrict_id').value = subdistrict_id
		document.querySelector('.anggota-city_id').value = city_id
		document.querySelector('.anggota-province_id').value = province_id

		toggleInput(formKeluarga,'disabled')
		toggleButton(formKeluarga,'Edit')
		KTApp.unblockPage() 
	}

	document.querySelector('.form-keluarga').addEventListener('submit', (e) => {
		e.preventDefault();
		const valid = $('.form-keluarga').valid()
		const keluarga_id = document.querySelector('.keluarga-id').value

		if(!valid) return;

		if(keluarga_id == ''){
			storeData()
		}else{
			updateKeluargaHandler(keluarga_id)
		}
	})

	document.querySelector('#anggota-form').addEventListener('submit',(e) => {
		e.preventDefault()
		const valid = $('#anggota-form').valid()
		if(!valid) return;

		// function storeAnggota from ListKeluarga/script.blade.php
		storeAnggota()
	})

	const btnEditHandler = () => {
		const formKeluarga = document.querySelector('.form-keluarga')

		toggleInput(formKeluarga,'enabled')
		toggleButton(formKeluarga,'Submit')
	}

	function loading(){
		KTApp.blockPage({
			overlayColor: '#000000',
			state: 'primary',
			message: 'Mohon Tunggu Sebentar'
 		});
	}

	function toggleInput(form,action){

		const isDisabled = action == 'disabled' ? true : false

		form.querySelectorAll('input,select,textarea').forEach(node => {
			node.disabled = isDisabled
		})
	}

	var isRt = '{{ $isRt }}';

	jQuery(document).ready(function() {
		$('.provinceNewData').select2({ width: '100%', placeholder: '-- Pilih Provinsi --'})
		$('.cityNewData').select2({ width: '100%', placeholder: '-- Pilih Kabupaten --'})
		$('.subdistrictNewData').select2({ width: '100%', placeholder: '-- Pilih Kecamatan --'})
		$('.kelurahanNewData').select2({ width: '100%', placeholder: '-- Pilih Kelurahan --'})
		$('.rwNewData').select2({ width: '100%', placeholder: '-- Pilih RW --'})
		$('.rtNewData').select2({ width: '100%', placeholder: '-- Pilih RT --'})
		$('.blokNewData').select2({ width: '100%', placeholder: '-- Pilih Blok --'})

		$('select[name=jenis_kelamin]').select2({ width: '100%', placeholder: '-- Pilih Jenis Kelamin --'})
		$('select[name=status_domisili]').select2({ width: '100%', placeholder: '-- Pilih Status Domisili --'})

		const spinner = $('<div id="select-spinner" class="spinner spinner-right"></div>');
		const selectProvince = $('#provinceNewData')
		const selectCity = $('#cityNewData')
		const selectSubdistrict = $('#subdistrictNewData')
		const selectKelurahan = $('#kelurahanNewData')
		const selectRW = $('#rwNewData')
		const selectRT = $('#rtNewData')
		const selectBlok = $('#blokNewData')

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
			var valBlokId = formDataParse.blok_id;
		}

		setTimeout(async () => {
			if (valProvinceId) {
				$('.provinceNewData').val(valProvinceId).trigger('change');
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

			selectBlok.html('');
			selectBlok.prop("disabled", true);
		}

		$('body').on('change', '.provinceNewData', async function(){
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

				selectBlok.html('');
				selectBlok.prop("disabled", true);

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
					$('.cityNewData').val(valCityId).trigger('change');
				}
			}
		})

		$('body').on('change', '.cityNewData', async function(){
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

				selectBlok.html('');
				selectBlok.prop("disabled", true);

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
					$('.subdistrictNewData').val(valSubdistrictId).trigger('change');
				}
			}
		})

		$('body').on('change', '.subdistrictNewData', async function(){
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

				selectBlok.html('');
				selectBlok.prop("disabled", true);

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
					$('.kelurahanNewData').val(valKelurahanId).trigger('change');
				}
			}
		})

		$('body').on('change', '.kelurahanNewData', async function(){
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

				selectBlok.html('');
				selectBlok.prop("disabled", true);

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
					$('.rwNewData').val(valRWId).trigger('change');
				}
			}
		})

		$('body').on('change', '.rwNewData', async function(){
			let rw = $(this).val()
			if (rw) {
				let subOption = '<option></option>';
				let url = @json(route('DetailAlamat.getRT'));
					url += `?rwID=${ encodeURIComponent(rw) }`

				$(this).prop("disabled", true);
				selectRT.parent().append(spinner);
				selectRT.html('');
				selectRT.prop("disabled", true);

				selectBlok.html('');
				selectBlok.prop("disabled", true);
				
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
					$('.rtNewData').val(valRTId).trigger('change');
				}
			}
		})

		$('body').on('change', '.rtNewData', async function(){
			let selectBlok = $('.blokNewData')

			let rt = $(this).val()
			if (rt) {
				let subOption = '<option></option>';
				let url = @json(route('DetailAlamat.getBlok'));
					url += `?rtID=${ encodeURIComponent(rt) }`

				$(this).prop("disabled", true);
				selectBlok.parent().append(spinner);
				selectBlok.html('');
				selectBlok.prop("disabled", true);

				const fetchBlok = await fetch(url).then(res => res.json()).catch((err) => {
					selectBlok.prop("disabled", false);
					spinner.remove()
					Swal.fire({
						title: 'Terjadi kesalahan dalam menghubungi server, silahkan coba lagi...',
						icon: 'warning'
					})
				});

				for(const data of fetchBlok) {
					subOption += `<option value="${data.blok_id}">${data.nama_blok}</option>`;
				}

				selectBlok.html(subOption);
				selectBlok.select2({ 
					placeholder: '-- Pilih Blok --', 
					width: '100%'
				});
				$(this).prop("disabled", false);
				selectBlok.prop("disabled", false);
				spinner.remove();

				if(valBlokId)
				{
					$('.blokNewData').val(valBlokId).trigger('change');
				}
			}
		})
	
		$('select[name=status_domisili]').change(function() {
			var selected = $(this).children("option:selected").val();

			if (selected == 1) {
				var alamat_domisili = $('textarea[name=alamat]').val();
				$('.alamat-ktp').val(alamat_domisili);
				$('.alamat-ktp').attr("readonly", true);
			} else {
				$('.alamat-ktp').val('');
				$('.alamat-ktp').removeAttr("readonly");
			}
		});

		$(".alamat-ktp").change(function () {
			var alamat_ktp = $(".alamat-ktp").val();
			$('.alamat-ktp').val(alamat_ktp);
		});
	});
</script>

<script>
	const storeDataLama = async () => {
		loading();

		const formData = new FormData(document.querySelector('.form-keluarga-lama'));

		const res = await fetch("{{route('Master.ListKeluarga.store')}}", {
				headers: {
					"X-CSRF-TOKEN": "{{ csrf_token() }}",
					'X-Requested-With': 'XMLHttpRequest'
				},
				method: 'post',
				body: formData
			})
			.then(response => response.json())
			.catch(() => {
				KTApp.unblockPage()
				swal.fire('Maaf Terjadi Kesalahan', '', 'error')
					.then(result => {
						if (result.isConfirmed) window.location.reload()
					})
			})

		const {status} = await res;
			
		if (status === 'success') {
			localStorage.setItem('success', 'Keluarga Berhasil Ditambahkan');
			return location.replace('{{route("Master.ListKeluarga.index")}}');
		}
	}

	document.querySelector('.form-keluarga-lama').addEventListener('submit', (e) => {
		e.preventDefault()

		const form = e.currentTarget
		const valid = $(form).valid()
		
		if (!valid) return;
		storeDataLama();
	});

	async function fetchDataWarga(id) {
		const warga_id = id

		const response = async () => {
			const url = `{{route('Master.ListKeluarga.getDataWarga','')}}/${warga_id}`
			return await fetch(url, {
				headers: {
					'X-CSRF-TOKEN': '{{csrf_token()}}',
					'X-Requested-With': 'XMLHttpRequest'
				},
				method: 'post'
			})
			.then(response => response.json())
			.catch(() => {
				swal.fire('Maaf Terjadi Kesalahan', '', 'error')
					.then(result => {
						if (result.isConfirmed) window.location.reload()
					})
			})
		}

		return await response()
	}
	
	jQuery(document).ready(function() {
		$('select[name=warga_id]').select2({width: '100%',placeholder: '-- Pilih Warga --'})
		$('select[name=hub_keluarga_id]').select2({width: '100%',placeholder: '-- Pilih Hubungan Keluarga --'})

		$('.provinceOldData').select2({width: '100%',placeholder: '-- Pilih Provinsi --'})
		$('.cityOldData').select2({width: '100%',placeholder: '-- Pilih Kabupaten --'})
		$('.subdistrictOldData').select2({width: '100%',placeholder: '-- Pilih Kecamatan --'})
		$('.kelurahanOldData').select2({width: '100%',placeholder: '-- Pilih Kelurahan --'})
		$('.rwOldData').select2({width: '100%',placeholder: '-- Pilih RW --'})
		$('.rtOldData').select2({width: '100%',placeholder: '-- Pilih RT --'})
		$('.blokOldData').select2({width: '100%',placeholder: '-- Pilih Blok --'})

		let spinner = $('<div id="select-spinner" class="spinner spinner-right"></div>');
		let sProvinceOldData = $('#provinceOldData')
		let sCityOldData = $('#cityOldData')
		let sSubdistrictOldData = $('#subdistrictOldData')
		let sKelurahanOldData = $('#kelurahanOldData')
		let sRWOldData = $('#rwOldData')
		let sRTOldData = $('#rtOldData')
		let sBlokOldData = $('#blokOldData')

		var getDataWarga
		var selectWargaID
		$('body').on('change', 'select[name=warga_id]', async function() {
			selectWargaID = this.value
			
			if (selectWargaID) {
				getDataWarga = await fetchDataWarga(selectWargaID)
				
				const {province_id} = getDataWarga.result;
				
				if (province_id == null) {
					swal.fire({
						title: 'Data Detail Alamat tidak ada/belum lengkap, silahkan perbarui data terlebih dahulu...',
						icon: 'warning',
					}).then((data) => {
						window.location.reload();
					})
				} else {
					$('.provinceOldData').val(province_id).trigger('change')
				}

				$('input[name=anggota_keluarga_id]').val(getDataWarga.result.anggota_keluarga_id)
				$('.anggota-email').val(getDataWarga.result.email) 
				$('.emailOldData').val(getDataWarga.result.email)
				$('input[name=jenis_kelamin]').val(getDataWarga.result.jenis_kelamin)
				$('input[name=tgl_lahir]').val(getDataWarga.result.tgl_lahir)
				
				$('.telpOldData').val(getDataWarga.result.mobile)
				$('.alamatOldData').val(getDataWarga.result.alamat)
				$('select[name=rt_id]').val(getDataWarga.result.rt_id)
				$('select[name=rw_id]').val(getDataWarga.result.rw_id)
				$('select[name=kelurahan_id]').val(getDataWarga.result.kelurahan_id)
				$('select[name=subdistrict_id]').val(getDataWarga.result.subdistrict_id)
				$('select[name=city_id]').val(getDataWarga.result.city_id)
				$('select[name=province_id]').val(getDataWarga.result.province_id)
			}
		})

		$('body').on('change', '.provinceOldData', async function() {
			let province = $(this).val()
			if (province) {
				let citiesOption = '<option></option>';
				let url = @json(route('DetailAlamat.getCities'));
				url += `?province_id=${ encodeURIComponent(province) }`

				$(this).prop("disabled", true);
				sCityOldData.parent().append(spinner);
				sCityOldData.html('');
				sCityOldData.prop("disabled", true);

				sSubdistrictOldData.html('');
				sSubdistrictOldData.prop("disabled", true);

				sKelurahanOldData.html('');
				sKelurahanOldData.prop("disabled", true);

				sRWOldData.html('');
				sRWOldData.prop("disabled", true);

				sRTOldData.html('');
				sRTOldData.prop("disabled", true);

				sBlokOldData.html('');
				sBlokOldData.prop("disabled", true);

				const cities = await fetch(url).then(res => res.json()).catch(err => {
					sCityOldData.prop("disabled", true);
					spinner.remove()
					Swal.fire({
						title: 'Terjadi kesalahan dalam menghubungi server, silahkan coba lagi...',
						icon: 'warning'
					})
				});

				for (const city of cities) {
					citiesOption += `<option value="${city.city_id}">${city.type} ${city.city_name}</option>`;
				}

				sCityOldData.html(citiesOption);
				sCityOldData.select2({
					placeholder: '-- Pilih Kabupaten --',
					width: '100%'
				});

				$(this).prop("disabled", false);
				sCityOldData.prop("disabled", false);
				spinner.remove();

				if (province && selectWargaID) {
					const {city_id} = getDataWarga.result;
					$('.cityOldData').val(city_id).trigger('change');
				}
			}
		})

		$('body').on('change', '.cityOldData', async function(){
			let city = $(this).val()
			if (city) {
				let subOption = '<option></option>';
				let url = @json(route('DetailAlamat.getSubdistricts'));
					url += `?city_id=${ encodeURIComponent(city) }`

				$(this).prop("disabled", true);
				sSubdistrictOldData.parent().append(spinner);
				sSubdistrictOldData.html('');
				sSubdistrictOldData.prop("disabled", true);
				
				sKelurahanOldData.html('');
				sKelurahanOldData.prop("disabled", true);
				
				sRWOldData.html('');
				sRWOldData.prop("disabled", true);

				sRTOldData.html('');
				sRTOldData.prop("disabled", true);

				sBlokOldData.html('');
				sBlokOldData.prop("disabled", true);

				const subdistricts = await fetch(url).then(res => res.json()).catch((err) => {
					sSubdistrictOldData.prop("disabled", true);
					spinner.remove()
					Swal.fire({
						title: 'Terjadi kesalahan dalam menghubungi server, silahkan coba lagi...',
						icon: 'warning'
					})
				});

				for(const sub of subdistricts) {
					subOption += `<option value="${sub.subdistrict_id}">${sub.subdistrict_name}</option>`;
				}

				sSubdistrictOldData.html(subOption);
				sSubdistrictOldData.select2({ 
					placeholder: '-- Pilih Kecamatan --', 
					width: '100%'
				});
				$(this).prop("disabled", false);
				sSubdistrictOldData.prop("disabled", false);
				spinner.remove();

				if(city && selectWargaID)
				{
					const {subdistrict_id} = getDataWarga.result;
					
					$('.subdistrictOldData').val(subdistrict_id).trigger('change');
				}
			}
		})

		$('body').on('change', '.subdistrictOldData', async function(){
			let subdistrict = $(this).val()
			if (subdistrict) {
				let subOption = '<option></option>';
				let url = @json(route('DetailAlamat.getKelurahan'));
					url += `?subdistrictID=${ encodeURIComponent(subdistrict) }`

				$(this).prop("disabled", true);
				sKelurahanOldData.parent().append(spinner);
				sKelurahanOldData.html('');
				sKelurahanOldData.prop("disabled", true);
				
				sRWOldData.html('');
				sRWOldData.prop("disabled", true);

				sRTOldData.html('');
				sRTOldData.prop("disabled", true);

				sBlokOldData.html('');
				sBlokOldData.prop("disabled", true);

				const fetchKelurahan = await fetch(url).then(res => res.json()).catch((err) => {
					sKelurahanOldData.prop("disabled", true);
					spinner.remove()
					Swal.fire({
						title: 'Terjadi kesalahan dalam menghubungi server, silahkan coba lagi...',
						icon: 'warning'
					})
				});

				for(const data of fetchKelurahan) {
					subOption += `<option value="${data.kelurahan_id}">${data.nama}</option>`;
				}

				sKelurahanOldData.html(subOption);
				sKelurahanOldData.select2({ 
					placeholder: '-- Pilih Kelurahan --', 
					width: '100%'
				});
				$(this).prop("disabled", false);
				sKelurahanOldData.prop("disabled", false);
				spinner.remove();

				if(subdistrict && selectWargaID)
				{
					const {kelurahan_id} = getDataWarga.result;
					
					$('.kelurahanOldData').val(kelurahan_id).trigger('change');
				}
			}
		})

		$('body').on('change', '.kelurahanOldData', async function(){
			let kelurahan = $(this).val()
			if (kelurahan) {
				let subOption = '<option></option>';
				let url = @json(route('DetailAlamat.getRW'));
					url += `?kelurahanID=${ encodeURIComponent(kelurahan) }`

				$(this).prop("disabled", true);
				sRWOldData.parent().append(spinner);
				sRWOldData.html('');
				sRWOldData.prop("disabled", true);

				sRTOldData.html('');
				sRTOldData.prop("disabled", true);

				sBlokOldData.html('');
				sBlokOldData.prop("disabled", true);

				const fetchRW = await fetch(url).then(res => res.json()).catch((err) => {
					sRWOldData.prop("disabled", true);
					spinner.remove()
					Swal.fire({
						title: 'Terjadi kesalahan dalam menghubungi server, silahkan coba lagi...',
						icon: 'warning'
					})
				});

				for(const data of fetchRW) {
					subOption += `<option value="${data.rw_id}">${data.rw}</option>`;
				}

				sRWOldData.html(subOption);
				sRWOldData.select2({ 
					placeholder: '-- Pilih RW --', 
					width: '100%'
				});
				$(this).prop("disabled", false);
				sRWOldData.prop("disabled", false);
				spinner.remove();

				if(kelurahan && selectWargaID)
				{
					const {rw_id} = getDataWarga.result;
					
					$('.rwOldData').val(rw_id).trigger('change');
				}
			}
		})

		$('body').on('change', '.rwOldData', async function(){
			let rw = $(this).val()
			if (rw) {
				let subOption = '<option></option>';
				let url = @json(route('DetailAlamat.getRT'));
					url += `?rwID=${ encodeURIComponent(rw) }`

				$(this).prop("disabled", true);
				sRTOldData.parent().append(spinner);
				sRTOldData.html('');
				sRTOldData.prop("disabled", true);

				sBlokOldData.html('');
				sBlokOldData.prop("disabled", true);

				const fetchRT = await fetch(url).then(res => res.json()).catch((err) => {
					sRTOldData.prop("disabled", true);
					spinner.remove()
					Swal.fire({
						title: 'Terjadi kesalahan dalam menghubungi server, silahkan coba lagi...',
						icon: 'warning'
					})
				});

				for(const data of fetchRT) {
					subOption += `<option value="${data.rt_id}">${data.rt}</option>`;
				}

				sRTOldData.html(subOption);
				sRTOldData.select2({ 
					placeholder: '-- Pilih RT --', 
					width: '100%'
				});
				$(this).prop("disabled", false);
				sRTOldData.prop("disabled", false);
				spinner.remove();

				if(rw && selectWargaID)
				{
					const {rt_id} = getDataWarga.result;
					
					$('.rtOldData').val(rt_id).trigger('change');
				}
			}
		})

		$('body').on('change', '.rtOldData', async function(){
			let rt = $(this).val()
			if (rt) {
				let subOption = '<option></option>';
				let url = @json(route('DetailAlamat.getBlok'));
					url += `?rtID=${ encodeURIComponent(rt) }`

				$(this).prop("disabled", true);

				const fetchBlok = await fetch(url).then(res => res.json()).catch((err) => {
					sBlokOldData.prop("disabled", true);
					spinner.remove()
					Swal.fire({
						title: 'Terjadi kesalahan dalam menghubungi server, silahkan coba lagi...',
						icon: 'warning'
					})
				});

				for(const data of fetchBlok) {
					subOption += `<option value="${data.blok_id}">${data.nama_blok}</option>`;
				}

				sBlokOldData.html(subOption);
				sBlokOldData.select2({ 
					placeholder: '-- Pilih Blok --', 
					width: '100%'
				});
				$(this).prop("disabled", false);
				sBlokOldData.prop("disabled", false);
				spinner.remove();
			}
		})

		$('.sdOldData').change(function() {
			var selected = $(this).children("option:selected").val();

			if (selected == 1) {
				var alamat_domisili = $('.alamatOldData').val();
				$('.alamat-KTP').val(alamat_domisili);
				$('.alamat-KTP').attr("readonly", true);
			} else {
				$('.alamat-KTP').val('');
				$('.alamat-KTP').removeAttr("readonly");
			}
		});

		$(".alamat-KTP").change(function () {
			var alamat_ktp = $(".alamat-KTP").val();
			$('.alamat-KTP').val(alamat_ktp);
		});
	});
</script>