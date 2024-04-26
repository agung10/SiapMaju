@extends('layouts.master')
@section('content')
    <style type="text/css">
        #process-loading { position:fixed; width:100vw; height:100vh; z-index: 100; display:none; opacity:unset !important; }
        #process-loading img { position:fixed; left:0; top:0; right:0; bottom:0px; margin:auto; width:200px; }
    </style>
    <span id="process-loading" class="text-center"><img src="{{ asset('images/loading.gif') }}"></span>
    <div class="container">
        <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
            <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <h5 class="text-dark font-weight-bold my-1 mr-5">
                            Laporan Vaksinasi
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        @if (\Auth::user()->is_admin)
            <div class="card card-custom gutter-b">
                <div class="card-body row">
                    <div class="col-md-6">
                        <h5>Data per RW</h5>
                        <select name="rw_id" class="form-control">{!! $rw !!}</select>
                    </div>
                </div>
            </div>
        @endif
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
                            <th>Nama</th>
                            <th width="100px">Jenis Kelamin</th>
                            <th width="100px">Tgl Lahir</th>
                            <th width="150px">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('select[name=rw_id]').select2({ width:'100%', placeholder:'-- Pilih RW --' });
            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ route('RamahAnak.Laporan.dataTables') }}',
                    type: 'GET',
                    data: (ress) => {
                        ress.id = $('select[name=rw_id]').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center', searchable: false, orderable: false },
                    { data: 'nama', name: 'anggota_keluarga.nama' },
                    { data: 'jenis_kelamin', name: 'anggota_keluarga.jenis_kelamin' },
                    { data: 'tgl_lahir', className:  'text-center' },
                    { data: 'action', name: 'action', className: 'text-center', searchable: false, orderable: false },
                ]
            });

            $('body').on('change', 'select[name=rw_id]', function() { table.ajax.reload(); });
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