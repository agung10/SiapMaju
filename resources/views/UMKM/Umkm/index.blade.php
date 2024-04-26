@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        UMKM (Usaha Mikro, Kecil, dan Menengah)
                    </h5>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-toolbar">
                @include('partials.buttons.add', ['text' => 'Tambah UMKM'])
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-checkable table-responsive" id="datatable">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th>UMKM</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Aktif</th>
                        <th>Disetujui</th>
                        <th width="25%">Action</th>
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
            ajax: "{{ route('UMKM.Umkm.dataTables') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', className:'text-center', searchable: false , orderable: false},
                {data: 'image', name: 'umkm.image', className:'text-center'},
                {data: 'nama', name: 'umkm.nama', className:'text-center'},
                {data: 'deskripsi', name: 'umkm.deskripsi', className:'text-center'},
                {data: 'aktif', name: 'umkm.aktif', className:'text-center'},
                {data: 'disetujui', name: 'umkm.disetujui', className:'text-center'},
                {data: 'action', name: 'action', className:'text-center', searchable: false , orderable: false},
                {data: 'updated_at', name: 'umkm.updated_at', searchable: false, visible: false },
            ],
            "drawCallback": function(settings) {
                $('[data-toggle="tooltip"]').tooltip()
                $('.popup-cover').magnificPopup({
                    type:'image',
                    gallery:{
                        enabled:true
                    }
                })
                addEvent()
            },        
            "order": [[ 7, "desc" ]],
        });
    });

    function addEvent(){ 
        document.querySelectorAll('.tambahProduk').forEach(el => {
            el.addEventListener('click',(e) => {
                e.preventDefault()
                tambahProdukHandler(e)
            })
        })
    }

    const tambahProdukHandler = (e) => {
        const umkm_id = e.currentTarget.getAttribute('data-id')
        const url = `{{route('UMKM.Produk.create')}}`

        console.log(umkm_id, url);
        localStorage.setItem('umkm_id',umkm_id)
        window.open(url,'_blank')
    }
</script>
@include('partials.datatable-delete', ['text' => __('umkm'), 'table' => '#datatable'])
@endsection