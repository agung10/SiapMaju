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
            ajax: "{{ route('role_management.ListRtRw.dataTables') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', className:'text-center', searchable: false , orderable: false},
                {data: 'nama', name: 'anggota_keluarga.nama', className:'text-center'},
                {data: 'rt', className:'text-center'},
                {data: 'rw', className:'text-center'},
                {data: 'dkm', className:'text-center'},
                {data: 'action', name: 'action', className:'text-center', searchable: false , orderable: false},
                {data: 'rt', name: 'rt.rt', searchable: false, visible: false },
                {data: 'rw', name: 'rw.rw', searchable: false, visible: false },
            ],
            "order": [[ 6, "asc" ], [ 7, "asc" ]],
            drawCallback:()=>{
                dataTableCallBack()
            }
        });
        $('[data-toggle="tooltip"]').tooltip()
    })

    const deleteData = async (id) => {
           const res = await fetch(`{{ route('role_management.ListRtRw.destroy','') }}/${id}`,{
               headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},
               method:'DELETE',
               body:id
           });

           const {status} = await res.json();
    
           if(status === 'success'){
             swal.fire("Success", "RT/RW Berhasil Di Hapus!", "success");

             $('#datatable').DataTable().ajax.reload();
           }else{
            swal.fire("Error", "Maaf Terjadi Kesalahan!", "error");
           }
    }

    const deleteFunc = (id) => {
        swal.fire({
            title: "Konfirmasi",
            text: "Apakah anda yakin untuk menghapus RT/RW ini?",
            icon: "warning",
            showCancelButton: true,
    
        })
          .then((result) => {
                if (result.isConfirmed) {
                    deleteData(id);
                } else if(result.dismiss === Swal.DismissReason.cancel) {
                    swal.fire("Proses Hapus Dibatalkan");
                }
            });
     
    }

    function dataTableCallBack(){
        document.querySelectorAll('.btn-custom').forEach(node => {
            node.addEventListener('click',(e) => {
                e.preventDefault();
                pilihRtRwHandler(e)
            })
        })
    }

    function pilihRtRwHandler(e){
        const btn = e.currentTarget
        const url = e.currentTarget.href
        
        $('#RtRwModal').modal('show')

        $('.rt-rw-form').unbind('submit').bind('submit',(e) => {
            e.preventDefault()
            const form = e.currentTarget
            formRtRwHandler(form,url)
        })
    }

    async function formRtRwHandler(form,url){
        $('#RtRwModal').modal('hide')
        loading()

        const formData = new FormData(form)

        const updateRtRw = async () => {
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

        const {status} = await updateRtRw()

        if(status == 'success'){
            $('#datatable').DataTable().ajax.reload()
            form.reset()
            KTApp.unblockPage()
            swal.fire('Update RT/RW Berhasil','','success')
        }
    }

    function loading(){
		KTApp.blockPage({
			overlayColor: '#000000',
			state: 'primary',
			message: 'Mohon Tunggu Sebentar'
 		});
	}
</script>