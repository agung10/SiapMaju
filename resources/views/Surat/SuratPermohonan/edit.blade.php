@extends('layouts.master')

@section('content')
<style type="text/css">
    .lampiran {
        cursor: pointer;
    }
</style>
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Edit Surat Permohonan
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
        <div class="card-body">
            <div class="form-surat-container">
                <form action="{{ route('Surat.SuratPermohonan.update', $data->surat_permohonan_id) }}"
                    class="form-surat" enctype="multipart/form-data" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mt-3">
                        <h4>Form Surat Permohonan</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Warga<span class="text-danger">*</span></label>
                                @if(\Auth::user()->is_admin == true)
                                <select class="form-control warga" name="anggota_keluarga_id" disabled>
                                    {!! $selectNamaWarga !!}
                                </select>
                                @else
                                <select class="form-control warga" name="anggota_keluarga_id" disabled>
                                    {{-- {!! $selectNamaAnggota !!} --}}
                                    {!! $resultAnggota !!}
                                </select>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jenis Surat<span class="text-danger">*</span></label>
                                <select class="form-control jenis_surat" name="jenis_surat_id" disabled>
                                    {!! $jenisSurat !!}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tempat Lahir<span class="text-danger">*</span></label>
                                <input type="text" name="tempat_lahir" class="form-control"
                                    value="{{ $data->tempat_lahir }}" {{ (($data->status_upload == 1) ? ('disabled') : ('')) }} />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Tanggal Lahir<span class="text-danger">*</span></label>
                            <input type="date" class="form-control tgl_lahirC" value="{{ $data->tgl_lahir }}" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Bangsa<span class="text-danger">*</span></label>
                                <input type="text" name="bangsa" class="form-control" value="{{ $data->bangsa }}" {{ (($data->status_upload == 1) ? ('disabled') : ('')) }} />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status Pernikahan<span class="text-danger">*</span></label>
                                @if ($data->status_upload == 1)
                                    <select class="form-control status_pernikahan" disabled>
                                        {!! $pernikahan !!}
                                    </select>
                                @else
                                    <select class="form-control status_pernikahan" name="status_pernikahan_id">
                                        {!! $pernikahan !!}
                                    </select>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Agama<span class="text-danger">*</span></label>
                                @if ($data->status_upload == 1)
                                    <select class="form-control agama" disabled>
                                        {!! $agama !!}
                                    </select>
                                @else
                                    <select class="form-control agama" name="agama_id">
                                        {!! $agama !!}
                                    </select>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pekerjaan<span class="text-danger">*</span></label>
                                <input type="text" name="pekerjaan" class="form-control"
                                    value="{{ $data->pekerjaan }}" {{ (($data->status_upload == 1) ? ('disabled') : ('')) }} />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No KK<span class="text-danger">*</span></label>
                                <input type="text" name="no_kk" class="form-control no_kk" value="{{ $data->no_kk }}" {{ (($data->status_upload == 1) ? ('disabled') : ('')) }} />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No KTP<span class="text-danger">*</span></label>
                                <input type="text" name="no_ktp" class="form-control" value="{{ $data->no_ktp }}" {{ (($data->status_upload == 1) ? ('disabled') : ('')) }} />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Permohonan<span class="text-danger">*</span></label>
                                <input type="date" name="tgl_permohonan" class="form-control"
                                    value="{{ $data->tgl_permohonan }}" {{ (($data->status_upload == 1) ? ('disabled') : ('')) }} />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Keperluan Pembuatan<span class="text-danger">*</span></label>
                                <textarea class="form-control" name="keperluan">{{ $data->keperluan }}</textarea>
                            </div>
                        </div>
                    </div>
                    @if ($data->lampiran1 || $data->lampiran2 || $data->lampiran3)
                    <div class="mt-3">
                        <h4>Lampiran</h4>
                    </div>
                    <div class="row lampiran-container">
                        @if ($data->lampiran1)
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Lampiran 1</label>
                                <a href="{{ asset('uploaded_files/surat/'.$data->lampiran1) }}" download>
                                    <input class="form-control" type="text" value="{{ $data->lampiran1 }}" disabled />
                                </a><br>
                                <input class="lampiran form-control" type="file" name="lampiran1"
                                    value="{{ $data->lampiran1 }}" />
                            </div>
                        </div>
                        @endif
                        @if ($data->lampiran2)
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Lampiran 2</label>
                                <a href="{{ asset('uploaded_files/surat/'.$data->lampiran2) }}" download>
                                    <input class="form-control" type="text" value="{{ $data->lampiran2 }}" disabled />
                                </a><br>
                                <input class="lampiran form-control" type="file" name="lampiran2"
                                    value="{{ $data->lampiran1 }}" />
                            </div>
                        </div>
                        @endif
                        @if ($data->lampiran3)
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Lampiran 3</label>
                                <a href="{{ asset('uploaded_files/surat/'.$data->lampiran3) }}" download>
                                    <input class="form-control" type="text" value="{{ $data->lampiran3 }}" disabled />
                                </a><br>
                                <input class="lampiran form-control" type="file" name="lampiran3"
                                    value="{{ $data->lampiran1 }}" />
                            </div>
                        </div>
                        @endif
                    </div>
                    @elseif (count($lampiranSurat) > 0)
                    <div id="lampiran-section">
                        <div class="mt-3">
                            <h4>Lampiran</h4>
                        </div>
                        <hr>
                        @if ($lampiranSurat)
                        <div id="lampiran-form" class="row lampiran-container">
                            @foreach ($lampiranSurat as $res)
                            <div class="col-md-12 mb-5">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ $res->nama_lampiran }}</label>
                                            <div id="custom-file-{{$loop->iteration}}" class="custom-file">
                                                <input type="file" class="custom-file-input input-lampiran-edit upload-lampiran" name="upload_lampiran[]" {{$res->upload_lampiran ? '' : 'required'}} multiple>
                                                <label class="custom-file-label label-lampiran-edit">Masukkan File... </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <span class="font-weight-bold btn btn-secondary btn-sm text-justify mb-1 w-100" style="cursor: default; margin-top:-1.3em;">
                                    <i class="fas fa-info-circle"></i>
                                    {{$res->keterangan}}
                                </span>
                                <a href="{{ asset('uploaded_files/surat/'.$res->upload_lampiran) }}" target="_blank" class="mb-5">Lihat File</a>
                                <input type="hidden" name="lampiran_id[]" value="{{$res->lampiran_id}}">
                            </div>
                            @endforeach
                        </div>  
                        @else
                        <div id="lampiran-form" class="row"></div>
                        @endif
                    </div>
                    @endif

                    @if ($data->catatan_kelurahan)
                        <div class="mt-3">
                            <h4>Catatan</h4>
                        </div>
                        <div class="row row-catatan">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Catatan Kelurahan</label>
                                    <textarea class="form-control" rows="9" disabled>{{ strip_tags($data->catatan_kelurahan) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Catatan</label>
                                    <textarea name="catatan_kelurahan" class="form-control" rows="9" placeholder="Tulis Catatan Disini"></textarea>
                                </div>
                            </div>
                        </div>
                    @endif
                    <h4 class="mt-3">Data Detail Alamat</h4>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Provinsi<span class="text-danger">*</span></label>
                                <select class="form-control provinceC" id="province">
                                    {!! $resultProvince !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Kota/Kabupaten<span class="text-danger">*</span></label>
                                <select class="form-control cityC" id="city">
                                    {!! $resultCity !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Kecamatan<span class="text-danger">*</span></label>
                                <select class="form-control subdistrictC" id="subdistrict">
                                    {!! $resultSubdistrict !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kelurahan<span class="text-danger">*</span></label>
                                <select class="form-control kelurahanC" id="kelurahan">
                                    {{!! $kelurahan !!}}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>RW<span class="text-danger">*</span></label>
                                <select class="form-control rwC" id="rw">
                                    {{!! $rw !!}}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>RT<span class="text-danger">*</span></label>
                                <select class="form-control rtC" id="rt">
                                    {{!! $rt !!}}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Alamat<span class="text-danger">*</span></label>
                                <textarea type="text" class="form-control alamatC" id="alamat">{{ $anggotaGetAlamat['alamat'] }}</textarea>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="rt_id" value="{{ $data->rt_id }}">
                    <input type="hidden" name="rw_id" value="{{ $data->rw_id }}">
                    <input type="hidden" name="kelurahan_id" value="{{ $data->kelurahan_id }}">
                    <input type="hidden" name="subdistrict_id" value="{{ $data->subdistrict_id }}">
                    <input type="hidden" name="city_id" value="{{ $data->city_id }}">
                    <input type="hidden" name="province_id" value="{{ $data->province_id }}">
                    <input type="hidden" name="nama_lengkap" value="{{ $data->nama_lengkap }}">
                    <input type="hidden" name="alamat" value="{{ $data->alamat }}">
                    <input type="hidden" name="tgl_lahir" value="{{ $data->tgl_lahir }}">
                    <input type="hidden" name="hal" value="{{ $data->hal }}">
                    <input type="hidden" name="lampiran" value="{{ $data->lampiran }}">

                    <div class="card-footer">
                        @include('partials.buttons.submit')
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{!! JsValidator::formRequest('App\Http\Requests\Surat\SuratRequest','.form-surat') !!}
@include('Surat.SuratPermohonan.editScript')

<script>
    async function fetchDataKeluarga(id) {
        const anggota_keluarga_id = id

        const response = async () => {
            const url = `{{route('Surat.SuratPermohonan.getDataWarga','')}}/${anggota_keluarga_id}`
            return await fetch(url,{
                headers:{
                    'X-CSRF-TOKEN':'{{csrf_token()}}',
                    'X-Requested-With':'XMLHttpRequest'
                },
                    method:'post'
            })
            .then(response => response.json())
            .catch(() => {
                swal.fire('Maaf Terjadi Kesalahan','','error')
                .then(result => {
                    if(result.isConfirmed) window.location.reload()
                })
            })
        }

        return await response()
    }

    async function fetchDataLampiran(id) {
        const lampiran_id = id

        const response = async () => {
            const url = `{{route('Surat.SuratPermohonan.getDataLampiran','')}}/${lampiran_id}`
        
            return await fetch(url,{
                headers:{
                    'X-CSRF-TOKEN':'{{csrf_token()}}',
                    'X-Requested-With':'XMLHttpRequest'
                },
                    method:'post'
            })
            .then(response => response.json())
            .catch(() => {
                swal.fire('Maaf Terjadi Kesalahan','','error')
                .then(result => {
                    if(result.isConfirmed) window.location.reload()
                })
            })
        }

        return await response()
    }

    var KTSelect2 = function() {
        // Functions
        return {
            init: function() {
                $('select[name=anggota_keluarga_id]').select2({ width: '100%', placeholder: '-- Pilih Nama warga --'})
                $('select[name=jenis_surat_id]').select2({ width: '100%', placeholder: '-- Pilih Jenis Surat --'})
                $('select[name=status_pernikahan_id]').select2({ width: '100%', placeholder: '-- Status Pernikahan --'})
                $('select[name=agama_id]').select2({ width: '100%', placeholder: '-- Pilih Agama --'})

                $('.provinceC').select2({ width: '100%', placeholder: '-- Pilih Provinsi --'})
                $('.cityC').select2({ width: '100%', placeholder: '-- Pilih Kabupaten --'})
                $('.subdistrictC').select2({ width: '100%', placeholder: '-- Pilih Kecamatan --'})
                $('.kelurahanC').select2({ width: '100%', placeholder: '-- Pilih Kelurahan --'})
                $('.rwC').select2({ width: '100%', placeholder: '-- Pilih RW --'})
                $('.rtC').select2({ width: '100%', placeholder: '-- Pilih RT --'})


                var getDataAlamat
                var selectKeluargaID
                $('body').on('change', 'select[name=anggota_keluarga_id]', async function(){
                    selectKeluargaID = this.value

                    if(selectKeluargaID)
                    {
                        getDataAlamat = await fetchDataKeluarga(selectKeluargaID)

                        const {province_id, tgl_lahir} = getDataAlamat.data;

                        if (tgl_lahir != null) {
                            $(".tgl_lahirC").val(tgl_lahir);
                        }

                        if (province_id == null) {
                            swal.fire({
                                title: 'Data Detail Alamat tidak ada/belum lengkap, silahkan perbarui data terlebih dahulu...',
                                icon: 'warning',
                            }).then((data) => {
                                // window.location = `{{ url('Master/Keluarga/AnggotaKeluarga/${selectKeluargaID}/edit') }}`;
                                window.location.reload();
                            })
                            $('.provinceC').val('').trigger('change.select2');
                            $('.provinceC').prop("disabled", true);

                            $('.cityC').val('').trigger('change.select2');
                            $('.cityC').prop("disabled", true);

                            $('.subdistrictC').val('').trigger('change.select2');
                            $('.subdistrictC').prop("disabled", true);

                            $('.kelurahanC').val('').trigger('change.select2');
                            $('.kelurahanC').prop("disabled", true);

                            $('.rwC').val('').trigger('change.select2');
                            $('.rwC').prop("disabled", true);

                            $('.rtC').val('').trigger('change.select2');
                            $('.rtC').prop("disabled", true);

                            $('.alamatC').val('');
                            $('.alamatC').prop("disabled", true);
                        } else {
                            $('.provinceC').val(province_id).trigger('change');
                        }

                        $('input[name=nama_lengkap]').val(getDataAlamat.data.nama)
						$('input[name=tgl_lahir]').val(getDataAlamat.data.tgl_lahir)
						$('input[name=rt_id]').val(getDataAlamat.data.rt_id)
						$('input[name=rw_id]').val(getDataAlamat.data.rw_id)
						$('input[name=kelurahan_id]').val(getDataAlamat.data.kelurahan_id)
						$('input[name=subdistrict_id]').val(getDataAlamat.data.subdistrict_id)
						$('input[name=city_id]').val(getDataAlamat.data.city_id)
						$('input[name=province_id]').val(getDataAlamat.data.province_id)
						$('input[name=alamat]').val(getDataAlamat.data.alamat)
                    }
                })

                let spinner = $('<div id="select-spinner" class="spinner spinner-right"></div>');
                let selectProvince = $('#province')
                let selectCity = $('#city')
                let selectSubdistrict = $('#subdistrict')
                let selectKelurahan = $('#kelurahan')
                let selectRW = $('#rw')
                let selectRT = $('#rt')
                let inputAlamat = $('#alamat')

                selectProvince.prop("disabled", true);
				selectCity.prop("disabled", true);
				selectSubdistrict.prop("disabled", true);
				selectKelurahan.prop("disabled", true);
				selectRW.prop("disabled", true);
				selectRT.prop("disabled", true);
				inputAlamat.prop("disabled", true);
                
                $('body').on('change', '.provinceC', async function(){
                    let province = $(this).val()
                    let citiesOption = '<option></option>';
                    let url = @json(route('DetailAlamat.getCities'));
                        url += `?province_id=${ encodeURIComponent(province) }`

                    $(this).prop("disabled", true);
                    selectCity.parent().append(spinner);
                    selectCity.html('');
                    selectCity.prop("disabled", true);

                    selectSubdistrict.html('');
                    selectSubdistrict.prop("disabled", true);
                    
                    selectKelurahan.html('');
                    selectKelurahan.prop("disabled", true);

                    selectRW.html('');
                    selectRW.prop("disabled", true);

                    selectRT.html('');
                    selectRT.prop("disabled", true);
                    
                    inputAlamat.html('');
                    inputAlamat.prop("disabled", true);

                    const cities = await fetch(url).then(res => res.json()).catch(err => {
                        // selectCity.prop("disabled", false);
                        spinner.remove()
                        Swal.fire({
                            title: 'Terjadi kesalahan dalam menghubungi server, silahkan coba lagi...',
                            icon: 'warning'
                        })
                    });

                    for(const city of cities) {
                        citiesOption += `<option value="${city.city_id}">${city.type} ${city.city_name}</option>`;
                    }

                    selectCity.html(citiesOption);
                    selectCity.select2({ 
                        placeholder: '-- Pilih Kabupaten --', 
                        width: '100%'
                    });

                    // $(this).prop("disabled", false);
                    // selectCity.prop("disabled", false);
                    spinner.remove();

                    if(province && selectKeluargaID)
                    {
                        const {city_id} = getDataAlamat.data;
                        $('.cityC').val(city_id).trigger('change');
                    }
                })

                $('body').on('change', '.cityC', async function(){
                    let city = $(this).val()
                    let subOption = '<option></option>';
                    let url = @json(route('DetailAlamat.getSubdistricts'));
                        url += `?city_id=${ encodeURIComponent(city) }`

                    $(this).prop("disabled", true);
                    selectSubdistrict.parent().append(spinner);
                    selectSubdistrict.html('');
                    selectSubdistrict.prop("disabled", true);
                    
                    selectKelurahan.html('');
                    selectKelurahan.prop("disabled", true);
                    
                    selectRW.html('');
                    selectRW.prop("disabled", true);

                    selectRT.html('');
                    selectRT.prop("disabled", true);

                    inputAlamat.html('');
                    inputAlamat.prop("disabled", true);

                    const subdistricts = await fetch(url).then(res => res.json()).catch((err) => {
                        // selectSubdistrict.prop("disabled", false);
                        spinner.remove()
                        Swal.fire({
                            title: 'Terjadi kesalahan dalam menghubungi server, silahkan coba lagi...',
                            icon: 'warning'
                        })
                    });

                    for(const sub of subdistricts) {
                        subOption += `<option value="${sub.subdistrict_id}">${sub.subdistrict_name}</option>`;
                    }

                    selectSubdistrict.html(subOption);
                    selectSubdistrict.select2({ 
                        placeholder: '-- Pilih Kecamatan --', 
                        width: '100%'
                    });
                    // $(this).prop("disabled", false);
                    // selectSubdistrict.prop("disabled", false);
                    spinner.remove();

                    if(city && selectKeluargaID)
                    {
                        const {subdistrict_id} = getDataAlamat.data;
                        
                        $('.subdistrictC').val(subdistrict_id).trigger('change');
                    }
                })

                $('body').on('change', '.subdistrictC', async function(){
                    let subdistrict = $(this).val()
                    let subOption = '<option></option>';
                    let url = @json(route('DetailAlamat.getKelurahan'));
                        url += `?subdistrictID=${ encodeURIComponent(subdistrict) }`

                    $(this).prop("disabled", true);
                    selectKelurahan.parent().append(spinner);
                    selectKelurahan.html('');
                    selectKelurahan.prop("disabled", true);
                    
                    selectRW.html('');
                    selectRW.prop("disabled", true);

                    selectRT.html('');
                    selectRT.prop("disabled", true);

                    inputAlamat.html('');
                    inputAlamat.prop("disabled", true);

                    const fetchKelurahan = await fetch(url).then(res => res.json()).catch((err) => {
                        // selectKelurahan.prop("disabled", false);
                        spinner.remove()
                        Swal.fire({
                            title: 'Terjadi kesalahan dalam menghubungi server, silahkan coba lagi...',
                            icon: 'warning'
                        })
                    });

                    for(const data of fetchKelurahan) {
                        subOption += `<option value="${data.kelurahan_id}">${data.nama}</option>`;
                    }

                    selectKelurahan.html(subOption);
                    selectKelurahan.select2({ 
                        placeholder: '-- Pilih Kelurahan --', 
                        width: '100%'
                    });
                    // $(this).prop("disabled", false);
                    // selectKelurahan.prop("disabled", false);
                    spinner.remove();

                    if(subdistrict && selectKeluargaID)
                    {
                        const {kelurahan_id} = getDataAlamat.data;
                        
                        $('.kelurahanC').val(kelurahan_id).trigger('change');
                    }
                })

                $('body').on('change', '.kelurahanC', async function(){
                    let kelurahan = $(this).val()
                    let subOption = '<option></option>';
                    let url = @json(route('DetailAlamat.getRW'));
                        url += `?kelurahanID=${ encodeURIComponent(kelurahan) }`

                    $(this).prop("disabled", true);
                    selectRW.parent().append(spinner);
                    selectRW.html('');
                    selectRW.prop("disabled", true);

                    selectRT.html('');
                    selectRT.prop("disabled", true);

                    inputAlamat.html('');
                    inputAlamat.prop("disabled", true);

                    const fetchRW = await fetch(url).then(res => res.json()).catch((err) => {
                        // selectRW.prop("disabled", false);
                        spinner.remove()
                        Swal.fire({
                            title: 'Terjadi kesalahan dalam menghubungi server, silahkan coba lagi...',
                            icon: 'warning'
                        })
                    });

                    for(const data of fetchRW) {
                        subOption += `<option value="${data.rw_id}">${data.rw}</option>`;
                    }

                    selectRW.html(subOption);
                    selectRW.select2({ 
                        placeholder: '-- Pilih RW --', 
                        width: '100%'
                    });
                    // $(this).prop("disabled", false);
                    // selectRW.prop("disabled", false);
                    spinner.remove();

                    if(kelurahan && selectKeluargaID)
                    {
                        const {rw_id} = getDataAlamat.data;
                        
                        $('.rwC').val(rw_id).trigger('change');
                    }
                })

                $('body').on('change', '.rwC', async function(){
                    let rw = $(this).val()
                    let subOption = '<option></option>';
                    let url = @json(route('DetailAlamat.getRT'));
                        url += `?rwID=${ encodeURIComponent(rw) }`

                    $(this).prop("disabled", true);
                    selectRT.parent().append(spinner);
                    selectRT.html('');
                    selectRT.prop("disabled", true);

                    inputAlamat.html('');
                    inputAlamat.prop("disabled", true);
                    
                    const fetchRT = await fetch(url).then(res => res.json()).catch((err) => {
                        // selectRT.prop("disabled", false);
                        spinner.remove()
                        Swal.fire({
                            title: 'Terjadi kesalahan dalam menghubungi server, silahkan coba lagi...',
                            icon: 'warning'
                        })
                    });

                    for(const data of fetchRT) {
                        subOption += `<option value="${data.rt_id}">${data.rt}</option>`;
                    }

                    selectRT.html(subOption);
                    selectRT.select2({ 
                        placeholder: '-- Pilih RT --', 
                        width: '100%'
                    });
                    // $(this).prop("disabled", false);
                    // selectRT.prop("disabled", false);
                    spinner.remove();

                    if(rw && selectKeluargaID)
                    {
                        const {rt_id} = getDataAlamat.data;
                        
                        $('.rtC').val(rt_id).trigger('change');
                    }
                })

                $('body').on('change', '.rtC', async function(){
                    let rt = $(this).val()
                    let textarea = '<textarea type="text" class="form-control"></textarea>';

                    // $(this).prop("disabled", false);
                    inputAlamat.html('');
                    // inputAlamat.prop("disabled", false);

                    if(rt && selectKeluargaID)
                    {
                        const {alamat} = getDataAlamat.data;
                        
                        $('.alamatC').val(alamat);
                    }
                })
            }
        };
    }();

    // Initialization
    jQuery(document).ready(function() {
        KTSelect2.init();

        $('#lampiran-section').show(300)
        // $('body').on('change', 'select[name=jenis_surat_id]', async function(e){
        //     const jenisSurat = e.currentTarget.value
		// 	const halSurat = getHalSurat(e);
		// 	const inputHalSurat = document.querySelector('input[name=hal]')
		// 	const keperluan = document.querySelector('.keperluan-container')
		// 	inputHalSurat.value = halSurat

		// 	if(jenisSurat == 15){
		// 		$(keperluan).fadeIn('slow')
		// 	}else{
		// 		$(keperluan).fadeOut('slow')
		// 	}

        //     selectJenisSuratID = this.value
        //     if(selectJenisSuratID){
        //         getDataLampiran = await fetchDataLampiran(selectJenisSuratID)
        //         const data = getDataLampiran.data;
                
        //         $('#lampiran-section').hide(300)
        //         $('#lampiran-form').html("")

        //         if (data && data.length > 0) {
        //             for (let i = 0; i < data.length; i++) {
        //                 $('#lampiran-section').show(300)
        //                 var template_lampiran = `<div class="col-md-12">
        //                     <label>${data[i].nama_lampiran}</label>`
        //                     if (data[i].kategori == 1) {
        //                         template_lampiran += `<span class="pl-2 font-weight-bold text-danger">(Wajib Diisi)</span>`
        //                     }
        //                     else {
        //                         template_lampiran += `<span class="pl-2 font-weight-bold" style="color: #3F4254;">(Tidak Wajib Diisi)</span>`
        //                     }
        //                 template_lampiran += `</div>
        //                 <div class="col-md-12">
        //                     <div class="row">
        //                         <div class="col-md-12">
        //                             <div class="form-group">
        //                                 <div id="custom-file-${i}" class="custom-file">
        //                                     <input type="file" class="custom-file-input upload-lampiran" name="upload_lampiran[]" ${data[i].kategori == 1 ? 'required' : ''} multiple>
        //                                     <label class="custom-file-label">Masukkan File...</label>
        //                                 </div>
        //                             </div>
        //                         </div>
        //                     </div>
        //                     <span class="font-weight-bold btn btn-secondary btn-sm text-justify mb-5 w-100" style="cursor: default; margin-top:-1.3em;">
        //                         <i class="fas fa-info-circle"></i>
        //                         ${data[i].keterangan}
        //                     </span>
        //                     <input type="hidden" name="lampiran_id[]" value="${data[i].lampiran_id}">
        //                 </div>`
        //                 $('#lampiran-form').append(template_lampiran)
        //             }
        //         }
        //     }
        //     else {
        //         $('#lampiran-section').hide(300)
        //         $('#lampiran-form').html("")
        //     }
        //     $('.custom-file-input').on('change', function () {
        //         var files = this.files;
        //         $('#'+$(this).parent().attr('id')+' .custom-file-label').empty();
        //         for (var i = 0, l = files.length; i < l; i++) {
        //             $('#'+$(this).parent().attr('id')+' .custom-file-label').append(files[i].name + '\n');
        //         }
        //     });
        // })

		function getHalSurat(e){
			const select = e.currentTarget
			const selectedText = select.options[select.selectedIndex].text
			return selectedText
		}
    });

    $('.input-lampiran-edit').on('change', function () {
        var files = this.files;
        $('#'+$(this).parent().attr('id')+' .label-lampiran-edit').empty();
        for (var i = 0, l = files.length; i < l; i++) {
            $('#'+$(this).parent().attr('id')+' .label-lampiran-edit').append(files[i].name + '\n');
        }
    });
</script>
@endsection