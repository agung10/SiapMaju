<script>
    var isAdmin = '<?php echo $isAdmin; ?>';
    var isWarga = '<?php echo $isWarga; ?>';

    init()

    var mymap;

    function leaflet(bloks){
        console.log('bloks', bloks);
        if (isWarga) {
            mymap = L.map('mapid').setView([bloks[0].lang, bloks[0].long], 21);
        } else {
            mymap = L.map('mapid').setView([-6.402687, 106.853243], 18);
        }

        console.log(mymap);
        var rest = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 22,
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
            accessToken: 'pk.eyJ1IjoiYmFycWkiLCJhIjoiY2t0aWc4MjVzMTFybTJwam9rNXBiNjNyYyJ9.MzpkBU0yXr-z5p2xCZynmQ'
        }).addTo(mymap);
        // clear legend so it is not nested
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
                    createHtmlLegend(color,rt)
                }

                const icon = L.divIcon({
                    className: 'map-marker',
                    iconSize:null,
                    html:`<span><i class="fa fa-map-marker-alt marker-icon" style="font-size:25;color:${color}"></i></span>`
                })

                const marker = L.marker([lang, long],{icon}).addTo(mymap)

                marker.on('click',() => markerClickHandler())

                async function markerClickHandler(){

                    const {status,result} = await fetchDataKeluarga(blok_id)

                    if(status !== 'success'){

                        swal.fire(`Data Warga Blok ${nama_blok} Tidak Ditemukan`,'','warning')
                        return;
                    }

                    const daftarWarga = () => {
                        return result.map(({nama,picture,is_rt,is_rw},i) => { 
                                                        const avatar =  picture ? `{{asset('uploaded_files/users')}}/${picture}`         
                                                                                : `{{asset('images/noAvatar.jpg')}}`
                                                        
                                                        const officer = is_rw ? 'RW' 
                                                                              : is_rt ? 'RT'
                                                                                      : ''
                                                        
                                                        const spanOfficer = officer ? `<span class="${officer}">(${officer})</span>`
                                                                                    : '';

                                                            return`<div class="detail-warga">
                                                                    <p>${i+1}.</p>
                                                                    <img class="avatar" src="${avatar}" />
                                                                    <p class="nama-warga">${nama}
                                                                    ${spanOfficer}
                                                                    </p>
                                                                   </div>`
                                                        })
                                                         .join('')
                    }

                    marker.bindPopup(`<div class="detail-blok-container">
                                        <p>Blok ${nama_blok}</p>
                                        <div class="detail-warga-container">
                                            ${daftarWarga()}
                                        </div>
                                    </div>`)
                }
        })
    }

    function init(){
        const bloks = @json($blok);

        leaflet(bloks)

        addSelectBlokEvent()

        resetFilter(bloks)
    }

    function addSelectBlokEvent()
    {
        if (isAdmin) {
            $('#filter').click(function(){
                var province_id = $('#province').val();

                if ((isAdmin && province_id == '')) {
                    swal.fire("Informasi", "Anda belum memilih alamat pencarian!", "info");
                } else {
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

        async function blokInputHandler(provinceId, cityId, subdistrictId, kelurahanId, rwId, rtId, blokId){

            const province_id = provinceId
            const city_id = cityId
            const subdistrict_id = subdistrictId
            const kelurahan_id = kelurahanId
            const rw_id = rwId
            const rt_id = rtId
            const blok_id = blokId

            const getBlokData = async () => {

                const url = `{{route('Geolocation.Sitemap.getBlokData')}}?province_id=${province_id}&city_id=${city_id}&subdistrict_id=${subdistrict_id}&kelurahan_id=${kelurahan_id}&rw_id=${rw_id}&rt_id=${rt_id}&blok_id=${blok_id}`
                console.log(url);
                return await fetch(url,{
                                    headers:{
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
            console.log(result);
            mymap.remove()

            leaflet(result)
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
            mymap.remove()
            
            leaflet(bloks)
        }
    }

    function createHtmlLegend(color,rt){
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

</script>