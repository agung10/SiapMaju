@extends('layouts.master')
<?php
    $isAdmin = \helper::checkUserRole('admin');
    $isRw = \helper::checkUserRole('rw');
?>
@section('content')
@include('Master.role_management.ListWarga.css')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        List Warga
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
                                        <select name="province_id" class="form-control border-0 font-weight-bold"
                                            id="province">
                                            <option></option>
                                            @foreach($provinces as $province_id => $province)
                                            <option value="{{ $province_id }}"> {{ $province }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select name="city_id" class="form-control border-0 font-weight-bold" id="city">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select name="subdistrict_id" class="form-control border-0 font-weight-bold"
                                            id="subdistrict">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select name="kelurahan_id" class="form-control border-0 font-weight-bold"
                                            id="kelurahan">
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
                                    <button type="button" id="filter"
                                        class="btn btn-light-primary font-weight-bold mt-sm-0 px-7">
                                        <i class="fas fa-search"></i>
                                        Cari Data
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
    @elseif ($isRw)
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="rt_id" class="form-control border-0 font-weight-bold" id="rt">
                                            {!! \helper::select('rt','rt',false,'rt','ASC') !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" id="filter"
                                        class="btn btn-light-primary font-weight-bold mt-sm-0 px-7">
                                        <i class="fas fa-search"></i>
                                        Cari Data
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

    <div class="card card-custom gutter-b">
        <div class="card-body">
            <table class="table table-bordered table-checkable table-responsive" id="datatable">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th width="300">Nama</th>
                        <th width="300">Blok</th>
                        <th width="300">RT</th>
                        <th width="300">Jenis Kelamin</th>
                        <th width="300">Hubungan Keluarga</th>
                        <th width="300">Tahun Lahir</th>
                        <th width="300">Email</th>
                        <th width="300">Status</th>
                        <th width="300">Action</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
</div>
<script>
    var isAdmin = '<?php echo $isAdmin; ?>';

    $(function() {
        makeDataTable()

        function makeDataTable(province_id = '', city_id = '', subdistrict_id = '', kelurahan_id = '', rw_id = '', rt_id = ''){
            const route = '{{ route('role_management.ListWarga.dataTables') }}'
            
            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive:true,
                ajax: {
                    url: route,
                    data:{province_id:province_id, city_id:city_id, subdistrict_id:subdistrict_id, kelurahan_id:kelurahan_id, rw_id:rw_id, rt_id:rt_id}
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', className:'text-center', searchable: false , orderable: false},
                    {data: 'nama', name: 'anggota_keluarga.nama', className:'text-center'},
                    {data: 'nama_blok', name: 'blok.nama_blok', className:'text-center'},
                    {data: 'rt', name: 'rt.rt', className:'text-center'},
                    {data: 'jenis_kelamin', name: 'anggota_keluarga.jenis_kelamin', className:'text-center'},
                    {data: 'hubungan_kel', name: 'hub_keluarga.hubungan_kel', className:'text-center'},
                    {data: 'tahun_lahir', name: 'anggota_keluarga.tgl_lahir', className:'text-center'},
                    {data: 'email', name: 'anggota_keluarga.email', className:'text-center'},
                    {data: 'is_active', name: 'anggota_keluarga.is_active', className:'text-center'},
                    {data: 'action', name: 'action', className:'text-center', searchable: false , orderable: false},
                ],
                "order": [[ 1, "asc" ]], 
            });
            $('[data-toggle="tooltip"]').tooltip()
        }

        $('#filter').click(function(){
            var province_id = $('#province').val();
            var city_id = $('#city').val();
            var subdistrict_id = $('#subdistrict').val();
            var kelurahan_id = $('#kelurahan').val();
            var rw_id = $('#rw').val();
            var rt_id = $('#rt').val();

            if ((isAdmin && province_id == '') || (!isAdmin && rt_id == '')) {
                swal.fire("Informasi", "Anda belum memilih alamat pencarian!", "info");
            } else {
                $('#datatable').DataTable().destroy();
                makeDataTable(province_id, city_id, subdistrict_id, kelurahan_id, rw_id, rt_id);
            }
        });

        $('#reset').click(function(){
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
            if (isAdmin) {
                $('select[name=rt_id]').prop('disabled', true);
            }
            
            $('#datatable').DataTable().destroy();
            makeDataTable();
        });
    });    

    const storage = localStorage.getItem('success');

    if(storage){
        swal({
            title: storage,
            icon: 'success',
        });

        localStorage.removeItem('success');
    } 

    var KTSelect2 = function() {
        // Private functions
        var demos = function() {
			$('select[name=province_id]').select2({ width: '100%', placeholder: '-- Pilih Provinsi --'})
			$('select[name=city_id]').select2({ width: '100%', placeholder: '-- Pilih Kabupaten --'})
			$('select[name=subdistrict_id]').select2({ width: '100%', placeholder: '-- Pilih Kecamatan --'})
			$('select[name=rt_id]').select2({ width: '100%', placeholder: '-- Pilih RT --'})
			$('select[name=rw_id]').select2({ width: '100%', placeholder: '-- Pilih RW --'})
			$('select[name=kelurahan_id]').select2({ width: '100%', placeholder: '-- Pilih Kelurahan --'})

			const spinner = $('<div id="select-spinner" class="spinner spinner-right"></div>');
            const selectCity = $('#city')
            const selectSubdistrict = $('#subdistrict')
            const selectKelurahan = $('#kelurahan')
            const selectRW = $('#rw')
            const selectRT = $('#rt')

            selectCity.html('');
            selectCity.prop("disabled", true);

            selectSubdistrict.html('');
            selectSubdistrict.prop("disabled", true);

            selectKelurahan.html('');
            selectKelurahan.prop("disabled", true);

            selectRW.html('');
            selectRW.prop("disabled", true);

            if (isAdmin) {
                selectRT.html('');
                selectRT.prop("disabled", true);
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