<script>
    const storage = localStorage.getItem('success');

    if(storage){
        swal({
            title: storage,
            icon: 'success',
        });

        localStorage.removeItem('success');
    }

    $(document).ready(function(){
        var table = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax: "{{ route('Master.Tanda_Tangan_RT.dataTables') }}",
            columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', className:'text-center'},
            {data: 'rt', name: 'rt',className:'text-center'},
            {data: 'tanda_tangan_rt', name: 'tanda_tangan_rt',className:'text-center'},
            {data: 'action', name: 'action',className:'text-center'},
            ],
            drawCallback:()=> callBackHandler()
        });
        $('[data-toggle="tooltip"]').tooltip()
    })

    function editCapHandler(e){
        const button = e.currentTarget
        const url = button.href.split('/')
        const cap_id = url[7] 
        const form = document.querySelector('.cap-form') 

        $('.modal-cap').modal('show')

        $(form).unbind('submit').bind('submit',(e) => {
            e.preventDefault()

            capFormHandler()
        })

        async function capFormHandler(){
            $('.modal-cap').modal('hide')
            loading()

            const updateCap = async () => {
                const formData = new FormData(form)

                return await fetch(`{{route('Master.Tanda_Tangan_RT.update','')}}/${cap_id}`,{
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
                                                // if(result.isConfirmed) window.location.reload()
                                        })
                                    })
                }

                const {status} = await updateCap()

                if(status !== 'success') return;
                KTApp.unblockPage()
                swal.fire('Tandan Tangan RT Berhasil di Update','','success')
                $('#datatable').DataTable().ajax.reload()
        }
    }

    function callBackHandler()
    {
        document.querySelectorAll('.btn-light-warning').forEach(node => {
            node.addEventListener('click',(e) => {
                e.preventDefault();

                editCapHandler(e)
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
</script>