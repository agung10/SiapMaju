@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Cap RW
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
                            <span class="opacity-60 font-weight-bold">Berdasarkan alamat/lokasi yang dipilih</span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <form class="form">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="kelurahan_id" class="form-control border-0 font-weight-bold" id="kelurahan">
                                            {!! $resultKelurahan !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" id="filter"
                                        class="btn btn-light-primary font-weight-bold mt-sm-0 px-7">
                                        <i class="fas fa-search"></i>
                                        Cari Data
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
                @include('partials.buttons.add', ['text' => 'Tambah Cap RW'])
                <a href="javascript:void(0)" class="btn btn-light-primary font-weight-bold addNewData d-none ml-5">
                    <i class="fas fa-plus-square"></i>
                    Tambah Cap RW Sesuai Pencarian
                </a>
            </div>
        </div>
        <div class="card-body">
        <table class="table table-bordered table-checkable" id="datatable">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th>RW</th>
                    <th>Cap RW</th>
                    <th width="25%">Action</th>
                </tr>
            </thead>
         </table>
        </div>
    </div>
</div>
<script>
    $(function() {
        makeDataTable()

        function makeDataTable(kelurahan_id = ''){
            const route = '{{ route('Master.CapRW.dataTables') }}'
            
            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: route,
                    data:{kelurahan_id:kelurahan_id}
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', className:'text-center'},
                    {data: 'rw', name: 'rw', className:'text-center'},
                    {data: 'cap_rw', name: 'cap_rw', className:'text-center'},
                    {data: 'action', name: 'action', className:'text-center'},
                ],
                drawCallback: function( settings, start, end, max, total, pre ) {
                    const row_count = this.fnSettings().fnRecordsTotal()
                    if (row_count <= 0) {
                        $('.addNewData').removeClass('d-none')
                    }
                },
            });
            $('[data-toggle="tooltip"]').tooltip()
        }

        $('#filter').click(function(){
            var kelurahan_id = $('#kelurahan').val();

            if (kelurahan_id == '') {
                swal.fire("Informasi", "Anda belum memilih alamat pencarian!", "info");
            } else {
                $('#datatable').DataTable().destroy();
                makeDataTable(kelurahan_id);
            }
        });

        $('#reset').click(function(){
            $('select[name=kelurahan_id]').val('').trigger('change');
            
            $('#datatable').DataTable().destroy();
            makeDataTable();

            $('.addNewData').addClass('d-none')
        });

        $('.addNewData').click(function() {
            window.open("/Master/CapRW/create","_blank");
            var value = JSON.stringify({
                kelurahan_id: $('select[name=kelurahan_id]').val(),
            })
            var localStorage = window.localStorage
            localStorage.setItem("temporary_form_data", value);
        })
    });

    const storage = localStorage.getItem('success');
    if(storage){
        swal({
            title: storage,
            icon: 'success',
        });

        localStorage.removeItem('success');
    }

    const deleteData = async (id) => {
        const res = await fetch(`{{ route('Master.CapRW.destroy', '') }}/${id}`,{
            headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},
            method:'DELETE',
            body:id
        });

        const {status} = await res.json();

        if(status === 'success'){
            swal.fire("Success", "Berhasil Di Hapus!", "success");
            $('#datatable').DataTable().ajax.reload();
        }else{
            swal.fire("Error", "Maaf Terjadi Kesalahan!", "error");
        }
    }

    const deleteFunc = (id) => {
        swal.fire({
            title: "Konfirmasi",
            text: "Apakah anda yakin untuk menghapus CapRW?",
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

    jQuery(document).ready(function() {
        $('select[name=kelurahan_id]').select2({ width: '100%', placeholder: '-- Pilih Kelurahan --'})
    });
</script>

@endsection