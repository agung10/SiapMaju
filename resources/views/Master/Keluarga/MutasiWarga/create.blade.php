@extends('layouts.master')
@section('content')
    <style type="text/css">
        textarea { overflow-y:auto; resize:none; }
    </style>
    <div class="container">
        <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
            <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <h5 class="text-dark font-weight-bold my-1 mr-5">
                            Tambah Mutasi Warga
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-custom gutter-b">
            <form id="headerForm" action="{{ route('Master.MutasiWarga.store') }}" method="POST">
                {{ csrf_field() }}
                <div class="card-body row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Warga<span class="text-danger">*</span></label>
                            <select name="anggota_keluarga_id" class="form-control">{!! $familyMember !!}</select>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Mutasi<span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_mutasi" class="form-control" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status Mutasi<span class="text-danger">*</span></label>
                            <select name="status_mutasi_warga_id" class="form-control">{!! $movedStatus !!}</select>
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="5" placeholder="Silakan isi keterangan (opsional)"></textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    @include('partials.buttons.submit')
                </div>
            </form>
        </div>
    </div>
    {!! JsValidator::formRequest('App\Http\Requests\Keluarga\MutasiWargaRequest', '#headerForm') !!}
    <script type="text/javascript">
        $(document).ready(function() {
            $('select[name=anggota_keluarga_id]').select2({ width:'100%', placeholder:'Pilih Warga' });
            $('select[name=status_mutasi_warga_id]').select2({ width:'100%', placeholder:'Pilih Status Mutasi' });
            const removeError = (element) => {
                const thisParent = element.parents('.form-group');
                thisParent.find('span.select2-selection--single').css({'border':'1px solid #1BC5BD'}).end()
                    .find('span#' + element.attr('name') + '-error').css({'display':'none'});
            };
            $('body').on('change', 'select[name=anggota_keluarga_id], select[name=status_mutasi_warga_id]', function() { removeError($(this)); });
        });
    </script>
@endsection