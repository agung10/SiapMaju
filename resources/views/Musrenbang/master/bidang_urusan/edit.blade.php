@extends('layouts.master')
@section('content')
<div class="container">
	<div style="z-index: -1;" class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
		<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Ubah Bidang Urusan
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<form id="headerForm" action="{{route('Musrenbang.Bidang-Urusan.update', $data->bidang_urusan_id)}}" enctype="multipart/form-data" method="POST">
			@csrf
			@method('PUT')
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Nama Bidang <span class="text-danger">*</span></label>
							<input type="text" name="nama_bidang" class="form-control" placeholder="Masukan nama bidang" value="{{ $data->nama_bidang }}" required />
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

{!! JsValidator::formRequest('App\Http\Requests\Musrenbang\BidangUrusanRequest','#headerForm') !!}
<script>
	const editData = async () => {
		loading()
		
		const formData = new FormData(document.getElementById('headerForm'));

		const res = async () => {
			return await fetch(`{{route('Musrenbang.Bidang-Urusan.update', '')}}/{{\Request::segment(3)}}`,{
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
			localStorage.setItem('success','Bidang Urusan Berhasil Diupdate');

			return location.replace('{{route("Musrenbang.Bidang-Urusan.index")}}');
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
</script>
@endsection