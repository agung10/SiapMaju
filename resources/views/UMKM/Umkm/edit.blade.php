@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Edit UMKM
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
        <form class="form forme" id="headerForm" action="{{ route('UMKM.Umkm.update', $data->umkm_id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            {{ method_field('PATCH') }}
            <div class="card-body">
                <div class="form-group">
                    <div class="imageContainer">
                        <img class="imagePicture"
                            src="{{ ((!empty($data)) ? ((!empty($data->image)) ? (asset('uploaded_files/umkm/'.$data->image)) : (asset('images/NoPic.png'))) : (asset('images/NoPic.png'))) }}"
                            width="200" height="200"></img>
                    </div>
                    <br>
                    <label>Logo UMKM<span class="text-danger">*</span></label>
                    <div class="custom-file">
                        <input name="image" type="file" class="custom-file-input" id="customFile" />
                        <label class="custom-file-label" for="customFile">{{ $data->image }}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleSelect1">Nama UMKM<span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" placeholder="Masukkan nama umkm"
                                value="{{ $data->nama_umkm }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Owner<span class="text-danger">*</span></label>
                            <select class="form-control" name="anggota_keluarga_id">
                                {!! $resultOwner !!}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="deskripsi">Deskripsi<span class="text-danger">*</span></label>
                        <textarea type="text" name="deskripsi" class="form-control"
                            rows="5">{{ $data->deskripsi }}</textarea>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status<span class="text-danger">*</span></label>
                            <select class="form-control" name="aktif">
                                <option selected disabled></option>
                                <option value="1" {{$data->aktif == '1' ? 'selected' : ''}}>Aktif</option>
                                <option value="0" {{$data->aktif == '0' ? 'selected' : ''}}>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Disetujui<span class="text-danger">*</span></label>
                            <select class="form-control" name="disetujui">
                                <option selected disabled></option>
                                <option value="1" {{$data->disetujui == '1' ? 'selected' : ''}}>Disetujui</option>
                                <option value="0" {{$data->disetujui == '0' ? 'selected' : ''}}>Tidak Disetujui</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Promosi</label>
                            <select class="form-control" name="promosi">
                                <option selected disabled></option>
                                <option value="1" {{$data->promosi == '1' ? 'selected' : ''}}>Ya</option>
                                <option value="0" {{$data->promosi == '0' ? 'selected' : ''}}>Tidak</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Website</label>
                            <select class="form-control" name="has_website">
                                <option selected disabled></option>
                                <option value="1" {{$data->has_website == '1' ? 'selected' : ''}}>Ya</option>
                                <option value="0" {{$data->has_website == '0' ? 'selected' : ''}}>Tidak</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="separator separator-dashed my-8"></div>
                <div id="sosmed-repeater">
                    <div class="form-group">
                        <h3 class="card-label">
                            Sosial Media
                        </h3>
                    </div>
                    @if(count($umkmMedsos) > 0)
                        @foreach($umkmMedsos as $val)
                        <div class="form-group row form-sosmed">
                            <div class="col-lg-12">
                                <div class="form-group row align-items-center">
                                    <div class="col-md-3">
                                        <label>Sosial Media:</label>
                                        <select name="medsos_id[]" class="form-control sosmed-id">
                                            <option></option>
                                            @foreach($medsos as $key => $value)
                                                <option value="{{ $key }}" {{ $val->medsos_id == $key ? 'selected' : ''}}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="d-md-none mb-2"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <label>URL:</label>
                                        <input name="medsos_url[]" type="text" class="form-control sosmed-url"
                                            value="{{ $val->url }}" placeholder="Masukkan URL">
                                        <div class="d-md-none mb-2"></div>
                                    </div>
                                    <div class="col-md-6 mt-2 action-medsos">
                                        @if($loop->last)
                                            <a href="javascript:;" class="btn font-weight-bolder btn-primary btn-new-sosmed"
                                                style="margin-top: 20px;">
                                                <i class="la la-plus"></i>Add
                                            </a>
                                        @endif
                                        <div class="d-md-none mb-2"></div>
                                        <a href="javascript:;" class="btn font-weight-bolder btn-danger btn-delete-sosmed"
                                            style="margin-top: 20px;">
                                            <i class="la la-trash-o"></i>Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                    <div class="form-group row form-sosmed">
                        <div class="col-lg-12">
                            <div class="form-group row align-items-center">
                                <div class="col-md-3">
                                    <label>Sosial Media:</label>
                                    <select name="medsos_id[]" class="form-control sosmed-id">
                                        <option></option>
                                        @foreach($medsos as $key => $value)
                                        <option value="{{ $key }}">
                                            {{ $value }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="d-md-none mb-2"></div>
                                </div>
                                <div class="col-md-3">
                                    <label>URL:</label>
                                    <input name="medsos_url[]" type="text" class="form-control sosmed-url"
                                        placeholder="Masukkan URL" autocomplete="off">
                                    <div class="d-md-none mb-2"></div>
                                </div>
                                <div class="col-md-6 mt-2 action-medsos">
                                    <a href="javascript:;" class="btn font-weight-bolder btn-primary btn-new-sosmed"
                                        style="margin-top: 20px;">
                                        <i class="la la-plus"></i>Add
                                    </a>
                                    <div class="d-md-none mb-2"></div>
                                    <a href="javascript:;"
                                        class="btn font-weight-bolder btn-danger btn-delete-sosmed d-none"
                                        style="margin-top: 20px;">
                                        <i class="la la-trash-o"></i>Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card-footer">
                @include('partials.buttons.submit')
            </div>
        </form>
    </div>
</div>

{!! JsValidator::formRequest('App\Http\Requests\UMKM\UMKMRequest','#headerForm') !!}
<script>
    const editData = async () => {
        loading()

        const formData = new FormData(document.getElementById('headerForm'));

        const res = async () => {
            return  await fetch(`{{route('UMKM.Umkm.update','')}}/{{\Request::segment(3)}}`,{
                headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},
                method:'post',
                body:formData
            })
            .then(response => response.json())
            .catch(() => {
                KTApp.unblockPage()
                swal.fire('Maaf Terjadi Kesalahan','','error')
                    .then(result => {
                        if(result.isConfirmed) window.location.reload()
                    })
            })
        } 

        const {status} = await res()

        if(status === 'success'){
            localStorage.setItem('success','UMKM Berhasil Diupdate');
            return location.replace('{{route("UMKM.Umkm.index")}}');
        }
        
    }

    document.querySelector('#headerForm').addEventListener('submit', (e) => {
        e.preventDefault();
        const form  = e.currentTarget
        const valid = $(form).valid()
        if(!valid) return;

        editData();
    })

    function loading(){
        KTApp.blockPage({
            overlayColor: '#000000',
            state: 'primary',
            message: 'Mohon Tunggu Sebentar'
        });
    }

    // Image Preview Script
    const imageAgendaField = document.querySelector('input[name="image"]')

    if(imageAgendaField){
        imageAgendaField.addEventListener('change',(event)=> {
            const output = document.querySelector('.imagePicture');

            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = () => {
                URL.revokeObjectURL(output.src)
            }
        });
    }

    // Class Select2 definition
    var KTSelect2 = function() {
        // Private functions
        var demos = function() {
            // basic
            $('select[name=anggota_keluarga_id]').select2({ placeholder: '-- Pilih Owner --' });
            $('select[name=aktif]').select2({ placeholder: '-- Status UMKM --' });
            $('select[name=disetujui]').select2({ width: '100%', placeholder: '-- Persetujuan --'});
            $('select[name=promosi]').select2({ width: '100%', placeholder: '-- Promosi --'});
            $('select[name=has_website]').select2({ width: '100%', placeholder: '-- Website --'});
            $('.sosmed-id').select2({ width: '100%', placeholder: '-- Pilih Sosmed --'});
        }

        // Functions
        return {
            init: function() {
                demos();
            }
        };
    }();

    // Initialization
    jQuery(document).ready(function() {
        KTSelect2.init();
    });
</script>
@include('UMKM.Umkm.medsosScript')
@endsection