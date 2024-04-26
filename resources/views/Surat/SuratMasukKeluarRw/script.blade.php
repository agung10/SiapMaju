<script>
    //Select Script
	const selectJenisSurat = document.querySelector('.jenis-surat-id')
	const selectSifatSurat = document.querySelector('.sifat-surat-id')
	const selectAsalSurat = document.querySelector('.asal-surat')
    const selectTujuanSurat = document.querySelector('.tujuan-surat')
    const selectWarga = document.querySelector('.warga')
    const selectSuratBalasan = document.querySelector('.surat-balasan')

	selectPlaceholder(selectJenisSurat, 'Jenis Surat')
	selectPlaceholder(selectSifatSurat, 'Sifat Surat')
	selectPlaceholder(selectAsalSurat, 'Asal Surat')
	selectPlaceholder(selectTujuanSurat, 'Tujuan Surat')
	selectPlaceholder(selectWarga, 'Warga')
	selectPlaceholder(selectSuratBalasan, 'Surat Balasan')

	function selectPlaceholder(selector,name){
		selector.childNodes[1].innerHTML = `Pilih ${name}`
		selector.childNodes[1].setAttribute('disabled','')
	}
	//

    selectWarga.addEventListener('change',(e) => {
        selectWargaHandler(e)
    })

    selectJenisSurat.addEventListener('change',(e) => {
        selectJenisSuratHandler(e)
    })

    async function selectJenisSuratHandler(e){
        const jenisSurat = e.currentTarget.value

        if(jenisSurat == 2){
            const generateNoSuratKeluar = async () => {
                const url = `{{route('Surat.SuratMasukKeluarRw.generateNoSuratKeluar')}}`

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
            
            const {status,noSurat} = await generateNoSuratKeluar()

            if(status !== 'success') return;
            document.querySelector('input[name=no_surat]').value = noSurat

        }else{
            document.querySelector('input[name=no_surat]').value = ``
        }
        
    }

    async function selectWargaHandler(e){

        const fetchDataWarga = async () => {    
            const warga_id = e.currentTarget.value
            const url = `{{route('Surat.SuratPermohonan.getDataWarga','')}}/${warga_id}`

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

        const {result,data:{rt_id,rw_id}} = await fetchDataWarga()
        
        if(result !== 'success') return;
        document.querySelector('input[name=rt_id]').value = rt_id
        document.querySelector('input[name=rw_id]').value = rw_id
    }

    function loading(){
		KTApp.blockPage({
			overlayColor: '#000000',
			state: 'primary',
			message: 'Mohon Tunggu Sebentar'
 		});
	}
</script>