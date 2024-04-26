@extends('layouts.master')

@section('content')
    <style type="text/css">
        #process-loading { position:fixed; width:100vw; height:100vh; z-index: 100; display:none; opacity:unset !important; }
        #process-loading img { position:fixed; left:0; top:0; right:0; bottom:0px; margin:auto; width:200px; }
        .lampiran { cursor:pointer; }
        .switch input:empty ~ span { height:25px; }
        .switch input:checked ~ span:after { margin-left:29px; }
        .switch input:empty ~ span:after { height:19px; width:21px; }
        textarea { overflow-y:auto; resize:none; }
        .btn-approve-lurah { background-color:#DFAA00; padding:0.5rem; border:none; cursor:pointer; font-weight:bold; }
        .btn-approve-lurah:hover { background-color:#fcbd00; transform:scale(1.1,1.1); }
        div.card-footer { padding:2rem 0; }
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
                            Detail Surat Permohonan
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="form-surat-container">
                    <form id="helpdesk" method="post" action="{{ route('Kelurahan.SuratMasuk.update', \Crypt::encryptString($data->surat_permohonan_id)) }}">
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}
                        <div class="row">
                            <h4>Form Surat Permohonan</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Warga</label>
                                    @if(\Auth::user()->is_admin == true)
                                    <select class="form-control warga" name="anggota_keluarga_id" disabled>
                                        {!! $selectNamaWarga !!}
                                    </select>
                                    @else
                                    <input type="text" name="nama_lengkap" class="form-control"  value="{{ $data->nama_lengkap }}" disabled/>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jenis Surat</label>
                                    <select class="form-control jenis_surat" name="jenis_surat_id" disabled>
                                        {!! $jenisSurat !!}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="form-control"  value="{{ $data->tempat_lahir }}" disabled/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Lahir</label>
                                    <input type="date" name="tgl_lahir" class="form-control" value="{{ $data->tgl_lahir }}" disabled/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Bangsa</label>
                                    <input type="text" name="bangsa" class="form-control" value="{{ $data->bangsa }}" disabled/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status Pernikahan</label>
                                    <select class="form-control status_pernikahan" name="status_pernikahan_id" disabled>
                                    {!! $pernikahan !!}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Agama</label>
                                    <select class="form-control agama" name="agama_id" disabled>
                                        {!! $agama !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Pekerjaan</label>
                                    <input type="text" name="pekerjaan" class="form-control form-control" value="{{ $data->pekerjaan }}" disabled />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No KK</label>
                                    <input type="text" name="no_kk" class="form-control no_kk" value="{{ $data->no_kk }}" disabled/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No KTP</label>
                                    <input type="text" name="no_ktp" class="form-control" value="{{ $data->no_ktp }}" disabled/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Permohonan</label>
                                    <input type="date" name="tgl_permohonan" class="form-control" value="{{ $data->tgl_permohonan }}" disabled/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Keperluan Pembuatan</label>
                                    <textarea class="form-control" disabled>{{ $data->keperluan ? $data->keperluan : '-' }}</textarea>
                                </div>
                            </div>
                        </div>
                        @if ($data->lampiran1 || $data->lampiran2 || $data->lampiran3) 
                            <div class="row">
                                <h4>Lampiran</h4>
                            </div>
                            <div class="row lampiran-container">
                                @if ($data->lampiran1)
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Lampiran 1</label>
                                            <a href="{{ asset('uploaded_files/surat/'.$data->lampiran1) }}" download>
                                                <input class="lampiran form-control" type="text" name="lampiran1" value="{{ $data->lampiran1 }}" disabled />
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                @if ($data->lampiran2)
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Lampiran 2</label>
                                            <a href="{{ asset('uploaded_files/surat/'.$data->lampiran2) }}" download>
                                                <input class="lampiran form-control" type="text" name="lampiran2" value="{{ $data->lampiran2 }}" disabled />
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                @if ($data->lampiran3)
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Lampiran 3</label>
                                            <a href="{{ asset('uploaded_files/surat/'.$data->lampiran3) }}" download>
                                                <input class="lampiran form-control" type="text" name="lampiran3"
                                                    value="{{ $data->lampiran3 }}" disabled />
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @elseif (count($lampiranSurat) > 0) 
                            <div class="row">
                                <h4>Lampiran</h4>
                            </div>
                            <div class="row lampiran-container">
                                @foreach ($lampiranSurat as $res)
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ $res->nama_lampiran }}</label>
                                            <a href="{{ asset('uploaded_files/surat/'.$res->upload_lampiran) }}" download>
                                                <input class="lampiran form-control" type="text" value="{{ $res->upload_lampiran }}" disabled />
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>  
                        @endif
                        <input type="hidden" value="{{ $data->rt_id }}">
                        <input type="hidden" value="{{ $data->rw_id }}">
                        <input type="hidden" value="{{ $data->nama_lengkap }}">
                        <input type="hidden" value="{{ $data->alamat }}">
                        <input type="hidden" value="{{ $data->hal }}">
                        <input type="hidden" value="{{ $data->lampiran }}">
                        <input type="hidden" class="surat_permohonan_id" value="{{ $data->surat_permohonan_id }}">
                        <div class="row">
                            <h4>Catatan</h4>
                        </div>
                        <div class="row row-catatan">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Catatan Sebelumnya</label>
                                    <textarea class="form-control" rows="9" style="margin-top:35px;" disabled>{{ strip_tags($data->catatan_kelurahan) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Catatan?</label>
                                    <label class="switch">
                                        <input type="checkbox" class="catatan">
                                        <span class="slider round"></span>
                                    </label>
                                    <textarea name="catatan_kelurahan" class="form-control" rows="9" placeholder="Tulis Catatan Disini" disabled></textarea>
                                    <span class="err-catatan" style="font-size:.9em; color:#F64E60; display:none;">Catatan Wajib diisi!</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-warning btn-submit">Approve Kelurahan</button>
                            <a class="btn btn-warning letterContent" data-toggle="modal">Isi Surat</a>
                            <a href="{{ route('Kelurahan.Surat.previewSurat', \Crypt::encryptString($data->surat_permohonan_id)) }}" class="btn btn-warning" target="_blank">Preview Surat Permohonan</a>
                            <button type="reset" onClick='goBack()' class="btn btn-secondary">Back</button>
                        </div>
                        <div class="modal" id="letterContent" tabindex="-1" role="dialog" aria-labelledby="letterContentLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="letterContentLabel">Buat Isi Surat</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>No Surat</label>
                                                <input type="text" name="no_surat_kel" class="form-control" value="{{ $data->no_surat_kel ?? '' }}" placeholder="Masukkan No Surat" />
                                                <input type="hidden" class="old-letter-numb" value="{{ $data->no_surat_kel ?? '' }}" />
                                            </div>
                                            <div class="form-group">
                                                <label>Isi Surat</label>
                                                <textarea name="isi_surat" id="summernote">{{ $data->isi_surat ?? '' }}</textarea>
                                                <textarea class="old-letter-content" style="display:none;">{{ $data->isi_surat ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                        <a data-url="{{ route('Kelurahan.Surat.previewSuratKelurahan', \Crypt::encryptString($data->surat_permohonan_id)) }}" class="btn btn-warning btn-preview">Preview Surat Kelurahan</a>
                                        <button type="button" class="btn btn-primary btn-submit-modal">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
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
                ]
            });

            $('body').on('hide.bs.modal', '#letterContent', function() {
                const _this = $(this);
                const letterNumb = _this.find('input[name=no_surat_kel]').val();
                const letterContent = _this.find('textarea[name=isi_surat]').val();
                const oldLetterNumb = _this.find('input.old-letter-numb').val();
                const oldLetterContent = _this.find('textarea.old-letter-content').val();
                if ((letterNumb.trim() != oldLetterNumb) || (letterContent != oldLetterContent)) {
                    storeLetterContent({ letterNumb, letterContent, '_token': '{{ csrf_token() }}' });
                }
            });

            const storeLetterContent = ((data) => {
                $.ajax({
                    url: '{{ route('Kelurahan.SuratMasuk.storeLetterContent', \Crypt::encryptString($data->surat_permohonan_id)) }}',
                    type: 'POST', data,
                    beforeSend: function() {
                        $('#process-loading').css({'display':'block'});
                        $('div.container').css({'opacity':'0.2'});
                    },
                    success: function(result) {
                        $('#process-loading').css({'display':'none'});
                        $('div.container').css({'opacity':'1'});
                        Swal.fire('Berhasil!', 'Isi surat telah disimpan!', 'success').then(() => {
                            $('input.old-letter-numb').val(data.letterNumb);
                            $('textarea.old-letter-content').val(data.letterContent);
                            $('#letterContent').modal('hide');
                        });
                    },
                    error: function() {
                        Swal.fire('Error!', 'Maaf, telah terjadi kesalahan!', 'error');
                    }
                });
            });
            const _formSubmit = $('form#helpdesk');
            $('body').on('change', 'input.catatan', function() {
                const _this = $(this);
                const _thisBlock = _this.parents('.row-catatan');
                if (_this.is(':checked')) {
                    _thisBlock.find('textarea[name=catatan_kelurahan]').addClass('required').prop('disabled', false).css({'border':'1px solid #F64E60'}).end()
                        .find('span.err-catatan').css({'display':'block'});
                    _formSubmit.find('button.btn-submit').removeClass('btn-warning').addClass('btn-danger').text('Kirim Catatan');
                }
                else {
                    _thisBlock.find('textarea[name=catatan_kelurahan]').removeClass('required').removeAttr('style').prop('disabled', true).end()
                        .find('span.err-catatan').css({'display':'none'});
                    _formSubmit.find('button.btn-submit').removeClass('btn-danger').addClass('btn-warning').text('Approve Kelurahan');
                }
            }).on('input', 'textarea[name=catatan_kelurahan]', function() {
                const _this = $(this);
                if (_this.val().trim()) {
                    $(this).removeClass('required').removeAttr('style');
                    $('span.err-catatan').css({'display':'none'});
                }
                else {
                    $(this).addClass('required').css({'border':'1px solid #F64E60'});
                    $('span.err-catatan').css({'display':'block'});
                }
            }).on('click', 'a.btn-preview', function() {
                const _this = $(this);
                const nextURL = _this.data('url');
                const letterModal = _this.parents('#letterContent');
                const letterNumb = letterModal.find('input[name=no_surat_kel]').val();
                const letterContent = letterModal.find('textarea[name=isi_surat]').val();
                const oldLetterNumb = letterModal.find('input.old-letter-numb').val();
                const oldLetterContent = letterModal.find('textarea.old-letter-content').val();
                if ((letterNumb.trim() != oldLetterNumb) || (letterContent != oldLetterContent)) {
                    storeLetterContent({ letterNumb, letterContent, '_token': '{{ csrf_token() }}' });
                }
                window.open(nextURL, '_blank');
            }).on('submit', 'form#helpdesk', function(e) {
                e.preventDefault();
                const _thisForm = $(this);
                const note = _thisForm.find('textarea[name=catatan_kelurahan]');
                const letterNumb = _thisForm.find('input[name=no_surat_kel]');
                const letterContent = _thisForm.find('textarea[name=isi_surat]');

                if (!note.hasClass('required')) {
                    if (note.val().trim()) {
                        _thisForm[0].submit();
                    }
                    else {
                        if ((letterNumb.val().trim()) && (letterContent.val().trim())) {
                            Swal.fire({
                                title: 'Apakah data sudah benar?',
                                text: 'Surat yang disetujui tidak dapat dibatalkan',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ya, menyetujui!',
                                cancelButtonColor: '#d33',
                                cancelButtonText: 'Batalkan!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    _thisForm[0].submit();
                                }
                            });
                        }
                        else {
                            Swal.fire('Error!', 'Maaf, Isi Surat Belum Dibuat!', 'error');
                        }
                    }
                }
            }).on('click', 'a.letterContent', function() {
                $('#letterContent').modal('show');
            }).on('click', 'button.btn-submit-modal', function() {
                const _this = $(this);
                const content = _this.parents('.modal-content');
                const letterNumb = content.find('input').val();
                const letterContent = content.find('textarea').val();
                storeLetterContent({ letterNumb, letterContent, '_token': '{{ csrf_token() }}' });
            });
        });
    </script>
@endsection