@extends('layouts.master')

@section('content')

<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Transaksi Kegiatan DKM
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column mb-5">
        <div class="d-flex align-items-md-center mb-2 flex-column flex-md-row">
            <div class="bg-white rounded p-4 w-100">
                <div class="col-12 mt-5">
                    <div class="d-flex align-items-sm-end flex-column flex-sm-row mb-3">
                        <h2 class="d-flex align-items-center mr-5 mb-0">Pencarian</h2>
                        <span class="mx-1" style="font-weight: 500;">Berdasarkan Bulan & Tahun yang dipilih</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-12 mb-lg-0 mb-5">
                        <select name="months_option" class="form-control form-control-solid-bg" data-control="select2" data-placeholder="-- Pilih Bulan --">
                            <option></option>
                            @foreach($months as $key => $month)
                                <option value="{{ $key }}" {{ $key == date('m') ? 'selected' : '' }}>
                                    {{ $month }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 col-12 mb-lg-0 mb-5">
                        <select name="years_option" class="form-control form-control-solid-bg" data-control="select2" data-placeholder="-- Pilih Tahun --">
                            <option></option>
                            @foreach($years as $key =>  $year)
                                <option value="{{ $key }}" {{ $key == date('Y') ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 col-12">
                        <button type="button" id="filter" class="btn btn-light-primary w-100">Cari Data</button>
                    </div>
                    {{-- <div class="col-lg-2 col-6">
                        <button type="button" id="reset" class="btn btn-light-info w-100">Reset</button>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-toolbar">
                {{view('partials.buttons.datatable',['create' => ['route' => route('Transaksi.DKM.create'),
                'name' => 'Transaksi Kegiatan DKM'
                ]
                ])
                }}
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-checkable" id="datatable">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th>Transaksi</th>
                        <th>Kategori Kegiatan</th>
                        <th>No Pendaftaran</th>
                        <th>Keluarga</th>
                        <th>Action</th>
                        <th class="d-none">Tgl Approval</th>
                        <th class="d-none">Updated At</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
    if('{{session()->has('success')}}'){
                swal({
                    title: 'Success',
                    text: '{{session()->get('success')}}',
                    icon:'success',
                });
            }

    window.addEventListener('DOMContentLoaded',event => {
		const successMsg = window.localStorage.getItem('finishInput')

		if(successMsg){
			Swal.fire('Sukses',`${successMsg}`,'success')
		}

		window.localStorage.removeItem('finishInput')

		
	})

    $('select[name=months_option]').select2({ width: '100%', placeholder: '-- Pilih Bulan --'})
    $('select[name=years_option]').select2({ width: '100%', placeholder: '-- Pilih Tahun --'})

    $(function() {
        let currentMonth = new Date().getMonth() + 1;
        let currentYear = new Date().getFullYear();
        makeDataTable(currentMonth, currentYear);

        function makeDataTable(month = '', year = '') {
            const route = '{{ route('Transaksi.DKM.dataTables') }}'

            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive:true,
                ajax: {
                    url: route,
                    data: { monthSelected: month, yearSelected: year }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', className:'text-center', searchable: false , orderable: false},
                    {data: 'nama_transaksi', name: 'transaksi.nama_transaksi', className:'text-center'},
                    {data: 'nama_kat_kegiatan', name: 'kat_kegiatan.nama_kat_kegiatan', className:'text-center'},
                    {data: 'no_pendaftaran', name: 'header_trx_kegiatan.no_pendaftaran', className:'text-center'},
                    {data: 'nama', name: 'anggota_keluarga.nama', className:'text-center'},
                    {data: 'action', name: 'action', className:'text-center', searchable: false , orderable: false},
                    {data: 'tgl_approval', name: 'header_trx_kegiatan.tgl_approval', searchable: false, visible: false },
                    {data: 'updated_at', name: 'header_trx_kegiatan.updated_at', searchable: false, visible: false },
                ],
                "order": [[ 6, "asc" ], [ 7, 'desc' ]],
                drawCallback: () => dataTableCallback()
            });
            $('[data-toggle="tooltip"]').tooltip()
        }

        $('#filter').click(function(){
            let monthOpt = $('select[name=months_option]').val()
            let yearOpt = $('select[name=years_option]').val()

            if (monthOpt == '') {
                swal.fire("Informasi", "Anda belum memilih bulan / tahun pencarian!", "info");
            } else {
                $('#datatable').DataTable().destroy();
                makeDataTable(monthOpt, yearOpt);
            }
        });

        // $('#reset').click(function(){
        //     $('select[name=months_option]').val(new Date().getMonth() + 1).trigger('change');
        //     $('select[name=years_option]').val(new Date().getFullYear()).trigger('change');
            
        //     $('#datatable').DataTable().destroy();
        //     makeDataTable();
        // });
    })

    function dataTableCallback(){
        addBlankTarget()
    }

    const deleteData = async (id) => {
           const res = await fetch(`{{ route('Transaksi.DKM.destroy','') }}/${id}`,{
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
            text: "Apakah anda yakin untuk menghapus?",
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

    function addBlankTarget(){
        document.querySelectorAll('.btn-custom').forEach(node => {
            const btnName = node.innerText
            
            if(btnName == 'Print'){
                node.setAttribute('target','_blank')
            }
        })
    }
</script>

@endsection