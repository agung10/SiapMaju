<script>

	window.addEventListener('DOMContentLoaded',() => {

		document.querySelector('.search-form').addEventListener('submit',(e) => {
			e.preventDefault()
			const form = document.querySelector('.search-form')
			const valid = $(form).valid()

			if(!valid) return;
			searchFormHandler(e)
		})

	})

	async function searchFormHandler(e){
		loading()

		const form =  e.currentTarget;
		const url = `{{route('LaporanSikad.LaporanTransaksiIdulFitri.searchLaporan')}}`

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
		
		document.querySelector('.buttons-print').innerText = 'Export Excel'

		$('.buttons-print').unbind('click').bind('click',()=> {
			buttonPrintHandler()
		})
	}

	function buttonPrintHandler(){
		const url = `{{route('LaporanSikad.LaporanTransaksiIdulFitri.printLaporanByKegiatan')}}`

		window.open(url)
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