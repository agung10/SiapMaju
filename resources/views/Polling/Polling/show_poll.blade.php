@extends('layouts.master')
@section('content')
    <style type="text/css">
        #process-loading { position:fixed; width:100vw; height:100vh; z-index: 100; display:none; opacity:unset !important; }
        #process-loading img { position:fixed; left:0; top:0; right:0; bottom:0px; margin:auto; width:200px; }
        textarea { overflow-y:auto; resize:none; }
        .custom-radio label { width:100%; border-radius:3px; border:1px solid #D1D3D4; font-weight:normal; }
        .custom-radio input[type="radio"]:empty { display:none; }
        .custom-radio input[type="radio"]:empty ~ label { position:relative; line-height:2.5em; text-indent:3.25em; cursor:pointer; -webkit-user-select:none; -moz-user-select:none; -ms-user-select:none; user-select:none; }
        .custom-radio input[type="radio"]:empty ~ label:before { position:absolute; display:block; top:0; bottom:0; left:0; content:''; width:2.5em; background:#D1D3D4; border-radius:3px 0 0 3px; }
        .custom-radio input[type="radio"]:hover:not(:checked) ~ label { color: #888; }
        .custom-radio input[type="radio"]:hover:not(:checked) ~ label:before { content:'\2714'; text-indent:.9em; color:#C2C2C2; }
        .custom-radio input[type="radio"]:checked ~ label { color:#777; }
        .custom-radio input[type="radio"]:checked ~ label:before { content:'\2714'; text-indent:.9em; color:#333; background-color:#ccc; }
        .custom-radio input[type="radio"]:focus ~ label:before { box-shadow:0 0 0 3px #999; }
        .custom-radio-info input[type="radio"]:checked ~ label:before { color:#fff; background-color:#5bc0de; }
        .btn-sm { padding:.25rem .5rem; }
    </style>
    <span id="process-loading" class="text-center"><img src="{{ asset('images/loading.gif') }}"></span>
    <div class="container">
        <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
            <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <h5 class="text-dark font-weight-bold my-1 mr-5">
                            Tambah Pertanyaan Polling
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-custom gutter-b">
            <form class="form" id="form-polling" action="{{ route('Polling.Polling.store') }}" method="POST">
                {{ csrf_field() }}
                <div class="card-body" style="padding-bottom:unset;">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Nama Warga<span style="color:red;">*</span></label>
                            <select name="anggota_keluarga_id" class="form-control">
                                {!! $familyMember !!}
                            </select>
                            <span class="err-anggota-keluarga" style="color:#F64E60; font-size:.9rem; display:none;">Warga tidak boleh kosong!</span>
                        </div>
                        <div class="col-lg-6">
                            <label>Pertanyaan</label>
                            <textarea class="form-control" rows="5" disabled>{{ $data->isi_pertanyaan }}</textarea>
                            <input type="hidden" name="id_polling" value="{{ \Crypt::encrypt($data->id_polling) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="5" placeholder="Silakan isi keterangan (opsional)"></textarea>
                        </div>
                        <div class="col-lg-6">
                            <label>Pilihan Jawaban<span style="color:red;">*</span></label>
                            <div class="custom-radio">
                                {!! $answerQuestion !!}
                            </div>
                            <span class="err-radio" style="color:#F64E60; font-size:.9rem; display:none;">Pilih 1 Jawaban!</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 text-right headerSaveButtonContainer">
                    <button type="submit" class="btn btn-primary mr-5 submit">Simpan</button>
                </div>
            </form>
            <div class="card-body" style="border-top:1px solid #ebedf3; padding-bottom:unset;">
                <div class="form-group row">
                    <div class="col-lg-12">
                        <table class="table table-bordered table-checkable" id="datatable">
                            <thead>
                                <tr>
                                    <th width="5%">No.</th>
                                    <th>Nama Warga</th>
                                    <th>Pilihan</th>
                                    <th width="50px">Action</th>
                                </tr>
                            </thead>
                            {!! $getPollingResult !!}
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 text-right headerSaveButtonContainer">
                <button type="submit" class="btn btn-info mr-5 btn-finish">Selesai</button>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable').DataTable();
            $('select[name=anggota_keluarga_id]').select2({ width:'100%', placeholder:'Pilih Warga' });
            $('body').on('change', 'select[name=anggota_keluarga_id]', function() {
                const _this = $(this);
                if (_this.val()) {
                    _this.parents('#form-polling').find('.select2-container--default .select2-selection--single').removeAttr('style').end()
                        .find('span.err-anggota-keluarga').css({'display':'none'});
                }
            }).on('change', 'input.radio', function() {
                const _this = $(this);
                if (_this.val()) {
                    _this.parents('#form-polling').find('label.err-radio-label').removeAttr('style').end()
                        .find('span.err-radio').css({'display':'none'});
                }
            }).on('submit', '#form-polling', function(e) {
                e.preventDefault();
                const _this = $(this);
                const familyMember = _this.find('select[name=anggota_keluarga_id]');
                const description = _this.find('textarea[name=keterangan]');
                const radio = _this.find('input.radio:checked');
                const table = _this.parents().find('table');
                const url = _this.attr('action');
                const type = _this.attr('method');

                if ((familyMember.val()) && (radio.val())) {
                    $.ajax({
                        url, type, data:_this.serialize(),
                        beforeSend: function() {
                            $('#process-loading').css({'display':'block'});
                            $('div.container').css({'opacity':'0.2'});
                        },
                        success: function(result) {
                            $('#process-loading').css({'display':'none'});
                            $('div.container').css({'opacity':'1'});
                            Swal.fire('Berhasil!', 'Data telah disimpan!', 'success').then(() => {
                                familyMember.html(result.familyMember);
                                description.val('');
                                radio.prop('checked', false);
                                table.find('tbody').remove().end().append(result.getPollingResult);
                            });
                        },
                        error: function() {
                            Swal.fire('Error!', 'Maaf, telah terjadi kesalahan!', 'error');
                        }
                    });
                }
                else {
                    if (!(familyMember.val())) {
                        _this.find('.select2-container--default .select2-selection--single').css({'border':'1px solid #F64E60'}).end()
                            .find('span.err-anggota-keluarga').css({'display':'block'});
                    }

                    if (!(radio.val())) {
                        _this.find('label.err-radio-label').css({'border':'1px solid #F64E60'}).end()
                            .find('span.err-radio').css({'display':'block'});
                    }
                }
            }).on('click', 'button.btn-edit-poll', function() {
                const _this = $(this);
                const card = _this.parents('div.card');
                const url = _this.data('url');

                $.ajax({
                    url, type:'GET', data:{ '_token': '{{ csrf_token() }}' },
                    beforeSend: function() {
                        $('#process-loading').css({'display':'block'});
                        $('div.container').css({'opacity':'0.2'});
                    },
                    success: function(result) {
                        $('#process-loading').css({'display':'none'});
                        $('div.container').css({'opacity':'1'});
                        card.find('select[name=anggota_keluarga_id]').html(result.familyMember).end()
                            .find('textarea[name=keterangan]').html(result.description).end()
                            .find('.custom-radio').html(result.answerOption);
                        $('html, body').animate({ scrollTop:0 }, 'fast');
                    },
                    error: function() {
                        Swal.fire('Error!', 'Maaf, telah terjadi kesalahan!', 'error');
                    }
                });
            }).on('click', 'button.btn-finish', function() {
                Swal.fire('Berhasil!', 'Terima kasih telah mengikuti polling!', 'success').then(() => { window.close(); });
            });
        });
    </script>
@endsection