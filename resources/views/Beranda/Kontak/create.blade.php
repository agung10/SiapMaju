@extends('layouts.master')

@section('content')
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Tambah Kontak
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<form id="headerForm" action="{{route('Beranda.Header.store')}}" enctype="multipart/form-data" method="POST">
			@csrf
			<div class="card-body">
				<h4>Data Kontak</h4>
				<hr>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>No Telp <span class="text-danger">*</span></label>
							<input type="text" name="no_telp" class="form-control" />
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>Mobile <span class="text-danger">*</span></label>
							<input type="text" name="mobile" class="form-control" />
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>Nama Lokasi <span class="text-danger">*</span></label>
							<input type="text" name="nama_lokasi" class="form-control" />
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>Email <span class="text-danger">*</span></label>
							<input type="text" name="email" class="form-control" />
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>Web <span class="text-danger">*</span></label>
							<input type="date" name="web" class="form-control" />
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>Rekening <span class="text-danger">*</span></label>
							<input type="date" name="rekening" class="form-control" />
						</div>
					</div>
				</div>

				<h4 class="mt-3">Data Detail Alamat</h4>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Kelurahan</label>
							<select class="form-control" name="kelurahan_id" id="kelurahan">
								{!! $resultKelurahan !!}
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>RW</label>
							<select class="form-control" name="rw_id" id="rw">
								@if ($isAdmin) 
									<option></option>
								@elseif ($isRw || $isRt) 
									{!! $resultRW !!}
								@endif
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>RT</label>
							<select class="form-control" name="rt_id" id="rt">
								@if ($isAdmin) 
									<option></option>
								@elseif ($isRw || $isRt) 
									{!! $resultRT !!}
								@endif
							</select>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label for="exampleTextarea">Alamat <span class="text-danger">*</span></label>
							<textarea name="alamat" class="form-control" id="exampleTextarea" rows="3"></textarea>
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

{!! JsValidator::formRequest('App\Http\Requests\Beranda\KontakRequest','#headerForm') !!}
<script>
	const storeData = async () => {
		loading()

		const formData = new FormData(document.getElementById('headerForm'));

			const res = async () => {
				return await fetch("{{route('Beranda.Kontak.store')}}",{
						headers:{
							"X-CSRF-TOKEN":"{{ csrf_token() }}"
						},
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

			localStorage.setItem('success','Kontak Berhasil Ditambahkan');

			return location.replace('{{route("Beranda.Kontak.index")}}');
		}
	}

	document.querySelector('#headerForm').addEventListener('submit', (e) => {
		e.preventDefault();
		const form  = e.currentTarget
		const valid = $(form).valid()
		if(!valid) return;

		storeData();
	})

	function loading(){
		KTApp.blockPage({
			overlayColor: '#000000',
			state: 'primary',
			message: 'Mohon Tunggu Sebentar'
 		});
	}

	var isAdmin = '<?php echo $isAdmin; ?>';

    var KTSelect2 = function() {
        // Private functions
        var demos = function() {
			$('select[name=kelurahan_id]').select2({ width: '100%', placeholder: '-- Pilih Kelurahan --'})
			$('select[name=rw_id]').select2({ width: '100%', placeholder: '-- Pilih RW --'})
			$('select[name=rt_id]').select2({ width: '100%', placeholder: '-- Pilih RT --'})

			const spinner = $('<div id="select-spinner" class="spinner spinner-right"></div>');
            const selectKelurahan = $('#kelurahan')
            const selectRW = $('#rw')
            const selectRT = $('#rt')

			// Get data from local storage form data
			var formData = localStorage.getItem("temporary_form_data");
			if (formData) {
				localStorage.removeItem("temporary_form_data");
				var formDataParse = JSON.parse(formData)
				
				var valKelurahanId = formDataParse.kelurahan_id;
				var valRWId = formDataParse.rw_id;
				var valRTId = formDataParse.rt_id;
			}

			setTimeout(async () => {
				if (valKelurahanId) {
					$('select[name=kelurahan_id]').val(valKelurahanId).trigger('change');
				}
			}, 100);
			
			if (isAdmin) {
                selectRW.html('');
                selectRW.prop("disabled", true);

                selectRT.html('');
                selectRT.prop("disabled", true);
            }

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
</script>
@endsection