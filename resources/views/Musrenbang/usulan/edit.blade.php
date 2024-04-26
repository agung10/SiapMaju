@extends('layouts.master')

@push('custom-css')
	<style>
		.image-css {
			background-size: contain;
			background-repeat: no-repeat;
			max-width: 130px !important; 
			height: 130px !important;
		}
	</style>
@endpush

@section('content')
<div class="container">
	<div style="z-index: -1;" class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
		<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Ubah Usulan Urusan
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<form id="headerForm" action="{{route('Musrenbang.Usulan-Urusan.update', $data->usulan_urusan_id)}}" enctype="multipart/form-data" method="POST">
			@csrf
			@method('PUT')
			<div class="card-body">
				<div class="row mb-5">
					<div class="col-4">
						@if (\helper::checkUserRole('rw'))
							<label>Ketua RW <span class="text-danger">*</span></label>
						@else
							<label>Warga <span class="text-danger">*</span></label>
						@endif
						
						<select class="form-control" name="user_id" id="ketua_rw">
							<option></option>
							@foreach ($data_user as $res)
								<option value="{{ $res->user_id }}" selected> {{ $res->nama ? $res->nama : $res->username }} </option>
							@endforeach
						</select>
					</div>
					<div class="col-4">
						<label>RW <span class="text-danger">*</span></label>
						<select class="form-control" name="rw_id" id="rw">
							<option></option>
							@foreach ($data_rw as $rw_id => $rw)
							<option value="{{ $rw_id }}" 
								@if(isset($data->rw_id) && !is_array($data->rw_id) && $data->rw_id == $rw_id)
									selected
								@elseif(isset($data->rw_id) && is_array($data->rw_id) && in_array($rw_id, $data->rw_id))
									selected
								@endif
								>  
								{{ $rw }} 
							</option>
							@endforeach
						</select>
					</div>
					<div class="col-4">
						<label>RT <span class="text-danger">*</span></label>
						<select class="form-control" name="rt_id" id="rt">
							<option></option>
							@foreach ($data_rt as $rt_id => $rt)
							<option value="{{ $rt_id }}" 
								@if(isset($data->rt_id) && !is_array($data->rt_id) && $data->rt_id == $rt_id)
									selected
								@elseif(isset($data->rt_id) && is_array($data->rt_id) && in_array($rt_id, $data->rt_id))
									selected
								@endif
								>  
								{{ $rt }} 
							</option>
							@endforeach
						</select>
					</div>
				</div>

				<div class="row mb-5">
					<div class="col-4">
						<label>Jenis Usulan <span class="text-danger">*</span></label>
						<select class="form-control" name="menu_urusan_id" id="jenis_usulan">
							<option></option>
							@foreach ($data_jenis_usulan as $menu_urusan_id => $nama_jenis)
							<option value="{{ $menu_urusan_id }}" 
								@if(isset($data->menu_urusan_id) && !is_array($data->menu_urusan_id) && $data->menu_urusan_id == $menu_urusan_id)
									selected
								@elseif(isset($data->menu_urusan_id) && is_array($data->menu_urusan_id) && in_array($menu_urusan_id, $data->menu_urusan_id))
									selected
								@endif
								>  
								{{ $nama_jenis }} 
							</option>
							@endforeach
						</select>
					</div>
					<div class="col-4">
						<label>Bidang <span class="text-danger">*</span></label>
						<select class="form-control" name="bidang_urusan_id" id="bidang">
							<option></option>
							@foreach ($data_bidang as $bidang_urusan_id => $nama_bidang)
							<option value="{{ $bidang_urusan_id }}" 
								@if(isset($data->bidang_urusan_id) && !is_array($data->bidang_urusan_id) && $data->bidang_urusan_id == $bidang_urusan_id)
									selected
								@elseif(isset($data->bidang_urusan_id) && is_array($data->bidang_urusan_id) && in_array($bidang_urusan_id, $data->bidang_urusan_id))
									selected
								@endif
								>  
								{{ $nama_bidang }} 
							</option>
							@endforeach
						</select>
					</div>
					<div class="col-4">
						<label>Kegiatan <span class="text-danger">*</span></label>
						<select class="form-control" name="kegiatan_urusan_id" id="kegiatan">
							<option></option>
							@foreach ($data_kegiatan as $kegiatan_urusan_id => $nama_kegiatan)
							<option value="{{ $kegiatan_urusan_id }}" 
								@if(isset($data->kegiatan_urusan_id) && !is_array($data->kegiatan_urusan_id) && $data->kegiatan_urusan_id == $kegiatan_urusan_id)
									selected
								@elseif(isset($data->kegiatan_urusan_id) && is_array($data->kegiatan_urusan_id) && in_array($kegiatan_urusan_id, $data->kegiatan_urusan_id))
									selected
								@endif
								>  
								{{ $nama_kegiatan }} 
							</option>
							@endforeach
						</select>
					</div>
					<div class="mt-5 col-12">
						<label>Alamat <span class="text-danger">*</span></label>
						<textarea class="form-control" name="alamat" id="alamat" rows="3">{{ $data->alamat }}</textarea>
					</div>
					<div class="mt-5 col-6">
						<label>Jumlah <span class="text-danger">*</span></label>
						<input type="text" name="jumlah" class="form-control" value="{{ $data->jumlah }}" required />
					</div>
					<div class="mt-5 col-6">
						<label>Tahun <span class="text-danger">*</span></label>
						<input type="text" name="tahun" class="form-control" value="{{ $data->tahun }}" readonly required />
					</div>
					<div class="mt-5 col-12">
						<label>Keterangan <span class="text-danger">*</span></label>
						<textarea class="form-control" name="keterangan" id="keterangan" rows="3">{{ $data->keterangan }}</textarea>
					</div>
				</div>

				<div class="view-gambar image-css col-6" style="background-image: url({{ asset('uploaded_files/musrenbang/'.$data->gambar_1) }})"></div>
				<div class="mt-5 row">
					<div class="mb-5 col-8">
						<label>Gambar Wajib <span class="text-danger">*</span></label>
						<input type="file" name="gambar_1" class="form-control" placeholder="Masukan gambar" accept=".png, .jpg, .jpeg" required/>
					</div>
					<div class="col-4"></div>
					@include('partials.form-file', [
						'title' => __('Gambar Tambahan'),
						'name' => 'gambar_2',
						'multiColumn' => true,
						'text' => true,
						'value' => $data->gambar_2,
						'folder' => 'musrenbang',
					])

					@include('partials.form-file', [
						'title' => __('Gambar Tambahan'),
						'name' => 'gambar_3',
						'multiColumn' => true,
						'text' => true,
						'value' => $data->gambar_3,
						'folder' => 'musrenbang',
					])
				</div>

				<div class="row">
					<div class="mt-5 col-12" id="newMap" style="top: 0; bottom: 0; width: 100%; height: 400px;"></div>

					<div class="mt-5 col-6">
						<label>Latitude <span class="text-danger">*</span></label>
						<input type="text" name="latitude" class="form-control" value={{ $data->latitude }} required />
					</div>
					<div class="mt-5 col-6">
						<label>Longitude <span class="text-danger">*</span></label>
						<input type="text" name="longitude" class="form-control" value={{ $data->longitude }} required />
					</div>
				</div>
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>
</div>

