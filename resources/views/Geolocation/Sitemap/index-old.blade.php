@extends('layouts.master')

@section('content')
@include('Geolocation.Sitemap.css')

<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Sitemap Platform Digital SiapMaju	                	            
                    </h5>
                </div>
            </div>
        </div>
    </div>

    @if ($isAdmin)
    <div class="d-flex flex-column mb-5">
        <div class="d-flex align-items-md-center mb-2 flex-column flex-md-row">
            <div class="bg-white rounded p-4 d-flex flex-grow-1 flex-sm-grow-0 w-100">
                <div class="row px-sm-5">
                    <div class="col-md-12 mt-5">
                        <div class="d-flex align-items-sm-end flex-column flex-sm-row mb-3">
                            <h2 class="d-flex align-items-center mr-5 mb-0">Pencarian</h2>
                            <span class="opacity-60 font-weight-bold">Berdasarkan alamat/lokasi yang dipilih</span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <form class="form">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select class="form-control" name="province_id" id="province">
                                            {!! $resultProvince !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select class="form-control" name="city_id" id="city">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select class="form-control" name="subdistrict_id" id="subdistrict">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select name="kelurahan_id" class="form-control border-0 font-weight-bold" id="kelurahan">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select name="rw_id" class="form-control border-0 font-weight-bold" id="rw">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select name="rt_id" class="form-control border-0 font-weight-bold" id="rt">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select name="blok_id" class="form-control border-0 font-weight-bold blok-input" id="blok">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-5">
                                    <button type="button" id="filter" class="btn btn-light-primary font-weight-bold mt-sm-0 px-7">
                                        <i class="fas fa-search"></i>
                                        Cari lokasi
                                    </button>

                                    <button type="button" id="reset" class="btn btn-default mt-sm-0">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="d-flex flex-column mb-5">
        <div class="d-flex align-items-md-center mb-2 flex-column flex-md-row">
            <div class="bg-white rounded p-4 d-flex flex-grow-1 flex-sm-grow-0 w-100">
                <div class="row px-sm-5">
                    <div class="col-md-12 mt-2">
                        <div class="d-flex align-items-sm-end flex-column flex-sm-row mb-3">
                            <h2 class="d-flex align-items-center mr-5 mb-0">Dashboard</h2>
                            @if ($isAdmin)
                                <span class="opacity-60 font-weight-bold">Blok Sesuai Pencarian</span>
                            @elseif ($isRw) 
                                <span class="opacity-60 font-weight-bold">Blok Sesuai RW</span>
                            @else
                                <span class="opacity-60 font-weight-bold">Lokasi Blok</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12">
                        <form class="form">
                            <div class="row">
                                <div class="col-md-12">
                                    <span class="color-legend-container"></span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- @if ($isAdmin)
    <div class="filter-blok-container">
        <div class="form-group">
			<label>Filter Blok</label>
            <div class="blok-input-wrapper">
                <select class="form-control blok-input" name="blok_id">
                    {!! \helper::select('blok','nama_blok',false,'nama_blok','ASC') !!}
                </select>
                <button class="btn btn-success reset-filter">Reset Filter</button>
            </div>
		</div>
    </div>
    @endif --}}

    {{-- <div class="color-legend-wrapper">
        <h3>Dashboard Taman Depok Permai</h3>
        <div class="color-legend-container"></div>
    </div> --}}

    <div class="card card-custom gutter-b">
        <div id="mapid"></div>
    </div>
</div>
@include('Geolocation.Sitemap.script')

<script>
    var isAdmin = '<?php echo $isAdmin; ?>';

    var KTSelect2 = function() {
        // Private functions
        var demos = function() {
			$('select[name=province_id]').select2({ width: '100%', placeholder: '-- Pilih Provinsi --'})
			$('select[name=city_id]').select2({ width: '100%', placeholder: '-- Pilih Kota/Kabupaten --'})
			$('select[name=subdistrict_id]').select2({ width: '100%', placeholder: '-- Pilih Kecamatan --'})
			$('select[name=kelurahan_id]').select2({ width: '100%', placeholder: '-- Pilih Kelurahan --'})
			$('select[name=rw_id]').select2({ width: '100%', placeholder: '-- Pilih RW --'})
			$('select[name=rt_id]').select2({ width: '100%', placeholder: '-- Pilih RT --'})
            $('select[name=blok_id]').select2({ width: '100%', placeholder: '-- Pilih Blok --'})

			const spinner = $('<div id="select-spinner" class="spinner spinner-right"></div>');
            const selectCity = $('#city')
            const selectSubdistrict = $('#subdistrict')
            const selectKelurahan = $('#kelurahan')
            const selectRW = $('#rw')
            const selectRT = $('#rt')
            const selectBlok = $('#blok')

            if (isAdmin) {
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

                selectBlok.html('');
                selectBlok.prop("disabled", true);
            }

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

                    selectRT.html('');
                    selectRT.prop("disabled", true);

                    selectBlok.html('');
                    selectBlok.prop("disabled", true);

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
    
                    selectRT.html('');
                    selectRT.prop("disabled", true);

                    selectBlok.html('');
                    selectBlok.prop("disabled", true);
    
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
    
                    selectRT.html('');
                    selectRT.prop("disabled", true);

                    selectBlok.html('');
                    selectBlok.prop("disabled", true);
    
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
    
                    selectRT.html('');
                    selectRT.prop("disabled", true);

                    selectBlok.html('');
                    selectBlok.prop("disabled", true);
    
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

            $('body').on('change', 'select[name=rw_id]', async function(){
				let rw = $(this).val()
                if (rw) {
                    let subOption = '<option></option>';
                    let url = @json(route('DetailAlamat.getRT'));
                        url += `?rwID=${ encodeURIComponent(rw) }`
    
                    $(this).prop("disabled", true);
                    selectRT.parent().append(spinner);
                    selectRT.html('');
                    selectRT.prop("disabled", true);

                    selectBlok.html('');
                    selectBlok.prop("disabled", true);
    
                    
                    const fetchRT = await fetch(url).then(res => res.json()).catch((err) => {
                        selectRT.prop("disabled", false);
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
                    $(this).prop("disabled", false);
                    selectRT.prop("disabled", false);
                    spinner.remove();
                }
			})

            $('body').on('change', 'select[name=rt_id]', async function(){
				let rt = $(this).val()
                if (rt) {
                    let subOption = '<option></option>';
                    let url = @json(route('DetailAlamat.getBlok'));
                        url += `?rtID=${ encodeURIComponent(rt) }`
    
                    $(this).prop("disabled", true);
                    selectBlok.parent().append(spinner);
                    selectBlok.html('');
                    selectBlok.prop("disabled", true);
                    
                    const fetchBlok = await fetch(url).then(res => res.json()).catch((err) => {
                        selectBlok.prop("disabled", false);
                        spinner.remove()
                        Swal.fire({
                            title: 'Terjadi kesalahan dalam menghubungi server, silahkan coba lagi...',
                            icon: 'warning'
                        })
                    });
    
                    for(const data of fetchBlok) {
                        subOption += `<option value="${data.blok_id}">${data.nama_blok}</option>`;
                    }
    
                    selectBlok.html(subOption);
                    selectBlok.select2({ 
                        placeholder: '-- Pilih Blok --', 
                        width: '100%'
                    });
                    $(this).prop("disabled", false);
                    selectBlok.prop("disabled", false);
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