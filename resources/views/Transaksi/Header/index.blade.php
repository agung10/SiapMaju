@extends('layouts.master')

@section('content')

<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                     Transaksi Kegiatan
                    </h5>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-toolbar">
    {{view('partials.buttons.datatable',['create' => ['route' => route('Transaksi.Header.create'),
                                                      'name' => 'Transaksi Kegiatan'
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

    $(document).ready(function(){
        var table = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax: "{{ route('Transaksi.Header.dataTables') }}",
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
    })

    function dataTableCallback(){
        addBlankTarget()
    }

    const deleteData = async (id) => {
           const res = await fetch(`{{ route('Transaksi.Header.destroy','') }}/${id}`,{
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