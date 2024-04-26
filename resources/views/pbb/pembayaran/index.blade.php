@extends('layouts.master')

@section('content')
<div class="container">
    <div class="d-flex flex-column mb-5">
        <div class="d-flex align-items-md-center mb-2 flex-column flex-md-row">
            <div class="bg-white rounded p-4 d-flex flex-grow-1 flex-sm-grow-0 w-100">
                <div class="row px-sm-5">
                    <div class="col-md-12 mt-5">
                        <div class="d-flex align-items-sm-end flex-column flex-sm-row mb-3">
                            <h2 class="d-flex align-items-center mr-5 mb-0">Pencarian</h2>
                            <span class="opacity-60 font-weight-bold">Berdasarkan tahun pajak</span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <select class="form-control" name="tahun_pajak">
                                        <option></option>
                                        @foreach($tahun_pajak as $val)
                                        <option value="{{ $val->tahun_pajak }}"> Tahun {{ $val->tahun_pajak }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-light-primary btnReset"><i class="fas fa-redo-alt"></i> Reset
                                    Tahun</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.breadcumb')
    <div class="card card-custom gutter-b">
        <div class="card-body">
            @include('partials.alert')
            <table class="table table-bordered" id="datatable">
                <thead>
                    <tr>
                        <th width="3%">No</th>
                        <th>Blok</th>
                        <th>Penerima</th>
                        <th>NOP</th>
                        <th>Tanggal Terima</th>
                        <th>Tahun Pajak</th>
                        <th>Tanggal Bayar</th>
                        <th>Status</th>
                        <th width="20%">Action</th>
                        <th class="d-none">Updated At</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    $(function() {
        makeDataTable()

        $('select[name=tahun_pajak]').on('change',() => {
            const tahunPajak = $('select[name=tahun_pajak]').val()
            destroyDataTable()
            makeDataTable(tahunPajak)
        })

        document.querySelector('.btnReset').addEventListener('click',() => {
            $('select[name=tahun_pajak]').val('').trigger('change')
            destroyDataTable()
            makeDataTable()
        })

        function makeDataTable(tahunPajak) {
            const route = tahunPajak ? `{{ route('pbb.pembayaran.dataTables.filtered','') }}/${tahunPajak}`
                                : '{{ route('pbb.pembayaran.dataTables') }}'

            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: route,
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', className:'text-center', searchable: false, sortable: false },
                    {data: 'nama_blok', name: 'blok.nama_blok', className:'text-center'},
                    {data: 'nama_warga', name: 'anggota_keluarga.nama', className:'text-center'},
                    {data: 'nop', name: 'nop', className:'text-center'},
                    {data: 'tgl_terima', name: 'tgl_terima', className:'text-center'},
                    {data: 'tahun_pajak', name: 'tahun_pajak', className:'text-center'},
                    {data: 'tgl_bayar', name: 'tgl_bayar', className:'text-center'},
                    {data: 'status', name: 'status', className:'text-center', searchable: false, sortable: false },
                    {data: 'action', name: 'action', className:'text-center', searchable: false, sortable: false },
                    {data: 'updated_at', name: 'updated_at', searchable: false, visible: false },
                ],
                "order": [[ 9, "desc" ]], // updated_at descending
            });
        }
    });

    function destroyDataTable(){
        $('#datatable').DataTable().destroy()
    }    

    $(document).ready(function() {
		$('select[name=tahun_pajak]').select2({ placeholder: '-- Pilih Tahun Pajak --', width: '100%' });
	});
</script>

@endsection