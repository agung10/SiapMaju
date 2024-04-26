@extends('layouts.master')
@section('content')
    <div class="container">
        <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
            <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <h5 class="text-dark font-weight-bold my-1 mr-5">
                            Vaksinasi Posyandu
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap py-3">
                <div class="card-toolbar">
                    @include('partials.buttons.add', ['text' => 'Tambah Vaksinasi'])
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-checkable" id="datatable">
                    <thead>
                        <tr>
                            <th width="50px">No</th>
                            <th width="100px">Nama Vaksin</th>
                            <th width="150px">Nama Anak</th>
                            <th width="100px">Tgl Vaksin</th>
                            <th>Keterangan</th>
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
                ajax: '{{ route('RamahAnak.Posyandu.dataTables') }}',
                columns: [
                    { data:'DT_RowIndex', name:'DT_RowIndex', className:'text-center', searchable: false , orderable: false },
                    { data:'nama_vaksin', name:'nama_vaksin' },
                    { data:'nama', name:'nama' },
                    { data:'tgl_input', className:'text-center' },
                    { data:'ket_vaksinasi' },
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
            const res = await fetch(`{{ route('RamahAnak.Posyandu.destroy', '') }}/${id}`, {
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
                text: '{{ session()->get('success') }}',
                icon: 'success',
            });
        }
    </script>
@endsection