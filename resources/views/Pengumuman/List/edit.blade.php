@extends('layouts.master')

@section('content')

<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Edit Pengumuman 
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<form id="headerForm" action="{{route('Pengumuman.List.update','')}}" enctype="multipart/form-data"
			method="POST">
			@csrf
			@method('PUT')
			<div class="card-body">
				<h4>Data Pengumuman</h4>
				<hr>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Pengumuman <span class="text-danger">*</span></label>
							<textarea name="pengumuman" class="form-control"
								rows="3">{{!empty($data->pengumuman) ? $data->pengumuman : ''}}</textarea>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<div class="imageContainerPengumuman">
								<img class="imagePicturePengumuman"
									src="{{!empty($data->image1) ? asset('upload/pengumuman/'.$data->image1) : asset('images/NoPic.png')}}" />
							</div>
							<label>Gambar Pengumuman 1</label>
							<div></div>
							<div class="custom-file">
								<input name="image1" type="file" class="custom-file-input" id="customFile" />
								<label class="custom-file-label" for="customFile">Choose file </label>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<div class="imageContainerPengumuman">
								<img class="imagePicturePengumuman"
									src="{{!empty($data->image2) ? asset('upload/pengumuman/'.$data->image2) : asset('images/NoPic.png')}}" />
							</div>
							<label>Gambar Pengumuman 2</label>
							<div></div>
							<div class="custom-file">
								<input name="image2" type="file" class="custom-file-input" id="customFile" />
								<label class="custom-file-label" for="customFile">Choose file </label>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<div class="imageContainerPengumuman">
								<img class="imagePicturePengumuman"
									src="{{!empty($data->image3) ? asset('upload/pengumuman/'.$data->image3) : asset('images/NoPic.png')}}" />
							</div>
							<label>Gambar Pengumuman 3</label>
							<div></div>
							<div class="custom-file">
								<input name="image3" type="file" class="custom-file-input" id="customFile" />
								<label class="custom-file-label" for="customFile">Choose file </label>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>Tanggal <span class="text-danger">*</span></label>
							<input type="date" value="{{!empty($data->tanggal) ? $data->tanggal : ''}}" name="tanggal"
								class="form-control" />
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label for="exampleSelect1">Status <span class="text-danger">*</span></label>
							<select name="aktif" class="form-control" id="exampleSelect1">
								<option disabled selected></option>
								<option value="1" {{$data->aktif === true ? 'selected' : ''}}>Aktif</option>
								<option value="0" {{$data->aktif === false ? 'selected' : ''}}>Tidak Aktif</option>
							</select>
						</div>
					</div>
				</div>

				<h4 class="mt-3">Data Detail Alamat</h4>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Kelurahan<span class="text-danger">*</span></label>
							<select class="form-control" name="kelurahan_id" id="kelurahan">
								{{!! $resultKelurahan !!}}
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>RW<span class="text-danger">*</span></label>
							<select class="form-control" name="rw_id" id="rw">
								{{!! $resultRW !!}}
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>RT<span class="text-danger">*</span></label>
							<select class="form-control" name="rt_id" id="rt">
								{{!! $resultRT !!}}
							</select>
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

{!! JsValidator::formRequest('App\Http\Requests\Pengumuman\PengumumanRequest','#headerForm') !!}
<script>
	const editData = async () => {
		loading()
		
		const formData = new FormData(document.getElementById('headerForm'));

		const res = async () => {
			return await fetch(`{{route('Pengumuman.List.update','')}}/{{\Request::segment(3)}}`,{
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

			localStorage.setItem('success','Pengumuman Berhasil Diupdate');

			return location.replace('{{route("Pengumuman.List.index")}}');
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

    var KTSelect2 = function() {
        // Private functions
        var demos = function() {
			$('select[name=aktif]').select2({ width: '100%', placeholder: '-- Pilih Status --'})
			$('select[name=kelurahan_id]').select2({ width: '100%', placeholder: '-- Pilih Kelurahan --'})
			$('select[name=rw_id]').select2({ width: '100%', placeholder: '-- Pilih RW --'})
			$('select[name=rt_id]').select2({ width: '100%', placeholder: '-- Pilih RT --'})
			
			const spinner = $('<div id="select-spinner" class="spinner spinner-right"></div>');
            const selectKelurahan = $('#kelurahan')
            const selectRW = $('#rw')
            const selectRT = $('#rt')

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