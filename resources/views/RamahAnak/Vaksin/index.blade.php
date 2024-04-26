@extends('layouts.master')
@section('content')
    <div class="container">
        <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
            <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <h5 class="text-dark font-weight-bold my-1 mr-5">
                            Vaksin
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap py-3">
                <div class="card-toolbar">
                    @include('partials.buttons.add', ['text' => 'Tambah Vaksin'])
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-checkable" id="datatable">
                    <thead>
                        <tr>
                            <th width="50px">No</th>
                            <th>Nama Warga</th>
                            <th>Wajib?</th>
                            <th>Status</th>
                            <th width="150px">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ route('RamahAnak.Vaksin.dataTables') }}',
                columns: [
                    { data:'DT_RowIndex', name:'DT_RowIndex', className:'text-center', searchable: false , orderable: false },
                    { data:'nama_vaksin', name:'nama_vaksin' },
                    { data:'wajib', className:'text-center' },
                    { data:'status_aktif', className:'text-center' },
                    { data:'action', name:'action', className:'text-center', searchable: false , orderable: false },
                ]
            });
            $('[data-toggle="tooltip"]').tooltip();
        });

        const deleteFunc = (id) => {
            swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah anda yakin untuk menghapus data ini?',
                icon: 'warning',
                showCancelButton: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteData(id);
                }
                else if (result.dismiss === Swal.DismissReason.cancel) {
                    swal.fire('Proses Hapus Dibatalkan');
                }
            });
        };

        const deleteData = async (id) => {
            const res = await fetch(`{{ route('RamahAnak.Vaksin.destroy', '') }}/${id}`, {
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                method: 'DELETE',
                body: id
            });
            const {status} = await res.json();
            if (status === 'success') {
                swal.fire('Success', 'Data berhasil dihapus!', 'success');
                $('#datatable').DataTable().ajax.reload();
            }
            else {
                swal.fire('Error', 'Maaf Terjadi Kesalahan!', 'error');
            }
        };

        if ('{{ session()->has('success') }}') {
            swal({
                title: 'Success',
                text: '{{session()->get('success')}}',
                icon: 'success',
            });
        }
    </script>
@endsection