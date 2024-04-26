@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                       Telepon Penting
                    </h5>
                </div>
            </div>
        </div>
    </div>

    @if ($isAdmin)
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select name="kelurahan_id" class="form-control border-0 font-weight-bold" id="kelurahan">
                                            {!! $resultKelurahan !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select name="rw_id" class="form-control border-0 font-weight-bold" id="rw">
                                            @if ($isAdmin) 
                                                <option></option>
                                            @elseif ($isRw) 
                                                {!! $resultRW !!}
                                            @endif
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
    @endif

    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-toolbar">
                @include('partials.buttons.add', ['text' => 'Tambah Nomor'])
                <a href="javascript:void(0)" class="btn btn-light-primary font-weight-bold addNewData d-none ml-5">
                    <i class="fas fa-plus-square"></i>
                    Tambah Data Nomor Sesuai Pencarian
                </a>
            </div>
        </div>
        <div class="card-body">
        <table class="table table-bordered table-checkable table-responsive" id="datatable">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th>Instansi </th>
                    <th>Nomor</th>
                    <th>Alamat</th>
                    <th width="25%">Action</th>
                </tr>
            </thead>
         </table>
        </div>
    </div>
</div>
<script>
    var isAdmin = '<?php echo $isAdmin; ?>';
    var isRw = '<?php echo $isRw; ?>';

    $(function() {
        makeDataTable()

        function makeDataTable(kelurahan_id = '', rw_id = ''){
            const route = '{{ route('Master.TeleponPenting.dataTables') }}'
            
            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: route,
                    data:{kelurahan_id:kelurahan_id, rw_id:rw_id}
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', className:'text-center'},
                    {data: 'nama_instansi', name: 'nama_instansi', className:'text-center'},
                    {data: 'no_tlp', name: 'no_tlp', className:'text-center'},
                    {data: 'alamat', name: 'alamat', className:'text-center'},
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
            var rw_id = $('#rw').val();

            if ((isAdmin && kelurahan_id == '')) {
                swal.fire("Informasi", "Anda belum memilih alamat pencarian!", "info");
            } else if ((isRw && rw_id == '')) {
                swal.fire("Informasi", "Anda belum memilih alamat pencarian RT!", "info");
            } else {
                $('#datatable').DataTable().destroy();
                makeDataTable(kelurahan_id, rw_id);
            }
        });

        $('#reset').click(function(){
            if (isAdmin) {
                $('select[name=kelurahan_id]').val('').trigger('change');

                $('select[name=rw_id]').val('').trigger('change');
                $('select[name=rw_id]').prop('disabled', true);
            }

            
            $('#datatable').DataTable().destroy();
            makeDataTable();

            $('.addNewData').addClass('d-none')
        });

        $('.addNewData').click(function() {
            window.open("/Master/TeleponPenting/create","_blank");
            var value = JSON.stringify({
                kelurahan_id: $('select[name=kelurahan_id]').val(),
                rw_id: $('select[name=rw_id]').val()
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
           const res = await fetch(`{{ route('Master.TeleponPenting.destroy','') }}/${id}`,{
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
            text: "Apakah anda yakin untuk menghapus data ini?",
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

    // Class Select2 definition
    var KTSelect2 = function() {
        // Private functions
        var demos = function() {
			$('select[name=kelurahan_id]').select2({ width: '100%', placeholder: '-- Pilih Kelurahan --'})
			$('select[name=rw_id]').select2({ width: '100%', placeholder: '-- Pilih RW --'})

			const spinner = $('<div id="select-spinner" class="spinner spinner-right"></div>');
            const selectKelurahan = $('#kelurahan')
            const selectRW = $('#rw')
            const selectRT = $('#rt')

            if (isAdmin) {
                selectRW.html('');
                selectRW.prop("disabled", true);
            }

            $('body').on('change', 'select[name=kelurahan_id]', async function(){
				let kelurahan = $(this).val()
                if (kelurahan) {
                    let subOption = '<option></option>';
                    let url = @json(route('DetailAlamat.getRW'));
                        url += `?kelurahanID=${ encodeURIComponent(kelurahan) }`
    
                    $(this).prop("disabled", true);
                    selectRW.parent().append(spinner);
                    selectRW.html('');
                    selectRW.prop("disabled", true);
    
                    selectRT.html('');
                    selectRT.prop("disabled", true);
    
                    const fetchRW = await fetch(url).then(res => res.json()).catch((err) => {
                        selectRW.prop("disabled", false);
                        spinner.remove()
                        Swal.fire({
                            title: 'Terjadi kesalahan dalam menghubungi server, silahkan coba lagi...',
                            icon: 'warning'
                        })
                    });
    
                    for(const data of fetchRW) {
                        subOption += `<option value="${data.rw_id}">${data.rw}</option>`;
                    }
    
                    selectRW.html(subOption);
                    selectRW.select2({ 
                        placeholder: '-- Pilih RW --', 
                        width: '100%'
                    });
                    $(this).prop("disabled", false);
                    selectRW.prop("disabled", false);
                    spinner.remove();
                }
			})
        }

        // Functions
        return {
            init: function() {
                demos();
            }
        };
    }();

    // Initialization
    jQuery(document).ready(function() {
        KTSelect2.init();
    });
</script>

@endsection