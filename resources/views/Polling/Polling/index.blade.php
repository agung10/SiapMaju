@extends('layouts.master')

@section('content')
    <div class="container">
        <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
            <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <h5 class="text-dark font-weight-bold my-1 mr-5">Polling</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap py-3">
                <div class="card-toolbar">
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-checkable table-responsive" id="datatable">
                    <thead>
                        <tr>
                            <th width="5%">No.</th>
                            <th>RT</th>
                            <th>Penutupan</th>
                            <th>Pertanyaan</th>
                            <th width="20%">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('Polling.Polling.dataTables') }}',
                columns: [
                    {data:'DT_RowIndex', name:'DT_RowIndex', className:'text-center'},
                    {data:'rt', name:'rt', className:'text-center'},
                    {data:'close', name:'close'},
                    {data:'isi_pertanyaan', name:'isi_pertanyaan'},
                    {data:'action', name:'action', className:'text-center'}
                ]
            });

            if ('{{ session()->has('success') }}') {
                swal.fire({
                    title: 'Success',
                    text: '{{session()->get('success')}}',
                    icon: 'success',
                }).then(() => { window.close(); });
            }
        });
    </script>
@endsection