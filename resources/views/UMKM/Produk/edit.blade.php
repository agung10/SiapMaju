@extends('layouts.master')

@section('content')
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Edit Produk
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<form id="headerForm" action="{{ route('UMKM.Produk.update', $produk->umkm_produk_id) }}"
			enctype="multipart/form-data" method="POST">
			@csrf
			{{ method_field('PATCH') }}
			<div class="card-body">
				<div class="form-group">
					<div class="imageContainer">
						<img class="imagePicture"
							src="{{ ((!empty($produk)) ? ((!empty($produk->image)) ? (asset('uploaded_files/umkm/'.$produk->image)) : (asset('images/NoPic.png'))) : (asset('images/NoPic.png'))) }}"
							width="200" height="200"></img>
					</div>
					<br>
					<label>Gambar<span class="text-danger">*</span></label>
					<div class="custom-file">
						<input name="image" type="file" class="custom-file-input" id="customFile" />
						<label class="custom-file-label" for="customFile">{{ $produk->image }}</label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="exampleSelect1">Nama Produk<span class="text-danger">*</span></label>
							<input type="text" name="nama" class="form-control" value="{{ $produk->nama }}">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>UMKM<span class="text-danger">*</span></label>
							<select class="form-control" name="umkm_id">
								{!! $resultUmkm !!}
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Kategori<span class="text-danger">*</span></label>
							<select class="form-control" name="umkm_kategori_id">
								{!! $resultKategori !!}
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<label for="deskripsi">Deskripsi<span class="text-danger">*</span></label>
						<textarea type="text" name="deskripsi" class="form-control"
							rows="5">{{ $produk->deskripsi }}</textarea>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="exampleSelect1">Url<span class="text-danger">*</span></label>
							<input type="text" name="url" class="form-control" value="{{ $produk->url }}">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="exampleSelect1">Harga<span class="text-danger">*</span></label>
							<input type="text" name="harga" class="form-control"
								value="{{\helper::number_formats($produk->harga, 'view')}}">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="exampleSelect1">Stok<span class="text-danger">*</span></label>
							<input type="number" name="stok" class="form-control" value="{{ $produk->stok }}">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="exampleSelect1">Berat (gram)<span class="text-danger">*</span></label>
							<input type="number" name="berat" class="form-control" value="{{ $produk->berat }}">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Status<span class="text-danger">*</span></label>
							<select class="form-control" name="aktif">
								<option></option>
								<option selected disabled></option>
								<option value="1" {{$produk->aktif == '1' ? 'selected' : ''}}>Aktif</option>
								<option value="0" {{$produk->aktif == '0' ? 'selected' : ''}}>Tidak Aktif</option>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Siap Dipesan<span class="text-danger">*</span></label>
							<select class="form-control" name="siap_dipesan">
								<option></option>
								<option value="1" {{$produk->siap_dipesan == '1' ? 'selected' : ''}}>Ya</option>
								<option value="0" {{$produk->siap_dipesan == '0' ? 'selected' : ''}}>Tidak</option>
							</select>
						</div>
					</div>
				</div>
				<div class="separator separator-dashed my-8"></div>
				<div id="sosmed-repeater">
					<div class="form-group">
						<h3 class="card-label">
							Gambar Produk
						</h3>
					</div>
				</div>

				<div class="row">
					@if(sizeof($produkImage) > 0)
						@foreach($produkImage as $key => $val)
							@include('partials.form-file', [
								'title' => __('Gambar Produk'),
								'name' => 'file_image_'.$key,
								'multiColumn' => true,
								'text' => true,
								'value' => $val->file_image,
								'folder' => 'umkm/umkm_image',
							])
							<input name="umkm_image_id" type="hidden" value="{{$val->umkm_image_id}}">
						@endforeach
					@endif
					@for ($i = sizeof($produkImage); $i < 3; $i++) 
						@include('partials.form-file', [ 
							'title'=> __('Gambar Produk'),
							'name' => 'file_image_add_'.$i,
							'multiColumn' => true,
							'text' => true,
						])
						<input name="umkm_image_id" type="hidden" value="none">
					@endfor
				</div>
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>
</div>

{!! JsValidator::formRequest('App\Http\Requests\UMKM\UMKMProdukRequest','#headerForm') !!}
<script>
	const editData = async () => {
		loading()

		const formData = new FormData(document.getElementById('headerForm'));

		const res = async () => {
			return  await fetch(`{{route('UMKM.Produk.update','')}}/{{\Request::segment(3)}}`,{
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
			localStorage.setItem('success','Produk Berhasil Diupdate');
			return location.replace('{{route("UMKM.Produk.index")}}');
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

	// Image Preview Script
	const imageAgendaField = document.querySelector('input[name="image"]')

	if(imageAgendaField){
		imageAgendaField.addEventListener('change',(event)=> {
			const output = document.querySelector('.imagePicture');

			output.src = URL.createObjectURL(event.target.files[0]);
			output.onload = () => {
				URL.revokeObjectURL(output.src)
			}
		});
	}

	// Class Select2 definition
	var KTSelect2 = function() {
		// Private functions
		var demos = function() {
			// basic
			$('select[name=umkm_id]').select2({ placeholder: '-- Pilih UMKM --' });
			$('select[name=umkm_kategori_id]').select2({ placeholder: '-- Pilih Kategori --' });
			$('select[name=aktif]').select2({ placeholder: '-- Pilih Status --' });
			$('select[name=siap_dipesan]').select2({ placeholder: '-- Siap Dipesan --' });
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

@include('UMKM.Produk.script2')
@endsection