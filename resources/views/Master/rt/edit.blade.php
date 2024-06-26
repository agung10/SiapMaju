@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Edit RT
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
        <form id="dataForm" action="{{ route('Master.rt.update', $data->rt_id) }}" method="POST">
            @csrf
            {{ method_field('PATCH') }}
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Provinsi<span class="text-danger">*</span></label>
                            <select class="form-control" name="province_id" id="province">
                                {!! $resultProvince !!}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Kota/Kabupaten<span class="text-danger">*</span></label>
                            <select class="form-control" name="city_id" id="city">
                                {!! $resultCity !!}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Kecamatan<span class="text-danger">*</span></label>
                            <select class="form-control" name="subdistrict_id" id="subdistrict">
                                {!! $resultSubdistrict !!}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kelurahan<span class="text-danger">*</span></label>
                            <select class="form-control" name="kelurahan_id" id="kelurahan">
                                {{!! $resultKelurahan !!}}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>RW<span class="text-danger">*</span></label>
                            <select class="form-control" name="rw_id" id="rw">
                                {{!! $resultRW !!}}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Nama RT<span class="text-danger">*</span></label>
                            <input type="text" value="{{ $data->rt }}" name="rt" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                @include('partials.buttons.submit')
            </div>
        </form>
    </div>
</div>

{!! JsValidator::formRequest('App\Http\Requests\Master\RTRequest','#dataForm') !!}
<script>
    const editData = async () => {
        loading()

        const formData = new FormData(document.getElementById('dataForm'));

        const res = async () => {
            return  await fetch(`{{route('Master.rt.update', '')}}/{{\Request::segment(3)}}`,{
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
            localStorage.setItem('success','RT Berhasil Diupdate');
            return location.replace('{{route("Master.rt.index")}}');
        }
        
    }

    document.querySelector('#dataForm').addEventListener('submit', (e) => {
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

	var KTSelect2 = function() {
        // Private functions
        var demos = function() {
			$('select[name=province_id]').select2({ width: '100%', placeholder: '-- Pilih Provinsi --'})
			$('select[name=city_id]').select2({ width: '100%', placeholder: '-- Pilih Kabupaten --'})
			$('select[name=subdistrict_id]').select2({ width: '100%', placeholder: '-- Pilih Kecamatan --'})
			$('select[name=rw_id]').select2({ width: '100%', placeholder: '-- Pilih RW --'})
			$('select[name=kelurahan_id]').select2({ width: '100%', placeholder: '-- Pilih Kelurahan --'})

			const spinner = $('<div id="select-spinner" class="spinner spinner-right"></div>');
            const selectCity = $('#city')
            const selectSubdistrict = $('#subdistrict')
            const selectKelurahan = $('#kelurahan')
            const selectRW = $('#rw')

			$('body').on('change', 'select[name=province_id]', async function(){
				let province = $(this).val()
                if (province) {
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
    
    
                    const cities = await fetch(url).then(res => res.json()).catch(err => {
                        selectCity.prop("disabled", false);
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
    
                    $(this).prop("disabled", false);
                    selectCity.prop("disabled", false);
                    spinner.remove();
                }
			})

			$('body').on('change', 'select[name=city_id]', async function(){
				let city = $(this).val()
                if (city) {
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
    
    
                    const subdistricts = await fetch(url).then(res => res.json()).catch((err) => {
                        selectSubdistrict.prop("disabled", false);
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
                    $(this).prop("disabled", false);
                    selectSubdistrict.prop("disabled", false);
                    spinner.remove();
                }
			})

            $('body').on('change', 'select[name=subdistrict_id]', async function(){
				let subdistrict = $(this).val()
                if (subdistrict) {
                    let subOption = '<option></option>';
                    let url = @json(route('DetailAlamat.getKelurahan'));
                        url += `?subdistrictID=${ encodeURIComponent(subdistrict) }`
    
                    $(this).prop("disabled", true);
                    selectKelurahan.parent().append(spinner);
                    selectKelurahan.html('');
                    selectKelurahan.prop("disabled", true);
                    
                    selectRW.html('');
                    selectRW.prop("disabled", true);
    
                    const fetchKelurahan = await fetch(url).then(res => res.json()).catch((err) => {
                        selectKelurahan.prop("disabled", false);
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
                    $(this).prop("disabled", false);
                    selectKelurahan.prop("disabled", false);
                    spinner.remove();
                }
			})

            $('body').on('change', 'select[name=kelurahan_id]', async function(){
				let kelurahan = $(this).val()
                if (kelurahan) {
                    let subOption = '<option></option>';
                    let url = @json(route('DetailAlamat.getRW'));
                        url += `?kelurahanID=${ encodeURIComponent(kelurahan) }`
    
                    $(this).prop("disabled", true);
                    selectRW.parent().append(spinner);
                    selectRW.html('');
                    selectRW.prop("disabled", true);
    
    
                    const fetchRW = await fetch(url).then(res => res.json()).catch((err) => {
                        selectRW.prop("disabled", false);
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
                    $(this).prop("disabled", false);
                    selectRW.prop("disabled", false);
                    spinner.remove();
                }
			})
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
@endsection