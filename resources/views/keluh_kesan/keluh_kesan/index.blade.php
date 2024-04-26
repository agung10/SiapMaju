@extends('layouts.master') 
@section('content')
<div class="container">
    <div style="z-index: -1;" class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Keluh Kesan
                    </h5>
                </div>
            </div>
        </div>
    </div>
    @include('partials.alert')
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-toolbar">
                @include('partials.buttons.add', ['text' => 'Tambah keluh kesan'])
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-responsive" id="table-keluh-kesan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>User</th>
                        <th>Keluh Kesan</th>
                        <th>Balasan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        var table = $("#table-keluh-kesan").DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('keluhKesan.keluhKesan.dataTables') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', className:'text-center', searchable: false , orderable: false},
                { data: "username", name: "users.username", className:'text-center'},
                { data: "keluh_kesan", name: "keluh_kesan.keluh_kesan", className:'text-center'},
                { data: "balas_keluh_kesan", className:'text-center'},
                { data: 'action', name: 'action', className:'text-center', searchable: false , orderable: false},
            ],
        });
        $('[data-toggle="tooltip"]').tooltip();
    });

    const deleteData = async (id) => {
        const res = await fetch(`{{ route('keluhKesan.keluhKesan.destroy','') }}/${id}`, {
            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            method: "DELETE",
            body: id,
        });

        const { status } = await res.json();

        if (status === "success") {
            swal.fire("Success", "Konten Berhasil Di Hapus!", "success");

            $("#table-keluh-kesan").DataTable().ajax.reload();
        } else {
            swal.fire("Error", "Maaf Terjadi Kesalahan!", "error");
        }
    };

    const deleteFunc = (id) => {
        swal.fire({
            title: "Konfirmasi",
            text: "Apakah anda yakin untuk menghapus keluh kesah ini beserta balasannya?",
            icon: "warning",
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                deleteData(id);
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swal.fire("Proses Hapus Dibatalkan");
            }
        });
    };

</script>

@endsection
