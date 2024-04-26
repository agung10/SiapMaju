@extends('layouts.master') 
@section('content')
<div class="container">
    <div style="z-index: -1;" class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Usulan Urusan
                    </h5>
                </div>
            </div>
        </div>
    </div>

    @include('partials.alert')
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-toolbar">
                @include('partials.buttons.add', ['text' => 'Tambah Usulan Urusan'])
            </div>
            <div class="float-left d-flex align-items-center">
                <a href="{{ asset('buku-panduan-musrenbang.pdf') }}" class="btn btn-success font-weight-bolder">
                    Download Buku Panduan Musrenbang
                </a>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered" id="datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Usulan</th>
                        <th>Bidang</th>
                        <th>Kegiatan</th>
                        <th>RT</th>
                        <th>RW</th>
                        <th>Ketua RW</th>
                        <th>Alamat</th>
                        <th>Jumlah</th>
                        <th>Tahun</th>
                        <th>Status Approval</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    const storage = localStorage.getItem('success');
    if(storage){
        swal({ title: storage, icon: 'success'});
        localStorage.removeItem('success');
    }

    $(document).ready(function(){
        var table = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax: "{{ route('Musrenbang.Usulan-Urusan.dataTables') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', className:'text-center', searchable: false, orderable: false},
                {data: 'jenis_usulan', name: 'jenis_usulan', className:'text-center'},
                {data: 'bidang', name: 'bidang', className:'text-center'},
                {data: 'kegiatan', name: 'kegiatan', className:'text-center'},
                {data: 'rt', name: 'rt', className:'text-center'},
                {data: 'rw', name: 'rw', className:'text-center'},
                {data: 'ketua_rw', name: 'ketua_rw', className:'text-center'},
                {data: 'alamat', name: 'usulan_urusan.alamat', className:'text-center'},
                {data: 'jumlah', name: 'usulan_urusan.jumlah', className:'text-center'},
                {data: 'tahun', name: 'usulan_urusan.tahun', className:'text-center'},
                {data: 'status_usulan', name: 'status_usulan', className:'text-center'},
                {data: 'keterangan', name: 'usulan_urusan.keterangan', className:'text-center'},
                {data: 'action', name: 'action', className:'text-center'},
                {data: 'updated_at', name: 'usulan_urusan.updated_at', className:'text-center', visible: false, searchable: false, orderable: false},
            ],
            "order": [[ 13, "desc" ]],
        });
        $('[data-toggle="tooltip"]').tooltip()
    })

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

    const deleteData = async (id) => {
        const res = await fetch(`{{ route('Musrenbang.Usulan-Urusan.destroy','') }}/${id}`,{
            headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},
            method:'DELETE',
            body:id
        });

        const {status} = await res.json();

        if(status === 'success') {
            swal.fire("Success", "Data Berhasil Di Hapus!", "success");
            $('#datatable').DataTable().ajax.reload();
        } else {
            swal.fire("Error", "Maaf Terjadi Kesalahan!", "error");
        }
    }
</script>
@endsection