{!! JsValidator::formRequest('App\Http\Requests\Musrenbang\UsulanUrusanRequest','#headerForm') !!}
<script>
	const editData = async () => {
		loading()
		
		const formData = new FormData(document.getElementById('headerForm'));

		const res = async () => {
			return await fetch(`{{route('Musrenbang.Usulan-Urusan.update', '')}}/{{\Request::segment(3)}}`,{
						headers: {"X-CSRF-TOKEN":"{{ csrf_token() }}"},
						method: 'POST',
						body: formData
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
			localStorage.setItem('success','Usulan Urusan Berhasil Diupdate');

			return location.replace('{{route("Musrenbang.Usulan-Urusan.index")}}');
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

	jQuery(document).ready(function() {
		$('select[name=menu_urusan_id]').select2({ width: '100%', placeholder: '-- Pilih Jenis Usulan --'})
		$('select[name=bidang_urusan_id]').select2({ width: '100%', placeholder: '-- Pilih Bidang --'})
		$('select[name=kegiatan_urusan_id]').select2({ width: '100%', placeholder: '-- Pilih Kegiatan --'})
		$('select[name=rw_id]').select2({ width: '100%', placeholder: '-- Pilih RW --'})
		$('select[name=rt_id]').select2({ width: '100%', placeholder: '-- Pilih RT --'})
		$('select[name=user_id]').select2({ width: '100%', placeholder: '-- Pilih Ketua RW --'})

		const spinner = $('<div id="select-spinner" class="spinner spinner-right"></div>');
		const selectRW = $('select[name=rw_id]')
		const selectRT = $('select[name=rt_id]')

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
		});

		$("input[name=tahun]").datepicker({
			format: "yyyy",
			viewMode: "years", 
			minViewMode: "years",
			autoclose: true
		});
    });

	document.querySelector('input[name=gambar_1]').addEventListener('change',(e) => {
		const imageReplace = document.querySelector('.view-gambar')

		const file = e.target.files[0]
		let reader = new FileReader()
		
		reader.onload = () => {
			imageReplace.style.backgroundImage = `url(${reader.result})`;
			imageReplace.classList.add("image-css");
		}

		reader.readAsDataURL(file)
	})
</script>

<script>
	const newMap = L.map('newMap').setView(['{{ $data->latitude }}', '{{ $data->longitude }}'], 15);

	const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
		maxZoom: 19,
		attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
	}).addTo(newMap);

	let marker = L.marker(['{{ $data->latitude }}', '{{ $data->longitude }}']).addTo(newMap).bindPopup(`Latitude: {{ $data->latitude }} <br/> Longitude: {{ $data->longitude }}`).openPopup();

	function onMapClick(e) {
		let latitude = e.latlng.lat.toString().substring(0, 15);
		let longitude = e.latlng.lng.toString().substring(0, 15);

		if (marker != undefined) { newMap.removeLayer(marker); };

		marker = new L.Marker([latitude, longitude], {draggable:true}).addTo(newMap);
		marker.bindPopup(`Latitude: ${latitude} <br/> Longitude: ${longitude}`).openPopup();

		$('input[name=latitude]').val(latitude);
		$('input[name=longitude]').val(longitude);
	}
	newMap.on('click', onMapClick);
</script>
@endsection