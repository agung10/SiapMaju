@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Edit Kategori Kegiatan
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
		<form id="headerForm" action="{{ route('Master.KatKegiatan.update', $data->kat_kegiatan_id) }}"  method="POST">
			@csrf
            {{ method_field('PATCH') }}
			<div class="card-body">
				<div class="form-group">
					<label>Nama Kategori<span class="text-danger">*</span></label>
					<input type="text" value="{{ $data->nama_kat_kegiatan }}" name="nama_kat_kegiatan" class="form-control" />
				</div>
				<div class="form-group">
					<label>Kode<span class="text-danger">*</span></label>
					<input type="text" value="{{ $data->kode_kat }}" name="kode_kat" class="form-control" />
				</div>
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>

{!! JsValidator::formRequest('App\Http\Requests\Kegiatan\KatKegiatanRequest','#headerForm') !!}
<script>
	const editData = async () => {
		loading()

		const formData = new FormData(document.getElementById('headerForm'));

		const res = async () => {
			return await fetch(`{{route('Master.KatKegiatan.update', '')}}/{{\Request::segment(4)}}`,{
						headers:{"X-CSRF-TOKEN":"{{ csrf_token() }}"},
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

			localStorage.setItem('success','Kategori Kegiatan Berhasil Diupdate');

			return location.replace('{{route("Master.KatKegiatan.index")}}');
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