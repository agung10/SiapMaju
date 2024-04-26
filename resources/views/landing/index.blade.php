@extends('layouts.master')
@section('content')

<style>
   .content {
      padding: 0px;
   }

   .top-distance {
      margin-top: -20px;
   }

   @media screen and (max-width:600px) {
      .row-dashboard {
         padding: 10px;
      }
   }
</style>

<div class="container">
   @if ($checkAdmin)
   <div class="d-flex flex-column mb-5 top-distance">
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
                  <form id="headerForm">
                     <div class="row">
                        <div class="col-md-4">
                           <div class="form-group">
                              <select name="province_id" class="form-control border-0 font-weight-bold" id="province">
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

   @include('dashboard.dashboard1')
   @include('dashboard.dashboard2')
   @include('dashboard.dashboard3')
</div>

<script>
   var urlParams = new URLSearchParams(window.location.search);
   var valProvinceId = urlParams.get('province_id');
   var valCityId = urlParams.get('city_id');
   var valSubdistrictId = urlParams.get('subdistrict_id');
   var valKelurahanId = urlParams.get('kelurahan_id');
   var valRWId = urlParams.get('rw_id');
   var valRTId = urlParams.get('rt_id');

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
         const selectProvince = $('#province')
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

         selectRT.html('');
         selectRT.prop("disabled", true);
         
         setTimeout(async () => {
            await selectProvince.val(valProvinceId).trigger('change')
         }, 100);

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

               if(valCityId)
					{
						$('select[name=city_id]').val(valCityId).trigger('change');
					}
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

               if(valSubdistrictId)
					{
						$('select[name=subdistrict_id]').val(valSubdistrictId).trigger('change');
					}
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

               if(valKelurahanId)
					{
						$('select[name=kelurahan_id]').val(valKelurahanId).trigger('change');
					}
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

               if(valRWId)
					{
						$('select[name=rw_id]').val(valRWId).trigger('change');
					}
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

               if(valRTId)
					{
						$('select[name=rt_id]').val(valRTId).trigger('change');
					}
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

   var requestPage = @json(\Request::getRequestUri());
   var conditions = 
      requestPage == `/search?province_id=${valProvinceId}` || 
      requestPage == `/search?province_id=${valProvinceId}&city_id=${valCityId}` || 
      requestPage == `/search?province_id=${valProvinceId}&city_id=${valCityId}&subdistrict_id=${valSubdistrictId}` || 
      requestPage == `/search?province_id=${valProvinceId}&city_id=${valCityId}&subdistrict_id=${valSubdistrictId}&kelurahan_id=${valKelurahanId}` ||
      requestPage == `/search?province_id=${valProvinceId}&city_id=${valCityId}&subdistrict_id=${valSubdistrictId}&kelurahan_id=${valKelurahanId}&rw_id=${valRWId}` ||
      requestPage == `/search?province_id=${valProvinceId}&city_id=${valCityId}&subdistrict_id=${valSubdistrictId}&kelurahan_id=${valKelurahanId}&rw_id=${valRWId}&rt_id=${valRTId}`;

   $('#filter').click(function(e){
      e.preventDefault();
      var province_id = $('#province').val();
      var form = $('#headerForm');

      if (province_id == '') {
            swal.fire("Informasi", "Anda belum memilih alamat pencarian!", "info");
      } else {
         form.attr('action', '/search');
         if (conditions) {
            $(this).closest('form').submit();
         } else {
            $(this).closest('form').attr('target', '_blank').submit();
         }
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
      $('select[name=rt_id]').prop('disabled', true);
   });
</script>
@endsection