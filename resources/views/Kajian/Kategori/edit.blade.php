@extends('layouts.master')

@section('content')

<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Edit  Kategori Peraturan	                	            </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
			<form id="headerForm" action="{{route('Kajian.Kategori.store')}}" enctype="multipart/form-data" method="POST">
				@csrf
				@method('PUT')
				<div class="card-body">
					<div class="form-group mb-8">
					</div>
					<div class="form-group">
						<label>Kategori Peraturan</label>
						<input type="text" value="{{!empty($data->kategori) ? $data->kategori : '' }}" name="kategori" class="form-control"  placeholder="Masukan Kategori Peraturan"/>
					</div>
				</div>
				<div class="card-footer">
					@include('partials.buttons.submit')
				</div>
			</form>
		</div>

{!! JsValidator::formRequest('App\Http\Requests\Kajian\KategoriKajianRequest','#headerForm') !!}
<script>

	const editData = async () => {
		loading()

		const formData = new FormData(document.getElementById('headerForm'));

		const res = async () => {
			return await fetch(`{{route('Kajian.Kategori.update','')}}/{{\Request::segment(3)}}`,{
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

			localStorage.setItem('success','Kategori Kajian Berhasil Diupdate');

			return location.replace('{{route("Kajian.Kategori.index")}}');
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