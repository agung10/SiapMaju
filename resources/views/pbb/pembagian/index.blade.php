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
            @include('partials.alert')
            <table class="table table-bordered" id="datatable">
                <thead>
                    <tr>
                        <th width="3%">No</th>
                        <th>Blok</th>
                        <th>Penerima</th>
                        <th>NOP</th>
                        <th>Tanggal Terima</th>
                        <th>Tahun Pajak</th>
                        <th width="20%">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax: "{{ route('pbb.pembagian.dataTables') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', className:'text-center', searchable: false, sortable: false },
                {data: 'nama_blok', name: 'blok.nama_blok', className:'text-center'},
                {data: 'nama_warga', name: 'anggota_keluarga.nama', className:'text-center'},
                {data: 'nop', name: 'nop', className:'text-center'},
                {data: 'tgl_terima', name: 'tgl_terima', className:'text-center'},
                {data: 'tahun_pajak', name: 'tahun_pajak', className:'text-center'},
                {data: 'action', name: 'action', className:'text-center', searchable: false, sortable: false },
                {data: 'updated_at', name: 'updated_at', searchable: false, visible: false },
            ],
            "order": [[ 7, "desc" ]], // updated_at descending
        });
    })
</script>

@endsection