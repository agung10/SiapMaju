<script>
    //Select Script
	const selectWarga = document.querySelector('.warga')
	const selectJenisSurat = document.querySelector('.jenis_surat')

	selectPlaceholder(selectWarga, 'Nama Warga')
	selectPlaceholder(selectJenisSurat, 'Jenis Surat')

	function selectPlaceholder(selector,name){
		selector.childNodes[1].innerHTML = `Pilih ${name}`
		selector.childNodes[1].setAttribute('disabled','')
	}
	//
    
    // Warga
    window.addEventListener('DOMContentLoaded', async () => {
        const warga = document.querySelector('select[name=anggota_keluarga_id]')
        const selectedWarga = warga.options[warga.selectedIndex]
        const warga_id = selectedWarga.value

        if(!isNaN(warga_id)){
            wargaHandler(warga_id)    
        }
    })

    const fetchDataWarga = async (warga_id) => {
        const url = `{{route('Surat.SuratPermohonan.getDataWarga','')}}/${warga_id}`

        return await fetch(url,{
                            headers:{
                                'X-Requested-With':'XMLHttpRequest',
                                'X-CSRF-TOKEN':'{{ csrf_token() }}',
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

    const wargaHandler = async (warga_id) => {
        

        const {result,data} = await fetchDataWarga(warga_id)
        
        if(result == 'success'){
            inputDataWarga(data)
        }
        
    }

    document.querySelector('select[name=anggota_keluarga_id]').addEventListener('change',(e) => {
        const warga_id = e.currentTarget.value

        wargaHandler(warga_id);
    })

    function inputDataWarga(data){
        const {nama,alamat,tgl_lahir,rt_id,rw_id,kelurahan_id,subdistrict_id,city_id,province_id} = data
        document.querySelector('input[name=nama_lengkap]').value = nama
        document.querySelector('input[name=alamat]').value = alamat
        document.querySelector('input[name=tgl_lahir]').value = tgl_lahir
        document.querySelector('input[name=rt_id]').value = rt_id
        document.querySelector('input[name=rw_id]').value = rw_id
        document.querySelector('input[name=kelurahan_id]').value = kelurahan_id
        document.querySelector('input[name=subdistrict_id]').value = subdistrict_id
        document.querySelector('input[name=city_id]').value = city_id
        document.querySelector('input[name=province_id]').value = province_id
    }

    // JenisSurat
    // clearDataStorage()
    // document.querySelector('select[name=jenis_surat_id]').addEventListener('change',(e) => {
    //     const jenisSurat = e.currentTarget.value
    //     const halSurat = getHalSurat(e);
    //     const inputHalSurat = document.querySelector('input[name=hal]')
    //     const keperluan = document.querySelector('.keperluan-container')
    //     inputHalSurat.value = halSurat

    //     if(jenisSurat == 15){
    //         $(keperluan).fadeIn('slow')
    //     }else{
    //         $(keperluan).fadeOut('slow')
    //     }
    // })

    // document.querySelectorAll('.upload-lampiran').forEach(node => {
    //     node.addEventListener('change',() => {
    //         lampiranHandler()
    //     },{once:true})
    // })

    // function getHalSurat(e){
    //     const select = e.currentTarget

    //     const selectedText = select.options[select.selectedIndex].text

    //     return selectedText
    // }

    // function lampiranHandler(){
    //     const inputTotalLampiran = document.querySelector('input[name=lampiran]')
    //     const totalLampiran = localStorage.getItem('totalLampiran') ?? '0'
    //     if(totalLampiran > 3) return
    //     const countLampiran = parseInt(totalLampiran) + 1
        
    //     localStorage.setItem('totalLampiran',countLampiran)
    //     inputTotalLampiran.value = countLampiran
    // }

    // function clearDataStorage(){
    //     const totalLampiran = localStorage.getItem('totalLampiran')

    //     if(totalLampiran){
    //         localStorage.removeItem('totalLampiran')
    //     }
    // }

    function loading(){
		KTApp.blockPage({
			overlayColor: '#000000',
			state: 'primary',
			message: 'Mohon Tunggu Sebentar'
 		});
	}
</script>