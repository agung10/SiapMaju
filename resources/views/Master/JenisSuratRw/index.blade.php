@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Jenis Surat RW
                    </h5>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-toolbar">
                @include('partials.buttons.add', ['text' => 'Tambah Jenis Surat RW'])
            </div>
        </div>
        <div class="card-body">
        <table class="table table-bordered table-checkable" id="datatable">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th width="50%">Jenis Surat RW</th>
                    <th width="25%">Action</th>
                </tr>
            </thead>
         </table>
        </div>
    </div>
</div>
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
            ajax: "{{ route('Master.JenisSuratRw.dataTables') }}",
            columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', className:'text-center'},
            {data: 'jenis_surat', name: 'jenis_surat',className:'text-center'},
            {data: 'action', name: 'action', className:'text-center'},
            ]
        });
        $('[data-toggle="tooltip"]').tooltip()
    })

    const deleteData = async (id) => {
           const res = await fetch(`{{ route('Master.JenisSuratRw.destroy','') }}/${id}`,{
               headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},
               method:'DELETE',
               body:id
           });

           const {status} = await res.json();

           if(status === 'success'){
             swal.fire("Success", "Jenis Surat RW Berhasil Di Hapus!", "success");

            $('#datatable').DataTable().ajax.reload();
           }else{
            swal.fire("Error", "Maaf Terjadi Kesalahan!", "error");
           }
    }

    const deleteFunc = (id) => {
        swal.fire({
            title: "Konfirmasi",
            text: "Apakah anda yakin untuk menghapus Jenis Surat RW?",
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
</script>

@endsection