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
                            Ubah Vaksinasi
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-custom gutter-b">
            <form id="headerForm" action="{{ route('RamahAnak.Posyandu.update', ((!empty($data)) ? (\Crypt::encrypt($data->id_ramah_anak)) : (''))) }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="card-body row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Vaksin</label>
                            <select name="id_vaksin" class="form-control">{!! $vaccine !!}</select>
                        </div>
                        <div class="form-group">
                            <label>Nama Anak<span class="text-danger">*</span></label>
                            <select name="anggota_keluarga_id" class="form-control">{!! $familyMember !!}</select>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Vaksinasi<span class="text-danger">*</span></label>
                            <input type="date" name="tgl_input" class="form-control" value="{{ ((!empty($data)) ? (date('Y-m-d', strtotime($data->tgl_input))) : (null)) }}" />
                        </div>
                        <div class="form-group">
                            <label>Keterangan Vaksinasi</label>
                            <textarea name="ket_vaksinasi" class="form-control" rows="5" placeholder="Masukkan Keterangan Vaksinasi (opsional)">{{ ((!empty($data)) ? ($data->ket_vaksinasi) : (null)) }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Berat Badan (kg)<span class="text-danger">*</span></label>
                            <input type="text" name="berat" class="form-control" value="{{ ((!empty($data)) ? ($data->berat) : (null)) }}" placeholder="Masukkan Berat Badan" />
                        </div>
                        <div class="form-group">
                            <label>Tinggi Badan (cm)<span class="text-danger">*</span></label>
                            <input type="text" name="tinggi" class="form-control" value="{{ ((!empty($data)) ? ($data->tinggi) : (null)) }}" placeholder="Masukkan Tinggi Badan" />
                        </div>
                        <div class="form-group">
                            <label>Lingkar Kepala (cm)<span class="text-danger">*</span></label>
                            <input type="text" name="lingkar_kepala" class="form-control" value="{{ ((!empty($data)) ? ($data->lingkar_kepala) : (null)) }}" placeholder="Masukkan Lingkar Kepala" />
                        </div>
                        <div class="form-group">
                            <label>Nilai Stunting<span class="text-danger">*</span></label>
                            <input type="text" name="nilai_stunting" class="form-control" value="{{ ((!empty($data)) ? ($data->nilai_stunting) : (null)) }}" placeholder="Masukkan Nilai Stunting" />
                        </div>
                        <div class="form-group">
                            <label>Keluhan</label>
                            <textarea name="keluhan" class="form-control" rows="5" placeholder="Masukkan Keluhan (opsional)">{{ ((!empty($data)) ? ($data->keluhan) : (null)) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    @include('partials.buttons.submit')
                </div>
            </form>
        </div>
    </div>
    {!! JsValidator::formRequest('App\Http\Requests\RamahAnak\PosyanduRequest', '#headerForm') !!}
    <script type="text/javascript">
        $(document).ready(function() {
            $('select[name=id_vaksin]').select2({ width:'100%', placeholder:'Pilih Vaksin' });
            $('select[name=anggota_keluarga_id]').select2({ width:'100%', placeholder:'Pilih Warga' });
            const removeError = (element) => {
                console.log(element);
                const thisParent = element.parents('.form-group');
                thisParent.find('span.select2-selection--single').css({'border':'1px solid #1BC5BD'}).end()
                    .find('span#' + element.attr('name') + '-error').css({'display':'none'});
            };

            $('body').on('change', 'select[name=id_vaksin], select[name=anggota_keluarga_id]', function() {
                removeError($(this));
            }).on('input', 'input[name=berat], input[name=tinggi], input[name=lingkar_kepala], input[name=nilai_stunting]', function() {
                let _this = $(this);
                if (_this.val().match(/^\d{1,}(\.\d{0,2})?$/)) {
                    localStorage.setItem(_this.attr('name'), _this.val());
                }
                else {
                    _this.val(localStorage.getItem(_this.attr('name')));
                }
            }).on('blur', 'input[name=berat], input[name=tinggi], input[name=lingkar_kepala], input[name=nilai_stunting]', function() {
                localStorage.removeItem($(this).attr('name'));
            });
        });
    </script>
@endsection