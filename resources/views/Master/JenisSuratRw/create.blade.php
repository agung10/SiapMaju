@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Tambah Jenis Surat RW
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
		<form id="dataForm" enctype="multipart/form-data" method="POST">
			@csrf
			<div class="card-body">
				<div class="form-group">
					<label>Jenis Surat RW <span class="text-danger">*</span></label>
					<input type="text" name="jenis_surat" class="form-control" />
				</div>
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>

{!! JsValidator::formRequest('App\Http\Requests\Master\JenisSuratRwRequest','#dataForm') !!}
<script>

	$(document).ready(() => {
		$('#dataForm').on('submit',(e) => {
			e.preventDefault()

		    const valid = $('#dataForm').valid()
			if(!valid) return;

			storeData();
		})
	})

	const storeData = async () => {

		const formData = new FormData(document.getElementById('dataForm'));

		const res = await fetch("{{route('Master.JenisSuratRw.store')}}",{
						headers:{
							"X-CSRF-TOKEN":"{{ csrf_token() }}",
							'X-Requested-With':'XMLHttpRequest',
						},
						method:'post',
						body:formData
					})
					 .then(response => response.json())
					 .catch(() => {
						 Swal.fire('Maaf Terjadi Kesalahan','','error')
						 window.location.reload()
					 })

		const {status,errors} = await res
		
		if(status === 'success'){

			localStorage.setItem('success','Jenis Surat RW Berhasil Ditambahkan');

			return location.replace('{{route("Master.JenisSuratRw.index")}}');
		}
	}
</script>
@endsection