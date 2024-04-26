<script>
	// 	//Select Script
	// const selectBlok = document.querySelector('.blok')
	// const selectRT = document.querySelector('.rt')
	// const selectRW = document.querySelector('.rw')

	// selectPlaceholder(selectBlok, 'Blok')
	// selectPlaceholder(selectRT, 'RT')
	// selectPlaceholder(selectRW, 'RW')

	// function selectPlaceholder(selector,name){
	// 	selector.childNodes[1].innerHTML = `Pilih ${name}`
	// 	selector.childNodes[1].setAttribute('disabled','')
	// }
	
	//
	const updateKeluarga = async (form,keluarga_id) => {
		loading();

		const formData = new FormData(form)
		const url = `{{route('Master.ListKeluarga.updateKeluarga','')}}/${keluarga_id}`

		return await fetch(url,{
								headers:{
									'X-CSRF-TOKEN':'{{ csrf_token() }}',
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

	const storeAnggota = async () => {
		loading();
		const formAnggota = document.querySelector('#anggota-form')
		const formData = new FormData(formAnggota)

		const res = await fetch(`{{route('Master.ListKeluarga.storeAnggotaKeluarga')}}`,{
									headers:{
										'X-CSRF-TOKEN':'{{ csrf_token() }}',
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

		const {status} = res

		if(status === 'success'){
			localStorage.setItem('success','Keluarga Berhasil Ditambahkan');

			return location.replace('{{route("Master.ListKeluarga.index")}}');
		}
	}

	const updateAnggota = async (form,anggota_keluarga_id) => {
		loading()

		const formData = new FormData(form)
		const url = `{{route('Master.ListKeluarga.updateAnggotaKeluarga','')}}/${anggota_keluarga_id}`

		return await fetch(url,{
							 headers:{
								 'X-CSRF-TOKEN':'{{ csrf_token() }}',
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

	function toggleButton(form,action){
		const button = form.querySelector('button')

		if(action == 'Edit'){
			button.classList.add('btn-edit')
			button.classList.replace('btn-primary','btn-warning')

			$(button).unbind('click').bind('click',(e) => {
				e.preventDefault()

				btnEditHandler()
			})
		}else if(action == 'Submit'){
			button.classList.remove('btn-edit')
			button.classList.replace('btn-warning','btn-primary')

			$(button).unbind('click')
		}
		
		button.innerText = action
	}
</script>