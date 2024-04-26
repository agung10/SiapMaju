@include('Surat.SuratMasukKeluarRw.script')
<script>
    const storeData = async () => {
		loading();

		const formSurat = document.querySelector('.form-surat')
		const formData = new FormData(formSurat)

		const res = await fetch("{{route('Surat.SuratMasukKeluarRw.store')}}",{
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

			return location.replace('{{route("Surat.SuratMasukKeluarRw.index")}}');
		}
	}

    document.querySelector('.form-surat').addEventListener('submit',(e) => {
        e.preventDefault()
        const form = e.currentTarget
        const valid = $(form).valid()

		if(!valid) return;
        storeData()
    })

</script>
