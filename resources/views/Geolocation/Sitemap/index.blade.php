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
                                    <input type="hidden" id="latitude_data">
                                    <input type="hidden" id="longitude_data">
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
                            @else
                                <span class="opacity-60 font-weight-bold">Blok Sesuai RW</span>
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

    <div class="card card-custom gutter-b">
        <div id="map-canvas" style="height:510px;"></div>   
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDye4JCXYK63nutkZ5-I58FL0CHkDlmdpM"></script>
<script>
    var isAdmin = '<?php echo $isAdmin; ?>';

    const bloks = @json($blok);
    const blokPersonal = @json($blokPersonal);
    initialize(bloks, blokPersonal);
    addSelectBlokEvent()
    resetFilter(bloks)

    var map = "";
    var marker;
    var infowindow;
    // var markers = [];
    
    // SHOW GOOGLE MAP
    function initialize(bloks, blokPersonal) {
        if (!isAdmin) {
            map = new google.maps.Map(document.getElementById('map-canvas'), {
                zoom: 18,
                center: new google.maps.LatLng(blokPersonal[0].lang, blokPersonal[0].long)
            });
        } else {
            map = new google.maps.Map(document.getElementById('map-canvas'), {
                zoom: 18,
                center: new google.maps.LatLng(-6.402687, 106.853243)
            });
        }
        
        var noPoi = [
        {
            featureType: "poi",
            stylers: [
              { visibility: "off" }
            ]   
          }
        ];

        map.setOptions({styles: noPoi});

        // clear legend so it is not nested {
        document.querySelector('.color-legend-container').innerHTML = '';

        //rtHolder to check if RT already exist or not
        let rtHolder = [];
        let color = null;

        // sort array to make the map marker location valid
        bloks.sort((a,b) => a.rt.localeCompare(b.rt))
        
        bloks.map(({blok_id,nama_blok,long,lang,rt}) => {
            if(long == null || lang == null) return;
            
            if(!rtHolder.includes(rt)){
                rtHolder.push(rt)
                color = "hsl(" + Math.random() * 360 + ", 50%, 50%)"
                createHtmlLegend(color, rt)
            }

            var icon = {
                path: "M53.1,48.1c3.9-5.1,6.3-11.3,6.3-18.2C59.4,13.7,46.2,0.5,30,0.5C13.8,0.5,0.6,13.7,0.6,29.9 c0,6.9,2.5,13.1,6.3,18.2C12.8,55.8,30,77.5,30,77.5S47.2,55.8,53.1,48.1z",
                fillColor: `${color}`,
                fillOpacity: 1,
                strokeWeight: 1,
                scale: 0.35,
                anchor: new google.maps.Point(32, 80),
                labelOrigin: new google.maps.Point(30, 30)
            };
            var coordsLatLng = {lat: parseFloat(lang), lng: parseFloat(long)};

            var markerTarget = {
                position : coordsLatLng,
                map: map,
                label: {
                    text: `${nama_blok}`, 
                    color: '#ffffff',
                    fontSize: "7px",
                    fontWeight: "bold"
                },
                icon: icon,
                title: `Blok ${nama_blok}`,
                animation:  google.maps.Animation.DROP
            }
            marker = new google.maps.Marker(markerTarget)
            
            // Allow each marker to have an info window    
            marker.addListener('click', async function (markerEvent) {
                const {status, result} = await fetchDataKeluarga(blok_id)
                const isInfoWindowOpen = (infoWindow) => {
                    var map = infoWindow && infoWindow.getMap();
                    return (map !== null && typeof map !== "undefined");
                }

                if( isInfoWindowOpen(infowindow) ) infowindow.close()

                if (status !== 'success') {
                    swal.fire(`Data Warga Blok ${nama_blok} Tidak Ditemukan`,'','warning')
                    return;
                }

                const generateDaftarUmkm = (umkms) => {
                    const umkmsElement = [];

                    umkms.forEach(({logo_umkm, nama_umkm, aktif},i) => { 
                        const logo =  logo_umkm ? `{{asset('uploaded_files/umkm')}}/${logo_umkm}`         
                                                : `{{asset('images/noAvatar.jpg')}}`

                        var listUmkm = `<div class="list">
                                <p style="margin-top: 4px;">${i+1}.</p>
                                <img class="logo-umkm" src="${logo}" />`
                                if (aktif) {
                                    listUmkm += `<p class="nama-umkm mt-1">${nama_umkm} <span class="text-success">(&#10004;)</span></p>`
                                } else {
                                    listUmkm += `<p class="nama-umkm mt-1">${nama_umkm} <span class="text-danger">(&#10006;)</span></p>`
                                }
                            listUmkm += `</div>`
                        umkmsElement.push(listUmkm);
                    })
                    
                    return umkmsElement.join('');
                }

                const generateDaftarKeluarga = async () => {
                    return result.map(({umkms, have_umkm, nama,picture,is_rt,is_rw},i) => { 
                        const avatar =  picture ? `{{asset('uploaded_files/users')}}/${picture}`         
                                                : `{{asset('images/noAvatar.jpg')}}`

                        const officer = is_rw ? 'RW' : is_rt ? 'RT' : ''
                        
                        const spanOfficer = officer ? `<span class="${officer}">(${officer})</span>` : '';

                        var data = `<div class="detail" style="display: flex;">
                                <div class="warga">
                                    <p style="margin-top: 27px;">${i+1}.</p>
                                    <img class="avatar" src="${avatar}" />
                                    <p class="nama-warga mt-3">${nama}
                                    ${spanOfficer}
                                    </p>
                                </div>`

                                if (umkms?.length > 0) {
                                    data+=`<div class="umkm">
                                        <p class="title">List UMKM</p>
                                        ${generateDaftarUmkm(umkms)}
                                    </div>`
                                } else {
                                    data+=`<div class="umkm">
                                        <span>Belum Mempunyai UMKM</span>
                                    </div>`
                                }
                                
                            data+=`</div>`

                        return data;
                    })
                    .join('')
                }
                
                const daftarKeluarga = await generateDaftarKeluarga();
                const content = `<p>Blok ${nama_blok}</p>
                                <div class="detail-blok-container">
                                    <div class="detail-warga-container">
                                        ${daftarKeluarga}
                                    </div>
                                </div>`;

                infowindow = new google.maps.InfoWindow(markerTarget)
                infowindow.setContent(content)
            })
        })
    }
    
    function addSelectBlokEvent(){
        if (isAdmin) {
            $("#filter").on("click", function() {
                var province_id = $('#province').val();

                if ((isAdmin && province_id == '')) {
                    swal.fire("Informasi", "Anda belum memilih alamat pencarian!", "info");
                } else {
                    // get <option> value
                    var provinceId = $('select[name=province_id]').val();
                    var cityId = $('select[name=city_id]').val();
                    var subdistrictId = $('select[name=subdistrict_id]').val();
                    var kelurahanId = $('select[name=kelurahan_id]').val();
                    var rwId = $('select[name=rw_id]').val();
                    var rtId = $('select[name=rt_id]').val();
                    var blokId = $('select[name=blok_id]').val();

                    blokInputHandler(provinceId, cityId, subdistrictId, kelurahanId, rwId, rtId, blokId)
                }
            })
        }

        async function blokInputHandler(provinceId, cityId, subdistrictId, kelurahanId, rwId, rtId, blokId) {
            const province_id = provinceId
            const city_id = cityId
            const subdistrict_id = subdistrictId
            const kelurahan_id = kelurahanId
            const rw_id = rwId
            const rt_id = rtId
            const blok_id = blokId

            const getBlokData = async () => {
                const url = `{{route('Geolocation.Sitemap.getBlokData')}}?province_id=${province_id}&city_id=${city_id}&subdistrict_id=${subdistrict_id}&kelurahan_id=${kelurahan_id}&rw_id=${rw_id}&rt_id=${rt_id}&blok_id=${blok_id}`

                return await fetch(url, {
                    headers: {
                        'X-CSRF-TOKEN':'{{csrf_token()}}',
                        'X-Requested-With':'XMLHttpRequest'
                    },
                    method:'post',
                })
                .then(response => response.json())
                .catch(() => {
                    swal.fire('Maaf Terjadi Kesalahan','','error')
                        .then(result => {
                            if(result.isConfirmed) window.location.reload()
                    })
                })
            }
            const {result} = await getBlokData()

            if (result.length > 0) {
                marker = null;
                initialize(result)

                var location = new google.maps.LatLng(result[0].lang, result[0].long)
                map.setCenter(location)
                
                if (province_id) { map.setZoom(10) } 
                if (city_id) { map.setZoom(12) }
                if (subdistrict_id) { map.setZoom(14) }
                if (kelurahan_id) { map.setZoom(16) }
                if (rw_id) { map.setZoom(18) }
                if (rt_id) { map.setZoom(20) }
                if (blok_id) { map.setZoom(22) }
            } else {
                swal.fire("Warning", "Maaf Data Pencarian Tidak Tersedia Untuk Saat Ini!", "error");
            }
        }
    }

    function resetFilter(bloks){
        $('#reset').click(function(){
            if (isAdmin) {
                $('select[name=province_id]').val('').trigger('change');

                $('select[name=city_id]').val('').trigger('change');
                $('select[name=city_id]').prop('disabled', true);

                $('select[name=subdistrict_id]').val('').trigger('change');
                $('select[name=subdistrict_id]').prop('disabled', true);

                $('select[name=kelurahan_id]').val('').trigger('change');
                $('select[name=kelurahan_id]').prop('disabled', true);

                $('select[name=rw_id]').val('').trigger('change');
                $('select[name=rw_id]').prop('disabled', true);

                $('select[name=rt_id]').val('').trigger('change');
                $('select[name=rt_id]').prop('disabled', true);

                $('select[name=blok_id]').val('').trigger('change');
                $('select[name=blok_id]').prop('disabled', true);

                resetFilterHandler()
            }
        });

        function resetFilterHandler(){
            marker = null;
            initialize(bloks)
        }
    }

    function createHtmlLegend(color, rt){
        const htmlLegend = `<div class="color-legend" style="width:50px;background:${color};border-radius:3px;margin:3px;"><span style="font-weight:bold;color:white;">${rt}</span></div>`
        document.querySelector('.color-legend-container').insertAdjacentHTML('afterbegin',htmlLegend)
    }

    async function fetchDataKeluarga(blok_id){
        const url = `{{route('Geolocation.Sitemap.store')}}`
        
        return await fetch(url,{
            headers:{
                'X-CSRF-TOKEN':'{{csrf_token()}}',
                'X-Requested-With':'XMLHttpRequest',
                'content-type':'application/json'
            },
            method:'post',
            body:JSON.stringify({blok_id})
        })
        .then(response => response.json())
        .catch(() => {
                swal.fire('Maaf Terjadi Kesalahan','','error')
                    .then(result => {
                        if(result.isConfirmed) window.location.reload()
                    })
        })
    }

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