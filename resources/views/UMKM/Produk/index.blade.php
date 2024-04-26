@extends('layouts.master')

@section('content')
@include('UMKM.Produk.css')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Produk
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column mb-5">
        <div class="d-flex align-items-md-center mb-2 flex-column flex-md-row">
            <div class="bg-white rounded p-4 d-flex flex-grow-1 flex-sm-grow-0 w-100">
                <div class="row px-sm-5">
                    <div class="col-md-12 mt-5">
                        <div class="d-flex align-items-sm-end flex-column flex-sm-row mb-3">
                            <h2 class="d-flex align-items-center mr-5 mb-0">Pencarian</h2>
                            <span class="opacity-60 font-weight-bold">Berdasarkan nama umkm yang dipilih</span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <form class="form">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <select class="form-control" name="umkm_id" id="umkm">
                                            {!! $resultUmkm !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" id="filter"
                                        class="btn btn-light-primary font-weight-bold mt-sm-0 px-7">
                                        <i class="fas fa-search"></i>
                                        Cari UMKM
                                    </button>

                                    <button type="button" id="reset" class="btn btn-default mt-sm-0">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-toolbar">
                @include('partials.buttons.add', ['text' => 'Tambah Produk'])
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-checkable" id="datatable">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th>Produk</th>
                        <th>Nama</th>
                        <th>UMKM</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aktif</th>
                        <th>Siap Dipesan</th>
                        <th width="25%">Action</th>
                        <th class="d-none">update</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    $(function() {
        makeDataTable()

        function makeDataTable(umkm_id = ''){
            const route = '{{ route('UMKM.Produk.dataTables') }}'
            
            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: route,
                    data:{umkm_id:umkm_id}
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', className:'text-center', searchable: false , orderable: false},
                    {data: 'image', name: 'umkm_produk.image', className:'text-center'},
                    {data: 'nama', name: 'umkm_produk.nama', className:'text-center'},
                    {data: 'nama_umkm', name: 'umkm.nama', className:'text-center'},
                    {data: 'harga', name: 'umkm_produk.harga', className:'text-center'},
                    {data: 'stok', name: 'umkm_produk.stok', className:'text-center'},
                    {data: 'aktif', name: 'umkm_produk.aktif', className:'text-center'},
                    {data: 'siap_dipesan', name: 'umkm_produk.siap_dipesan', className:'text-center'},
                    {data: 'action', name: 'action', className:'text-center', searchable: false , orderable: false},
                    {data: 'updated_at', name: 'umkm_produk.updated_at', searchable: false, visible: false},
                ],
                drawCallback: function( settings, start, end, max, total, pre ) {
                    const row_count = this.fnSettings().fnRecordsTotal()
                    if (row_count <= 0) {
                        $('.addNewData').removeClass('d-none')
                    }
                },
                "order": [[ 9, "desc" ]],
            });
            $('[data-toggle="tooltip"]').tooltip()
        }

        $('#filter').click(function(){
            var umkm_id = $('#umkm').val();

            if ((umkm_id == '')) {
                swal.fire("Informasi", "Anda belum memilih nama UMKM!", "info");
            } else {
                $('#datatable').DataTable().destroy();
                makeDataTable(umkm_id);
            }
        });

        $('#reset').click(function(){
            $('select[name=umkm_id]').val('').trigger('change');

            $('#datatable').DataTable().destroy();
            makeDataTable();

            $('.addNewData').addClass('d-none')
        });
    });
    
    const storage = localStorage.getItem('success');

    if(storage){
        swal({
            title: storage,
            icon: 'success',
        });

        localStorage.removeItem('success');
    }

    // Class Select2 definition
	var KTSelect2 = function() {
	return {
			init: function() {
				$('select[name=umkm_id]').select2({ placeholder: '-- Pilih UMKM --', width: '100%' });
			}
		};
	}();

	// Initialization
	jQuery(document).ready(function() {
		KTSelect2.init();
	});
</script>
@include('partials.datatable-delete', ['text' => __('produk'), 'table' => '#datatable'])
@endsection