@extends('layouts.master') 
@section('content')
<div class="container">
    <div style="z-index: -1;" class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Menu Urusan
                    </h5>
                </div>
            </div>
        </div>
    </div>

    @include('partials.alert')
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-toolbar">
                @include('partials.buttons.add', ['text' => 'Tambah Menu Urusan'])
            </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered" id="datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Jenis</th>
                        <th>Tanggal Buat</th>
                        <th>Tanggal Ubah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    const storage = localStorage.getItem('success');
    if(storage){
        swal({ title: storage, icon: 'success'});
        localStorage.removeItem('success');
    }

    $(document).ready(function(){
        var table = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax: "{{ route('Musrenbang.Menu-Urusan.dataTables') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', className:'text-center', searchable: false , orderable: false},
                {data: 'nama_jenis', name: 'menu_urusan.nama_jenis', className:'text-center'},
                {data: 'created_at', name: 'menu_urusan.created_at', className:'text-center'},
                {data: 'updated_at', name: 'menu_urusan.updated_at', className:'text-center'},
                {data: 'action', name: 'action', className:'text-center'},
            ],
            "order": [[ 3, "desc" ]],
        });
        $('[data-toggle="tooltip"]').tooltip()
    })

    const deleteFunc = (id) => {
        swal.fire({
            title: "Konfirmasi",
            text: "Apakah anda yakin untuk menghapus data ini?",
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

    const deleteData = async (id) => {
        const res = await fetch(`{{ route('Musrenbang.Menu-Urusan.destroy','') }}/${id}`,{
            headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},
            method:'DELETE',
            body:id
        });

        const {status} = await res.json();

        if(status === 'success') {
            swal.fire("Success", "Data Berhasil Di Hapus!", "success");
            $('#datatable').DataTable().ajax.reload();
        } else {
            swal.fire("Error", "Maaf Terjadi Kesalahan!", "error");
        }
    }
</script>
@endsection
