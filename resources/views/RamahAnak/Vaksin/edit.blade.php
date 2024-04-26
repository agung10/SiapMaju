@extends('layouts.master')
@section('content')
    <style type="text/css">
        .switch input:empty ~ span { height:25px; }
        .switch input:checked ~ span:after { margin-left:29px; }
        .switch input:empty ~ span:after { height:19px; width:21px; }
        textarea { overflow-y:auto; resize:none; }
    </style>
    <div class="container">
        <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
            <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <h5 class="text-dark font-weight-bold my-1 mr-5">
                            Ubah Vaksin
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-custom gutter-b">
            <form id="headerForm" action="{{ route('RamahAnak.Vaksin.update', ((!empty($data)) ? (\Crypt::encrypt($data->id_vaksin)) : (''))) }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="card-body row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Vaksin<span class="text-danger">*</span></label>
                            <input type="text" name="nama_vaksin" class="form-control" value="{{ ((!empty($data)) ? ($data->nama_vaksin) : ('')) }}" placeholder="Masukkan Nama Vaksin" />
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="5" placeholder="Masukkan Keterangan (opsional)">{{ ((!empty($data)) ? ($data->keterangan) : ('')) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Wajib?</label>
                            <label class="switch">
                                <input type="checkbox" name="wajib" {{ ((!empty($data)) ? (($data->wajib) ? ('checked') : ('')) : ('')) }} class="form-control" data-label="Wajib">
                                <span class="slider round"></span>
                                <code>{{ ((!empty($data)) ? (($data->wajib) ? ('Wajib') : ('Tidak Wajib')) : ('')) }}</code>
                            </label>
                        </div>
                        <div class="form-group">
                            <label>Status?</label>
                            <label class="switch">
                                <input type="checkbox" name="status_aktif" {{ ((!empty($data)) ? (($data->status_aktif) ? ('checked') : ('')) : ('')) }} class="form-control" data-label="Aktif">
                                <span class="slider round"></span>
                                <code>{{ ((!empty($data)) ? (($data->wajib) ? ('Aktif') : ('Tidak Aktif')) : ('')) }}</code>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    @include('partials.buttons.submit')
                </div>
            </form>
        </div>
    </div>
    {!! JsValidator::formRequest('App\Http\Requests\RamahAnak\VaksinRequest', '#headerForm') !!}
    <script type="text/javascript">
        $(document).ready(function() {
            $('body').on('change', 'label', function() {
                const _this = $(this);
                const thisInput = _this.find('input');
                const thisLabel = thisInput.data('label');
                const thisCode = _this.find('code');

                if (thisInput.is(':checked')) {
                    thisCode.text(thisLabel)
                }
                else {
                    thisCode.text(`Tidak ${thisLabel}`);
                }
            });
        });
    </script>
@endsection