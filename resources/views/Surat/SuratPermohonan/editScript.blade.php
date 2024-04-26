@include('Surat.SuratPermohonan.script')
<script>
    document.querySelector('.form-surat').addEventListener('submit',(e) => {
        e.preventDefault();
        const form =  e.currentTarget
        const valid = $(form).valid()

        if(!valid) return;

        updateSurat(form)
    })

    async function updateSurat(form){
        const formUploadLampiran = $('.upload-lampiran[required]')
		
        let is_valid = false
		const collection = Array.from(formUploadLampiran)
		
		if (collection.length) is_valid = true
		collection.forEach(element => {
			if (!element.validity.valid) {
				is_valid = false
			} else {
				is_valid = true
			}
		});
        
		if (collection.length > 0 && !is_valid) {
			swal.fire('Anda harus memasukkan lampiran yang wajib di isi !','','error');
			return 
		} 
        else {
            var dataImages = document.getElementsByClassName('upload-lampiran');
			var storeAct = 0;
            if (collection.length > 0) {
                for (let i = 0; i < dataImages.length; i++) {
                    var dataImage = $('#'+dataImages[i].getAttribute("id"))
                    var valImage = dataImage.val();
                    if (valImage) {
                        var Extension = valImage.substring(valImage.lastIndexOf('.') + 1).toLowerCase();
                        if (Extension == "jpg" || Extension == "jpeg" || Extension == "png" || Extension == "pdf" || Extension == "gif") {
                            var dataImage2 = document.getElementById(dataImages[i].getAttribute("id"));
                            if (dataImage2.files.length > 0) {
                                for (let i = 0; i < dataImage2.files.length; i++) {
                                    let fsize = dataImage2.files.item(i).size;
                                    let file = Math.round((fsize / 1024));
                                    // The size of the file.
                                    if (file >= 2048) {
                                        swal.fire('File yang diupload melebihi batas maksimum, batas maksimum file yaitu 2 MB', '', 'error');
                                    } else {
                                        storeAct = 1			
                                    }
                                }
                            }
                        }
                        else {
                            swal.fire('Lampiran hanya mengizinkan jenis file JPG, JPEG, PNG, GIF, dan PDF !', '', 'error');
                        }
                    }
                }
            } else {
                storeAct = 1;
            }

            if (storeAct == 1) {
                loading();

                const fetchUpdateSurat = async () => {
                    const surat_id = '{{ $data->surat_permohonan_id}}'
                    const url = `{{route('Surat.SuratPermohonan.update','')}}/${surat_id}`
                    const formData = new FormData(form)

                    return await fetch(url,{
                        headers:{
                            'X-CSRF-TOKEN':'{{csrf_token()}}',
                            'X-Requested-With':'XMLHttpRequest',
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

                const {status} = await fetchUpdateSurat()

                if(status === 'success'){
                    localStorage.setItem('success','Surat Permohonan Berhasil DiUpdate');
                    return location.replace('{{route("Surat.SuratPermohonan.index")}}');
                }
            }
        }
    }
</script>