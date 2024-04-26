@extends('layouts.master')

@section('content')
<div class="container">
    @include('partials.breadcumb')
    <div class="card card-custom gutter-b">
        <div class="card-body">
            @include('partials.alert')
            <table class="table table-bordered" id="datatable">
                <thead>
                    <tr>
                        <th width="3%">No</th>
                        <th>Blok</th>
                        <th>NOP</th>
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
            ajax: "{{ route('pbb.nop.dataTables') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', className:'text-center', searchable: false, sortable: false },
                {data: 'nama_blok', name: 'nama_blok', className:'text-center'},
                {data: 'nop', name: 'nop', className:'text-center'},
                {data: 'action', name: 'action', className:'text-center', searchable: false, sortable: false },
                {data: 'updated_at', name: 'updated_at', searchable: false, visible: false },
            ],
            "order": [[ 4, "desc" ]], // updated_at descending
        });
    })

    const storage = localStorage.getItem('success');

    if(storage){
        swal({
            title: storage,
            icon: 'success',
        });

        localStorage.removeItem('success');
    }
</script>

@endsection