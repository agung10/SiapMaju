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
            ajax: "{{ route('Master.CapRW.dataTables') }}",
            columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', className:'text-center'},
            {data: 'rw', name: 'rw',className:'text-center'},
            {data: 'cap_rw', name: 'cap_rw',className:'text-center'},
            {data: 'action', name: 'action',className:'text-center'},
            ],
            drawCallback:()=> callBackHandler()
        });
        $('[data-toggle="tooltip"]').tooltip()
    })

    function editCapHandler(e){
        const button = e.currentTarget
        const url = button.href.split('/')

        // url 6 for site https://sikad.tamandepokpermai.com/Master/CapRW
        const cap_id = url[8] ?? url [6]
        
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

                return await fetch(`{{route('Master.CapRW.update','')}}/${cap_id}`,{
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

                const {status} = await updateCap()

                if(status !== 'success') return;
                KTApp.unblockPage()
                swal.fire('Cap RW Berhasil di Update','','success')
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