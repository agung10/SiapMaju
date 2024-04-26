@extends('layouts.master')
@section('content')
    @include('Kelurahan.Kepala-Seksi.css')
    <div class="container">
        <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
            <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <h5 class="text-dark font-weight-bold my-1 mr-5">
                            Surat
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap py-3">
                <div class="card-toolbar">
                    <form class="search-surat-container">
                        <input type="text" placeholder="Ketikan no Surat" id="searchSurat">
                        <button type="submit" class="search-submit"><i class="fas fa-search"></i></button>
                    </form>
                    <!-- <button type="submit" class="btn-qrcode"><i class="fas fa-qrcode"></i></button> -->
                    <button type="submit" class="btn-reset"><i class="fas fa-undo-alt"></i></button>
                </div>
            </div>
            <div class="card-body" id="datatablenosearch">
                <table class="table table-bordered table-checkable" id="dataTablesNoSearch">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th width="300">No Surat</th>
                            <th width="300">Jenis Permohonan</th>
                            <th width="300">Nama Pemohon</th>
                            <th width="300">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="card-body" style="display:none;" id="datatablesearch">
                <table class="table table-bordered table-checkable" id="datatable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th width="300">No Surat</th>
                            <th width="300">Jenis Permohonan</th>
                            <th width="300">Nama Pemohon</th>
                            <th width="300">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    {{-- <div class="modal" id="qrcodemodal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">QRCODE Scanner</h5>
                    <button type="button" class="btn-qrcode" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="modal-body qrscanner-container">
                    <div class="select-camera-container">
                        <div class="camera">Rear Camera</div>
                        <div class="camera">Back Camera</div>
                    </div>
                    <video id="qrscanner"></video>
                </div>
            </div>
        </div>
    </div> --}}
    <script type="text/javascript">
        // $(document).ready(function() {
        //     (function() {
        //         let scanner = new Instascan.Scanner({ video: document.getElementById('qrscanner') });
        //         const startCamera = (cameras) => {
        //             let cameraIndex = 0;
        //             if (cameras.length < 2) return scanner.start(cameras[cameraIndex]);
        //             document.querySelector('.select-camera-container').style.display = 'flex';
        //             document.querySelectorAll('.camera').forEach((node, i) => {
        //                 node.addEventListener('click', function() {
        //                     cameraIndex = i;
        //                     return scanner.start(cameras[cameraIndex]);
        //                 });
        //             });
        //             return scanner.start(cameras[cameraIndex]);
        //         }

        //         scanner.addListener('scan', function(content) {
        //             window.open(content, '_blank').focus();
        //             $('#qrcodemodal').modal('hide');
        //         });

        //         Instascan.Camera.getCameras().then(function(cameras) {
        //             if (cameras.length > 0) {
        //                 startCamera(cameras);
        //             }
        //             else {
        //                 console.error('No cameras found.');
        //             }
        //         }).catch(function (e) {
        //             console.error(e);
        //         });
        //     })();
        //     document.querySelector('.btn-qrcode').addEventListener('click', function() { $('#qrcodemodal').modal('show'); });
        //     $('[data-toggle="tooltip"]').tooltip();
        // });

        $(document).ready(function(){
            var table = $('#dataTablesNoSearch').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('Kelurahan.Kepala-Seksi.dataTablesKepalaSeksiNoSearch') }}",
                columns: [
                    {data:'DT_RowIndex', name:'DT_RowIndex', className:'text-center', searchable: false , orderable: false},
                    {data:'no_surat', name:'surat_permohonan.no_surat', className:'text-center'},
                    {data:'hal', name:'surat_permohonan.hal', className:'text-center'},
                    {data:'nama_lengkap', name:'surat_permohonan.nama_lengkap', className:'text-center'},
                    {data:'action', name:'action', className:'text-center', searchable: false , orderable: false},
                    {data:'surat_permohonan_id', name: 'surat_permohonan.surat_permohonan_id', searchable: false, visible: false},
                ],
                "order": [[ 5, "desc" ]],
            });
            $('[data-toggle="tooltip"]').tooltip()
        })

        $(".btn-reset").click(function(){
            $('#searchSurat').val('').trigger('change');

            document.querySelector('#datatablesearch').style.display = 'none';
            document.querySelector('#datatablenosearch').style.display = '';
        });
        
        $(document).ready(function(){
            $('body').on('submit', '.search-surat-container', function(e) {
                e.preventDefault();
                const _this = $(this);
                const numb = _this.find('input').val();

                document.querySelector('#datatablenosearch').style.display = 'none';
                document.querySelector('#datatablesearch').style.display = '';
                
                $('#datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: '{{ route('Kelurahan.Kepala-Seksi.dataTables') }}',
                        dataType: 'json',
                        type: 'GET',
                        data: { numb, '_token': '{{ csrf_token() }}' },
                        complete: function(data) {
                            $('#datatable').css({'display':''});
                        }
                        
                    },
                    columns: [
                        {data:'DT_RowIndex', name:'DT_RowIndex', className:'text-center'},
                        {data:'no_surat', name:'no_surat', className:'text-center'},
                        {data:'hal', name:'hal', className:'text-center'},
                        {data:'nama_lengkap', name:'nama_lengkap', className:'text-center'},
                        {data:'action', name:'action', className:'text-center'},
                    ]
                });
            });

            if ('{{ session()->has('success') }}') {
                swal.fire({
                    title: 'Success',
                    text: '{{session()->get('success')}}',
                    icon: 'success',
                });
            }
        })
    </script>
@endsection