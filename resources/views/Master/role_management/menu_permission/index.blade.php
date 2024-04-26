@extends('layouts.master')

@section('content')
<div class="container">
    @include('partials.breadcumb')
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-toolbar">
                <!-- @include('partials.buttons.add', ['text' => 'Tambah Data']) -->
            </div>
        </div>
        @if ($message = Session::get('success'))
          <div class="alert alert-success alert-block mx-5">
            <button type="button" class="close" data-dismiss="alert">×</button> 
                <strong>{{ $message }}</strong>
          </div>
        @endif
        <div class="card-body">
            <table class="table table-bordered table-checkable" id="datatable">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th width="15%">Nama Role</th>
                        <th>Menu</th>
                        <th>Permission</th>
                        <th width="25%">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        var table = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax: "{{ route('master.menu-permission.dataTables') }}",
            columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', className:'text-center', searchable: false, orderable:false},
            {data: 'role_name', name: 'role.role_name', width: '20%', className:'text-center'},
             {data: 'menu', orderable:false, searchable: false, className:'text-center'},
             {data: 'permission', orderable:false, searchable: false, className:'text-center'},
            {data: 'action', name: 'action'},
            ]
        });
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>

@endsection