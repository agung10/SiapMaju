@include('Surat.SuratPermohonan.script')
<script>
    const storeData = async () => {
		const formUploadLampiran = $('.upload-lampiran[required]')

		let is_valid = false
		const collection = Array.from(formUploadLampiran)
		
		if (collection.length == 0) {
			is_valid = true
		} else {
			is_valid = true
			collection.forEach(element => {
				if (!element.validity.valid) {
					is_valid = false
				} else {
					is_valid = true
				}
			});
		}

		if (!is_valid) {
			swal.fire('Anda harus memasukkan lampiran yang wajib di isi !','','error');
			return 
		} 

		var dataImages = document.getElementsByClassName('upload-lampiran');
		var storeAct = 0;
		for (let i = 0; i < dataImages.length; i++) {
			var dataImage = $('#'+dataImages[i].getAttribute("id"))
			var valImage = dataImage.val();
			if (valImage) {
				var extension = valImage.substring(valImage.lastIndexOf('.') + 1).toLowerCase();
				const allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'gif']
				if (!allowedExtensions.some((allowedExtension) => allowedExtension === extension)) {
					swal.fire('Lampiran hanya mengizinkan jenis file JPG, JPEG, PNG, GIF, dan PDF !', '', 'error');
					return
				}
				var dataImage2 = document.getElementById(dataImages[i].getAttribute("id"));
				if (dataImage2.files.length > 0) {
					for (let i = 0; i < dataImage2.files.length; i++) {
						let fsize = dataImage2.files.item(i).size;
						let file = Math.round((fsize / 1024));
						// The size of the file.
						if (file >= 2048) {
							swal.fire('File yang diupload melebihi batas maksimum, batas maksimum file yaitu 2 MB', '', 'error');
							return
						} else {
							storeAct = 1			
						}
					}
				}
			}
		}
		if (dataImages.length > 0){
			storeAct = 1
		} 

		if (storeAct == 1) {
			loading();

			const formSurat = document.querySelector('.form-surat')
			const formData = new FormData(formSurat)

			const res = await fetch("{{route('Surat.SuratPermohonan.store')}}",{
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

			const {status} = res;
			
			if(status === 'success'){
				KTApp.unblockPage()

				localStorage.setItem('success','Pengajuan Surat Permohonan Berhasil');	
				return location.replace('{{route("Surat.SuratPermohonan.index")}}');
			}
		}
	}

    document.querySelector('.form-surat').addEventListener('submit',(e) => {
        e.preventDefault()
        const form = e.currentTarget
        const valid = $(form).valid()

		// if user choose jenis surat lain-lain
		checkJenisSurat()

        if(!valid) return;
        
        storeData()
    })

	function checkJenisSurat(){
		const keperluanContainer = document.querySelector('.keperluan-container')
		const keperluanStyle = getComputedStyle(keperluanContainer)
		const keperluan = document.querySelector('.keperluan').value
		const hal = document.querySelector('input[name=hal]')

		if(keperluanStyle.display == 'block'){
			hal.value = `Lain-lain (${keperluan})`
		}
	}
</script>