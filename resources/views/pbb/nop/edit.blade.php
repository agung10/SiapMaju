@extends('layouts.master')

@section('content')
<div class="container">
	@include('partials.breadcumb')
	<div class="card card-custom gutter-b">
		<form id="headerForm" action="{{ route('pbb.nop.update', $data->blok_id) }}" method="POST">
			@csrf
			{{ method_field('PATCH') }}
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Nama Blok <span class="text-danger">*</span></label>
							<select class="form-control" name="blok_id">
								{{!! $resultBlok !!}}
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>NOP</label>
							<input type="text" value="{{ !empty($data->nop) ? $data->nop : '' }}" name="nop"
								class="form-control" />
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

{!! JsValidator::formRequest('App\Http\Requests\Master\NOPRequest','#headerForm') !!}
<script>
	const editData = async () => {	
		loading()

		const formData = new FormData(document.getElementById('headerForm'));

		const res = async () => {
			return await fetch(`{{route('pbb.nop.update', '')}}/{{\Request::segment(3)}}`,{
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
			localStorage.setItem('success','NOP Berhasil Diupdate');
			return location.replace('{{route("pbb.nop.index")}}');
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

    $(document).ready(function() {
        $('select[name=blok_id]').select2({ width: '100%', placeholder: '-- Pilih Blok --'})
    });
</script>
@endsection