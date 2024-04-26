@extends('layouts.master')

@section('content')
<div class="container">
    @include('partials.breadcumb')
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap py-3">
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="datatable">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th>Produk</th>
                        <th>Pembeli</th>
                        <th>Harga Produk</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        let table = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax: "{{ route('UMKM.Pemesanan.dataTables') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', className:'text-center', searchable: false , orderable: false},
                {data: 'produk', name: 'umkm_produk.nama'},
                {data: 'pembeli', name: 'anggota_keluarga.nama'},
                {data: 'harga_produk', name: 'pemesanan.harga_produk'},
                {data: 'jumlah', name: 'pemesanan.jumlah'},
                {data: 'total', name: 'pemesanan.total'},
                {data: 'created_at', name: 'pemesanan.created_at'},
                {data: 'status', name: 'pemesanan.approved', searchable: false},
                {data: 'action', name: 'action', className:'text-center', searchable: false , orderable: false},
            ],
            "order": [[ 6, "desc" ]],
        });
    })
</script>

@endsection