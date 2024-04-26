@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Laporan Surat Permohonan
                    </h5>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-toolbar">
            <a href="{{route('LaporanSikad.LaporanSuratPermohonan.create')}}" class="btn btn-primary font-weight-bolder">
                <i class="fa fa-print"></i>
		        Print
	        </a>
            </div>
        </div>
        <div class="card-body">
        <table class="table table-bordered table-checkable table-responsive" id="datatable">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th>No Surat</th>
                    <th>Jenis Permohonan</th>
                    <th>Tanggal Surat</th>
                    <th>Nama Warga</th>
                    <th>RT</th>
                    <th>Status Surat</th>
                    <th>Lama Proses</th>
                </tr>
            </thead>
         </table>
        </div>
    </div>
</div>
<script>
    const storage = localStorage.getItem('success');

    if(storage){
        swal({
            title: storage,
            icon: 'success',
        });

        localStorage.removeItem('success');
    }

    $(document).ready(function(){
        var table = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('LaporanSikad.LaporanSuratPermohonan.dataTables') }}",
            columns: [
                {data: 'DT_RowIndex', name:'DT_RowIndex', className:'text-center', searchable: false , orderable: false},
                {data: 'no_surat', name: 'surat_permohonan.no_surat', className:'text-center'},
                {data: 'jenis_permohonan', name: 'jenis_surat.jenis_permohonan', className:'text-center'},
                {data: 'tgl_surat', className:'text-center'},
                {data: 'nama', name: 'anggota_keluarga.nama', className:'text-center'},
                {data: 'rt', name: 'rt.rt', className:'text-center'},
                {data: 'status_surat', className:'text-center'},
                {data: 'lama_proses', className:'text-center'},
                {data: 'surat_permohonan_id', name: 'surat_permohonan.surat_permohonan_id', searchable: false, visible: false},
            ],
            "order": [[ 8, "desc" ]],
            drawCallback: () => $('span').tooltip()
        });
        
    })

</script>

@endsection