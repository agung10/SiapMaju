@extends('layouts.master')

@section('content')
<div class="container">
    @include('partials.breadcumb')
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-toolbar">
                @include('partials.buttons.add', ['text' => 'Tambah Data'])
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-checkable" id="datatable">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th>Nama</th>
                        <th>Route</th>
                        <th>Parent</th>
                        <th>Status</th>
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
            ajax: "{{ route('master.menu.dataTables') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', className:'text-center', searchable: false , orderable: false},
                {data: 'name', name: 'menu.name', className:'text-center'},
                {data: 'cust_route', name: 'menu.route', className:'text-center'},
                {data: 'cust_nama_parent', name: 'parent.name', className:'text-center'},
                {data: 'cust_is_active', name: 'menu.is_active', className:'text-center'},
                {data: 'action', name: 'action', className:'text-center', searchable: false , orderable: false},
            ]
        });
        $('[data-toggle="tooltip"]').tooltip()
    })

    const deleteData = async (id) => {
        const res = await fetch(`{{ route('master.menu.destroy','') }}/${id}`,
        {
            headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},
            method:'DELETE',
            body:id
        });

        const {status} = await res.json();
        console.log(status)

        if(status === 'success'){
            swal.fire("Success", "Berhasil Di Hapus!", "success");

            $('#datatable').DataTable().ajax.reload();
        }else{
            swal.fire("Error", "Maaf Terjadi Kesalahan!", "error");
        }
    }

    const deleteFunc = (id) => {
        swal.fire({
            title: "Konfirmasi",
            text: "Apakah anda yakin untuk menghapus menu ?",
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