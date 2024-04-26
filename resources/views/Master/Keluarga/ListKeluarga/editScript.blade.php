@include('Master.Keluarga.ListKeluarga.script')
<script>
    
    (function initFunction(){
        hiddenInputData()
    })

    const updateKeluargaHandler = async (form) => {
        const keluarga_id = '{{\Crypt::encryptString($data->keluarga_id)}}'

        const {status,no_telp,alamat,email,rt_id,rw_id,kelurahan_id,subdistrict_id,city_id,province_id} = await updateKeluarga(form,keluarga_id)

        if(status === 'error_email'){
			document.querySelector('input[name="email"]').classList.replace('is-valid','is-invalid')
			KTApp.unblockPage()
			swal.fire('Maaf Alamat Email Sudah Digunakan !','','error');

            return;
		}

        if (status !== 'success') return;

        document.querySelector('.anggota-mobile').value = no_telp
        document.querySelector('.anggota-alamat').value = alamat
		document.querySelector('.anggota-email').value = email
		document.querySelector('.anggota-rt_id').value = rt_id
		document.querySelector('.anggota-rw_id').value = rw_id
		document.querySelector('.anggota-kelurahan_id').value = kelurahan_id
		document.querySelector('.anggota-subdistrict_id').value = subdistrict_id
		document.querySelector('.anggota-city_id').value = city_id
		document.querySelector('.anggota-province_id').value = province_id

        KTApp.unblockPage()
        hiddenInputData()
        swal.fire('Keluarga Berhasil di Update','','success')
    }

    const addKepalaKeluargaHandler = (e) => {
        const button = e.currentTarget
        const buttonContainer = button.closest('.no-data-container')
        const formAnggotaContainer = document.querySelector('.form-anggota-container-edit')

        $(buttonContainer).fadeOut('slow')
        formAnggotaContainer.classList.remove('d-none')
        formAnggotaContainer.style.display = 'none'
        $(formAnggotaContainer).fadeIn('slow')
    }

    const updateAnggotaKeluargaHandler = async (form,anggota_keluarga_id) => {
        const {status} = await updateAnggota(form,anggota_keluarga_id)

        if(status !== 'success') return;
        KTApp.unblockPage()
        
        swal.fire('Anggota Keluarga berhasil di update','','success')
            .then(result => {              
                if(result.isConfirmed) window.location.href = `{{route('Master.ListKeluarga.index')}}`
            })
    }

	document.querySelector('.form-keluarga').addEventListener('submit', (e) => {
		e.preventDefault();
        const formKeluarga = document.querySelector('.form-keluarga')
        const valid = $(formKeluarga).valid()
        if(!valid) return;

		updateKeluargaHandler(formKeluarga);
	})

    document.querySelector('.add-kepala')?.addEventListener('click',(e) => {
        addKepalaKeluargaHandler(e)
    })

    document.querySelector('#anggota-form').addEventListener('submit',(e) => {
        e.preventDefault()
        
        const formAnggota = e.currentTarget
        const valid = $(formAnggota).valid()
        const anggota_keluarga_id = '{{$data->anggota_keluarga_id}}'
        
        if(!valid) return;

        if(anggota_keluarga_id == ''){
            storeAnggota()
        }else{
            updateAnggotaKeluargaHandler(formAnggota,anggota_keluarga_id)
        }
    })

    function hiddenInputData(){
        const mobile = document.querySelector('input[name=mobile]').value
        const alamat = document.querySelector('input[name=alamat]').value
        const email = document.querySelector('input[name=email]').value
        const rt_id = document.querySelector('input[name=rt_id]').value
        const rw_id = document.querySelector('input[name=rw_id]').value
        const kelurahan_id = document.querySelector('input[name=kelurahan_id]').value
        const subdistrict_id = document.querySelector('input[name=subdistrict_id]').value
        const city_id = document.querySelector('input[name=city_id]').value
        const province_id = document.querySelector('input[name=province_id]').value

        document.querySelector('.anggota-mobile').value = mobile
        document.querySelector('.anggota-alamat').value = alamat
        document.querySelector('.anggota-email').value = email
		document.querySelector('.anggota-rt_id').value = rt_id
		document.querySelector('.anggota-rw_id').value = rw_id
		document.querySelector('.anggota-kelurahan_id').value = kelurahan_id
		document.querySelector('.anggota-subdistrict_id').value = subdistrict_id
		document.querySelector('.anggota-city_id').value = city_id
		document.querySelector('.anggota-province_id').value = province_id
    }

    // Initialization
    jQuery(document).ready(function() {
		$('select[name=province_id]').select2({ width: '100%', placeholder: '-- Pilih Provinsi --'})
		$('select[name=city_id]').select2({ width: '100%', placeholder: '-- Pilih Kabupaten --'})
		$('select[name=subdistrict_id]').select2({ width: '100%', placeholder: '-- Pilih Kecamatan --'})
		$('select[name=kelurahan_id]').select2({ width: '100%', placeholder: '-- Pilih Kelurahan --'})
		$('select[name=rw_id]').select2({ width: '100%', placeholder: '-- Pilih RW --'})
		$('select[name=rt_id]').select2({ width: '100%', placeholder: '-- Pilih RT --'})
		$('select[name=blok_id]').select2({ width: '100%', placeholder: '-- Pilih Blok --'})

		$('select[name=jenis_kelamin]').select2({ width: '100%', placeholder: '-- Pilih Jenis Kelamin --'})
		$('select[name=status_domisili]').select2({ width: '100%', placeholder: '-- Pilih Status Domisili --'})

		const spinner = $('<div id="select-spinner" class="spinner spinner-right"></div>');
		const selectCity = $('#city')
		const selectSubdistrict = $('#subdistrict')
		const selectKelurahan = $('#kelurahan')
		const selectRW = $('#rw')
		const selectRT = $('#rt')

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

		$('body').on('change', 'select[name=rt_id]', async function(){
			let selectBlok = $('select[name=blok_id]')

			let rt = $(this).val()
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
		})

		$(window).on( "load", function() {
			const status_domisili = $('select[name=status_domisili]')
			var selected = status_domisili.children("option:selected").val();

			if (selected == 1) {
				var alamat_domisili = $('textarea[name=alamat]').val();
				$('.alamat-ktp').val(alamat_domisili);
				$('.alamat-ktp').attr("readonly", true);
			}
		});

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