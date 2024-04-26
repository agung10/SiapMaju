@include('Surat.SuratMasukKeluarRw.script')
<script>
    document.querySelector('.form-surat').addEventListener('submit',(e) => {
        e.preventDefault();
        const form =  e.currentTarget
        const valid = $(form).valid()

        if(!valid) return;

        updateSurat(form)
    })

    async function updateSurat(form){
        loading();

        const fetchUpdateSurat = async () => {
            const surat_id = '{{ $data->surat_masuk_keluar_rw_id}}'

            const url = `{{route('Surat.SuratMasukKeluarRw.update','')}}/${surat_id}`
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

			return location.replace('{{route("Surat.SuratMasukKeluarRw.index")}}');
		}
        
    }
</script>