<script>
    //Select Script
	const selectKatKegiatan = document.querySelector('.katKegiatan')

	selectPlaceholder(selectKatKegiatan, 'Kategori Kegiatan')

	function selectPlaceholder(selector,name){
		selector.childNodes[1].innerHTML = `Pilih ${name}`
		selector.childNodes[1].setAttribute('disabled','')
	}
	//

	window.addEventListener('DOMContentLoaded',() => {

		document.querySelector('.search-form').addEventListener('submit',(e) => {
			e.preventDefault()
			const form = document.querySelector('.search-form')
			const valid = $(form).valid()

			if(!valid) return;
			searchFormHandler(e)
		})

        selectKatKegiatan.addEventListener('change',(e) => {
            selectKatKegiatanHandler(e)
        })
	})

    async function selectKatKegiatanHandler (e){
            const selectKatKegiatan = e.currentTarget
            const selectKegiatan = document.querySelector('.kegiatan')
            const inputStartDate = document.querySelector('input[name=start_date]')
            const inputEndDate = document.querySelector('input[name=end_date]')

            const getKatKegiatan = async () => {
                const url = `{{route('Transaksi.Header.getKodeKegiatan')}}`
                const kat_kegiatan_id = selectKatKegiatan.value

                return await fetch(url,{
                    headers:{
                        'X-CSRF-TOKEN':'{{csrf_token()}}',
                        'X-Requested-With':'XMLHttpRequest',
                        'Content-Type':'application/json'
                    },
                    method:'post',
                    body:JSON.stringify({kat_kegiatan_id})
                })
                 .then(response => response.json())
                 .catch(() => {
                    swal.fire('Maaf Terjadi Kesalahan','','error')
									.then(result => {
										if(result.isConfirmed) window.location.reload()
									})
                 })
            }

            const {selectElKegiatan} = await getKatKegiatan()
            
            selectKegiatan.innerHTML = selectElKegiatan
            selectKegiatan.removeAttribute('disabled')
            inputStartDate.removeAttribute('disabled')
            inputEndDate.removeAttribute('disabled')
    }

	async function searchFormHandler(e){
		loading()

		const form =  e.currentTarget;
		const url = `{{route('LaporanSikad.LaporanTransaksiKegiatan.searchLaporanByKegiatan')}}`

		const {result} = await fetchLaporan(url,form)

		document.querySelector('.result-container').innerHTML = result;

		$('.table-search-result').DataTable({
			dom: 'Bfrtip',
			"columnDefs": [
							{"className": "dt-center", "targets": "_all"}
						  ],
			buttons: [
				{
					extend: 'print',
					customize: function ( win ) {
						$(win.document.body)
							.css( 'font-size', '10pt' )
	
						$(win.document.body).find( 'table' )
							.addClass( 'compact' )
							.css( 'font-size', 'inherit' );
					}
				}
       	 	],
			drawCallback:() => dataTableCallback()
		})

		KTApp.unblockPage()
		$('.result-container').fadeIn('slow')

	}

	function loading(){
		KTApp.blockPage({
			overlayColor: '#000000',
			state: 'primary',
			message: 'Mohon Tunggu Sebentar'
 		});
	}

	function dataTableCallback(){

		// sumTotalHandler()
		
		document.querySelector('.buttons-print').innerText = 'Export Excel'

		$('.buttons-print').unbind('click').bind('click',()=> {
			buttonPrintHandler()
		})
	}

	function buttonPrintHandler(){
		const url = `{{route('LaporanSikad.LaporanTransaksiKegiatanDKM.printLaporanByKegiatan')}}`

		window.open(url)
	}

	function sumTotalHandler(){

		const totalMasuk = document.querySelector('.total-masuk')
		const totalKeluar = document.querySelector('.total-keluar')

		const sumNilaiMasuk = (selector) => {
			let arrNilai = [];
			
			document.querySelectorAll(selector).forEach(node => {
				const nilai = parseInt(node.innerText)

				if(nilai > 0){
					arrNilai = [...arrNilai,nilai]
				}
			})

			return arrNilai.reduce((a,b) => {
									return a+b
								  },0)
		}

		const totalNilaiMasuk = sumNilaiMasuk('.nilai-masuk') ?? 0
		const totalNilaiKeluar = sumNilaiMasuk('.nilai-keluar') ?? 0

		if(totalMasuk || totalKeluar){
			totalMasuk.innerText = totalNilaiMasuk
			totalKeluar.innerText = totalNilaiKeluar
		}
	}

	async function fetchLaporan(url,form){
		formData = new FormData(form)

			return await fetch(url,{
								headers:{
									'X-CSRF-TOKEN':'{{csrf_token()}}',
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
	}
</script>