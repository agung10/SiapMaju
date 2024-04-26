@extends('layouts.master')
@section('content')
    <div class="container">
        <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
            <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <h5 class="text-dark font-weight-bold my-1 mr-5">
                            Mutasi Warga
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap py-3">
                <div class="card-toolbar">
                    @include('partials.buttons.add', ['text' => 'Tambah Mutasi Warga'])
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-checkable" id="datatable">
                    <thead>
                        <tr>
                            <th width="50px">No</th>
                            <th>Nama Warga</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th width="80px">Action</th>
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
                ajax: '{{ route('Master.MutasiWarga.dataTables') }}',
                columns: [
                    { data:'DT_RowIndex', name:'DT_RowIndex', className:'text-center', searchable: false , orderable: false},
                    { data:'nama', name:'anggota_keluarga.nama'},
                    { data:'tanggal_mutasi', className:'text-center'},
                    { data:'nama_status', className:'text-center'},
                    { data:'action', name:'action', className:'text-center', searchable: false , orderable: false},
                ]
            });
            $('[data-toggle="tooltip"]').tooltip();
        })

        if ('{{ session()->has('success') }}') {
            swal({
                title: 'Success',
                text: '{{session()->get('success')}}',
                icon: 'success',
            });
        }
    </script>
@endsection