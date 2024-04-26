@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Tambah Jenis Surat
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
		<form id="dataForm" action="{{ route('Master.JenisSurat.store') }}" enctype="multipart/form-data" method="POST">
			@csrf
			<div class="card-body">
				<div class="form-group">
					<label>Nama Jenis Permohonan <span class="text-danger">*</span></label>
					<input type="text" name="jenis_permohonan" class="form-control" />
				</div>
				<div class="form-group">
					<label>Kode Surat <span class="text-danger">*</span></label>
					<input type="number" maxlength="2" name="kd_surat" class="form-control" 
						oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
					/>
				</div>
				<div class="form-group">
					<label>Keterangan <span class="text-danger">*</span></label>
					<textarea type="text" name="keterangan" class="form-control"></textarea>
				</div>
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>

{!! JsValidator::formRequest('App\Http\Requests\Master\JenisSuratRequest','#dataForm') !!}
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

		const res = await fetch("{{route('Master.JenisSurat.store')}}",{
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

			localStorage.setItem('success','Jenis Surat Berhasil Ditambahkan');

			return location.replace('{{route("Master.JenisSurat.index")}}');
		}
	}
</script>
@endsection