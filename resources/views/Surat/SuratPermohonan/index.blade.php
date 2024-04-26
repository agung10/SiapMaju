@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Surat Permohonan
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-toolbar">
                @include('partials.buttons.add', ['text' => 'Buat Surat Permohonan'])
            </div>
        </div>
        <div class="card-body">
        <table class="table table-bordered table-checkable table-responsive" id="datatable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th width="300">No Surat</th>
                    <th width="300">Jenis Permohonan</th>
                    <th width="300">Nama Pemohon</th>
                    <th width="300">RT</th>
                    <th width="300">Status</th>
                    <th width="300">Aksi</th>
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
            ajax: "{{ route('Surat.SuratPermohonan.dataTables') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', className:'text-center', searchable: false , orderable: false},
                {data: 'no_surat', name: 'surat_permohonan.no_surat', className:'text-center'},
                {data: 'hal', name: 'surat_permohonan.hal', className:'text-center'},
                {data: 'nama_lengkap', name: 'surat_permohonan.nama_lengkap', className:'text-center'},
                {data: 'rt', name: 'rt.rt', className:'text-center'},
                {data: 'status', className:'text-center'},
                {data: 'action', name: 'action', className:'text-center', searchable: false , orderable: false},
                {data: 'updated_at', name: 'surat_permohonan.updated_at', searchable: false, visible: false },
            ],
            "order": [[ 7, "desc" ]],
        });
        $('[data-toggle="tooltip"]').tooltip()
    })

    const deleteData = async (id) => {
           const res = await fetch(`{{ route('Surat.SuratPermohonan.destroy','') }}/${id}`,{
               headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},
               method:'DELETE',
               body:id
           });

           const {status} = await res.json();
    
           if(status === 'success'){
             swal.fire("Success", "Surat Permohonan Berhasil Di Hapus!", "success");

             $('#datatable').DataTable().ajax.reload();
           }else{
            swal.fire("Error", "Maaf Terjadi Kesalahan!", "error");
           }
    }

    const deleteFunc = (id) => {
        swal.fire({
            title: "Konfirmasi",
            text: "Apakah anda yakin untuk menghapus surat permohonan ini?",
            icon: "warning",
            showCancelButton: true,
    
        })
          .then((result) => {
                if (result.isConfirmed) {
                    deleteData(id);
                } else if(result.dismiss === Swal.DismissReason.cancel) {
                    swal.fire("Proses Hapus Dibatalkan");
                }
            });
     
    }

    if('{{session()->has('error')}}'){
        swal.fire({
            title: 'Error',
            text: '{{session()->get('error')}}',
            icon:'error',
        });
    }

    if('{{session()->has('success')}}'){
        swal.fire({
            title: 'Sukses',
            text: '{{session()->get('success')}}',
            icon:'success',
        });
    }

</script>

@endsection