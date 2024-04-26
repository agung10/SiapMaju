@extends('layouts.master')
@section('content')
    <style type="text/css">
        #process-loading { position:fixed; width:100vw; height:100vh; z-index: 100; display:none; opacity:unset !important; }
        #process-loading img { position:fixed; left:0; top:0; right:0; bottom:0px; margin:auto; width:200px; }
        .btn { margin-bottom:3px; }
        textarea { overflow-y:auto; resize:none; }
        .modal-backdrop { z-index:-1; }
        .modal .modal-header .close span { display:unset; }
        .modal-dialog { max-width:900px; }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <span id="process-loading" class="text-center"><img src="{{ asset('images/loading.gif') }}"></span>
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
                            <th width="300">Status</th>
                            <th width="450">Aksi</th>
                        </tr>
                    </thead>
                </table>
                <div class="modal fade" id="uploadDocument" tabindex="-1" role="dialog" aria-labelledby="uploadDocumentLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('Kelurahan.Surat.dataTables') }}",
                columns: [
                    {data:'DT_RowIndex',name: 'DT_RowIndex', className:'text-center', searchable: false , orderable: false},
                    {data:'no_surat', name:'surat_permohonan.no_surat', className:'text-center'},
                    {data:'hal', name:'surat_permohonan.hal', className:'text-center'},
                    {data:'nama_lengkap', name:'surat_permohonan.nama_lengkap', className:'text-center'},
                    {data:'status', className:'text-center'},
                    {data:'action',name: 'action', className:'text-center', searchable: false , orderable: false},
                    {data:'surat_permohonan_id',name: 'surat_permohonan.surat_permohonan_id', searchable: false, visible: false},
                ],
                "order": [[ 6, "desc" ]],
            });
            $('[data-toggle="tooltip"]').tooltip();
            $('body').on('click', 'a.uploadDocument', function() {
                const _this = $(this);
                const url = _this.data('edit');
                const modal = $('div#uploadDocument');
                $.ajax({
                    url, method: 'GET',
                    beforeSend: function() {
                        $('#process-loading').css({'display':'block'});
                        $('div.container').css({'opacity':'0.2'});
                    },
                    success: function(result) {
                        $('#process-loading').css({'display':'none'});
                        $('div.container').css({'opacity':'1'});
                        modal.find('.modal-content').html(result).end().modal('show');
                        $('#summernote').summernote({
                            placeholder: 'Masukkan Isi Surat',
                            tabsize: 2,
                            height: 220,
                            toolbar: [
                                ['style', ['style']],
                                ['font', ['bold', 'underline', 'clear']],
                                ['color', ['color']],
                                ['para', ['ul', 'ol', 'paragraph']],
                                ['table', ['table']],
                                ['insert', ['link', 'picture', 'video']],
                                ['view', ['fullscreen', 'codeview', 'help']]
                            ],
                            callbacks: {
                                onChange: function(contents) {
                                    if (contents.replace(/(<([^>]+)>)/gi, '')) {
                                        $('.note-editor').css({'border':'1px solid #E4E6EF'});
                                        $('.note-editor').parents().find('.err-isi-surat').css({'display':'none'}).html('');
                                    }
                                    else {
                                        $('.note-editor').css({'border':'1px solid #F64E60'});
                                        $('.note-editor').parents().find('.err-isi-surat').css({'display':'block'}).html('<i class="fas fa-info-circle" style="font-size:1rem"></i> Isi surat harus diisi!');
                                    }
                                }
                            }
                        });
                    },
                    error: function() {
                        Swal.fire('Error!', 'Maaf, telah terjadi kesalahan!', 'error');
                    }
                });
            }).on('input', 'input[name=no_surat_kel]', function() {
                const _this = $(this);
                if (_this.val().trim()) {
                    _this.css({'border':'1px solid #E4E6EF'});
                    _this.parents().find('span.err-no-surat').css({'display':'none'}).html('');
                }
                else {
                    _this.css({'border':'1px solid #F64E60'});
                    _this.parents().find('span.err-no-surat').css({'display':'block'}).html('<i class="fas fa-info-circle" style="font-size:1rem"></i> No surat harus diisi!');
                }
            }).on('input', 'input[name=upload_surat_kelurahan]', function() {
                const _this = $(this);
                let fileExtension;
                if (_this.val()) {
                    if (_this.val().substr((_this.val().lastIndexOf('.') + 1)) == 'pdf') {
                        _this.css({'border':'1px solid #E4E6EF'});
                        _this.parents().find('span.err-upload').css({'display':'none'}).html('');
                    }
                    else {
                        _this.css({'border':'1px solid #F64E60'});
                        _this.parents().find('span.err-upload').css({'display':'block'}).html('<i class="fas fa-info-circle" style="font-size:1rem"></i> Format file surat kelurahan harus .pdf!');
                    }
                }
                else {
                    _this.css({'border':'1px solid #F64E60'});
                    _this.parents().find('span.err-upload').css({'display':'block'}).html('<i class="fas fa-info-circle" style="font-size:1rem"></i> File surat harus diisi!');
                }
            }).on('submit', 'form#upload-surat-kelurahan', function(e) {
                e.preventDefault();
                const _this = $(this);
                const method = _this.attr('method');
                const url = _this.attr('action');
                const data = new FormData(this);
                const modal = _this.parents('#uploadDocument');
                const inputNumb = _this.find('input.no-surat');
                const errMsgNumb = _this.find('span.err-no-surat');
                const textContent = _this.find('textarea.isi-surat');
                const errMsgContent = _this.find('span.err-isi-surat');
                const inputFile = _this.find('input.upload-surat');
                const errMsgFile = _this.find('span.err-upload');
                if ((inputNumb.val()) && (textContent.val().replace(/(<([^>]+)>)/gi, '')) && (inputFile.val())) {
                    let fileExtension;
                    if (inputFile.val().substr((inputFile.val().lastIndexOf('.') + 1)) == 'pdf') {
                        $.ajax({
                            method, url, data,
                            contentType: false,
                            processData: false,
                            beforeSend: function() {
                                $('#process-loading').css({'display':'block'});
                                $('div.container').css({'opacity':'0.2'});
                            },
                            success: function(result) {
                                Swal.fire('Berhasil!', 'Data telah berhasil disimpan!', 'success').then(() => {
                                    if ((result.status_upload == 5) && (result.upload_surat_kelurahan)) {
                                        const target = $('a.uploadDocument.class' + result.surat_permohonan_id).parents('td');
                                        target.find('a.btn-preview').remove();
                                        target.append(`<a class="btn btn-light-primary font-weight-bold mr-2 btn-preview" data-path="{{ asset('uploaded_files/surat_kelurahan/${result.upload_surat_kelurahan}') }}">Hasil</a>`);
                                    }
                                    $('#process-loading').css({'display':'none'});
                                    $('div.container').css({'opacity':'1'});
                                    modal.modal('hide');
                                    _this.remove();
                                });
                            },
                            error: function() {
                                Swal.fire('Error!', 'Maaf, telah terjadi kesalahan!', 'error');
                            }
                        });
                    }
                }
                else {
                    if (!inputNumb.val()) {
                        inputNumb.css({'border':'1px solid #F64E60'});
                        errMsgNumb.css({'display':'block'}).html('<i class="fas fa-info-circle" style="font-size:1rem"></i> No surat harus diisi!');
                    }
    
                    if (!textContent.val().replace(/(<([^>]+)>)/gi, '')) {
                        textContent.parents().find('.note-editor').css({'border':'1px solid #F64E60'});
                        errMsgContent.css({'display':'block'}).html('<i class="fas fa-info-circle" style="font-size:1rem"></i> Isi surat harus diisi!');
                    }
    
                    if (!inputFile.val()) {
                        inputFile.css({'border':'1px solid #F64E60'});
                        errMsgFile.css({'display':'block'}).html('<i class="fas fa-info-circle" style="font-size:1rem"></i> File surat harus diisi!');
                    }
                }
            }).on('click', 'a.btn-preview', function() {
                const _this = $(this);
                const path = _this.data('path');
                window.open(path);
            });
        });
    </script>
@endsection