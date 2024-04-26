@extends('layouts.master')
@section('content')
    <style type="text/css">
        textarea { overflow-y:auto; resize:none; }
        div.this-answer { display:flex; flex-direction:column; margin-bottom:5px; }
        div.this-answer input { width:85%; margin-right:10px; }
        .btn i { padding:unset; }
        div.input-answer { display:flex; flex-direction:row; width:100%; }
        div.err-answer { font-size:.9rem; color:#F64E60; }
        #process-loading { position:fixed; width:100vw; height:100vh; z-index: 100; display:none; opacity:unset !important; }
        #process-loading img { position:fixed; left:0; top:0; right:0; bottom:0px; margin:auto; width:200px; }
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
            <form class="form" id="form-pertanyaan-tambah" action="{{ route('Polling.Pertanyaan.store') }}" method="POST">
                {{ csrf_field() }}
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-6" data-url="{{ route('Polling.Pertanyaan.getCitiesByProvince') }}">
                            <label>Provinsi<span style="color:#F64E60;">*</span></label>
                            <select name="province_id" class="form-control">
                                {!! $provinces !!}
                            </select>
                        </div>
                        <div class="col-lg-6" data-url="{{ route('Polling.Pertanyaan.getRWsByWard') }}">
                            <label>Kelurahan<span style="color:#F64E60;">*</span></label>
                            <select name="kelurahan_id" class="form-control"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6" data-url="{{ route('Polling.Pertanyaan.getSubdistrictsByCity') }}">
                            <label>Kota<span style="color:#F64E60;">*</span></label>
                            <select name="city_id" class="form-control"></select>
                        </div>
                        <div class="col-lg-6" data-url="{{ route('Polling.Pertanyaan.getRTsByRW') }}">
                            <label>RW<span style="color:#F64E60;">*</span></label>
                            <select name="rw_id" class="form-control"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6" data-url="{{ route('Polling.Pertanyaan.getWardsBySubdistrict') }}">
                            <label>Kecamatan<span style="color:#F64E60;">*</span></label>
                            <select name="subdistrict_id" class="form-control"></select>
                        </div>
                        <div class="col-lg-6">
                            <label>RT<span style="color:#F64E60;">*</span></label>
                            <select name="rt_id" class="form-control"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Penutupan<span style="color:#F64E60;">*</span></label>
                            <input type="date" name="close_date" class="form-control" />
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Isi Pertanyaan<span style="color:#F64E60;">*</span></label>
                            <textarea name="isi_pertanyaan" class="form-control" rows="5" placeholder="Masukkan Isi Pertanyaan"></textarea>
                        </div>
                        <div class="col-lg-6 row-answer">
                            <label>Pilihan Jawaban<span style="color:#F64E60;">*</span></label>
                            <div class="this-answer">
                                <div class="input-answer">
                                    <input type="text" name="isi_pilih_jawaban[]" class="form-control choice-answer" placeholder="Masukkan Isi Jawaban">
                                    <span class="btn btn-default remove-choice"><i class="fa fa-trash"></i></span>
                                </div>
                                <div class="err-answer" style="display:none;">Jawaban harus diisi !</div>
                            </div>
                            <span class="btn btn-default btn-add-answer">Tambah Jawaban</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 text-right headerSaveButtonContainer">
                    <button type="submit" class="btn btn-primary mr-5 submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\Polling\PertanyaanRequest', '#form-pertanyaan-tambah') !!}
    <script type="text/javascript">
        $(document).ready(function() {
            $('select[name=province_id]').select2({ width:'100%', placeholder:'Pilih Provinsi' });
            $('select[name=city_id]').select2({ width:'100%', placeholder:'Pilih Kota' });
            $('select[name=subdistrict_id]').select2({ width:'100%', placeholder:'Pilih Kecamatan' });
            $('select[name=kelurahan_id]').select2({ width:'100%', placeholder:'Pilih Kelurahan' });
            $('select[name=rw_id]').select2({ width:'100%', placeholder:'Pilih RW' });
            $('select[name=rt_id]').select2({ width:'100%', placeholder:'Pilih RT' });

            const getDataLocation = (url, type, data, target) => {
                $.ajax({
                    url, type, data,
                    beforeSend: function() {
                        $('#process-loading').css({'display':'block'});
                        $('div.container').css({'opacity':'0.2'});
                    },
                    success: function(result) {
                        $('#process-loading').css({'display':'none'});
                        $('div.container').css({'opacity':'1'});
                        $(`select[name=${target}]`).html(result);
                        if (target == 'city_id') { $('select[name=subdistrict_id], select[name=kelurahan_id], select[name=rw_id], select[name=rt_id]').html('<option></option>'); }
                        else if (target == 'subdistrict_id') { $('select[name=kelurahan_id], select[name=rw_id], select[name=rt_id]').html('<option></option>'); }
                        else if (target == 'kelurahan_id') { $('select[name=rw_id], select[name=rt_id]').html('<option></option>'); }
                        else if (target == 'rw_id') { $('select[name=rt_id]').html('<option></option>'); }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Maaf, telah terjadi kesalahan!', 'error');
                    }
                });
            };

            $('body').on('change', 'select[name=province_id]', function() {
                const _this = $(this);
                const _thisParent = _this.parents();
                const _url = _thisParent.data('url');
                getDataLocation(_url, 'POST', { 'id':_this.val(), '_token': '{{ csrf_token() }}' }, 'city_id');
            }).on('change', 'select[name=city_id]', function() {
                const _this = $(this);
                const _thisParent = _this.parents();
                const _url = _thisParent.data('url');
                getDataLocation(_url, 'POST', { 'id':_this.val(), '_token': '{{ csrf_token() }}' }, 'subdistrict_id');
            }).on('change', 'select[name=subdistrict_id]', function() {
                const _this = $(this);
                const _thisParent = _this.parents();
                const _url = _thisParent.data('url');
                getDataLocation(_url, 'POST', { 'id':_this.val(), '_token': '{{ csrf_token() }}' }, 'kelurahan_id');
            }).on('change', 'select[name=kelurahan_id]', function() {
                const _this = $(this);
                const _thisParent = _this.parents();
                const _url = _thisParent.data('url');
                getDataLocation(_url, 'POST', { 'id':_this.val(), '_token': '{{ csrf_token() }}' }, 'rw_id');
            }).on('change', 'select[name=rw_id]', function() {
                const _this = $(this);
                const _thisParent = _this.parents();
                const _url = _thisParent.data('url');
                getDataLocation(_url, 'POST', { 'id':_this.val(), '_token': '{{ csrf_token() }}' }, 'rt_id');
            }).on('input', 'input.choice-answer', function() {
                const _this = $(this);
                const _thisAnswer = _this.parents('.this-answer');
                const _remove = _thisAnswer.find('span.remove-choice');

                if (_this.val()) {
                    _remove.removeClass('btn-default').addClass('btn-danger');
                    $('span.btn-add-answer').removeClass('btn-default').addClass('btn-info');
                }
                else {
                    _this.blur().removeClass('is-valid');
                    if ($('span.remove-choice').length <= 1) { _remove.removeClass('btn-danger').addClass('btn-default'); }
                    $('span.btn-add-answer').removeClass('btn-info').addClass('btn-default');
                }
            }).on('click', 'span.btn-add-answer', function() {
                const _this = $(this);
                const _rowAnswer = _this.parents('.row-answer');
                const _allAnswer = _rowAnswer.find('.this-answer');
                const _clone = $(_allAnswer[_allAnswer.length - 1]).clone();
                if (_this.hasClass('btn-info')) {
                    _rowAnswer.find('input').attr('readonly', true).removeAttr('style').css({'background-color':'#80808038'}).end()
                        .find('.err-answer').css({'display':'none'});
                    _clone.find('input').removeAttr('readonly style').removeClass('is-valid').val('').end()
                        .find('.err-answer').css({'display':'none'});
                    $('.btn-add-answer').before(_clone);
                    _this.removeClass('btn-info').addClass('btn-default');
                }
            }).on('click', 'span.remove-choice', function() {
                const _this = $(this);
                const _rowAnswer = _this.parents('.row-answer');

                if (_rowAnswer.find('.this-answer').length <= 1) {
                    _this.removeClass('btn-danger').addClass('btn-default');
                    _rowAnswer.find('span.btn-add-answer').removeClass('btn-info').addClass('btn-default').end()
                        .find('input').removeClass('is-valid').val('');
                }
                else {
                    _this.parents('.this-answer').remove();
                    const _allAnswer = _rowAnswer.find('.this-answer')
                    const _lastAnswer = _allAnswer[_allAnswer.length - 1];
                    $(_lastAnswer).find('input').removeAttr('readonly style');
                    if ($(_lastAnswer).find('input').val()) { _rowAnswer.find('span.btn-add-answer').removeClass('btn-default').addClass('btn-info'); }
                    else { _rowAnswer.find('span.btn-add-answer').removeClass('btn-info').addClass('btn-default'); }
                }
            }).on('submit', 'form#form-pertanyaan-tambah', function(e) {
                e.preventDefault();
                const _this = $(this);
                const _answer = $('.this-answer');
                const answerLength = _answer.length;

                let ansInc = 0;
                _answer.each((ind, val) => {
                    if ($(val).find('input').val()) {
                        ansInc++;
                        $(val).find('input').removeAttr('style').end()
                            .find('.err-answer').css({'display':'none'});

                        if (answerLength == ansInc) {
                            _this[0].submit();
                        }
                    }
                    else {
                        $(val).find('input').css({'border':'1px solid #F64E60'}).end()
                            .find('.err-answer').css({'display':'block'});
                    }
                });
            });
        });
    </script>
@endsection