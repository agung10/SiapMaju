@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Edit Jenis Transaksi
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
		<form id="headerForm" action="{{ route('Master.JenisTransaksi.update', $data->jenis_transaksi_id) }}"  method="POST">
			@csrf
            {{ method_field('PATCH') }}
			<div class="card-body">
				<div class="form-group">
					<label>Kegiatan <span class="text-danger">*</span></label>
					<select name="kegiatan_id" class="form-control">
						{!! $result !!}
					</select>
					<div style="font-size:10px;margin:5px;color:red" class="err-kegiatan_id"></div>
				</div>
				<div class="form-group">
					<label>Nama Jenis Transaksi<span class="text-danger">*</span></label>
					<input type="text" value="{{ $data->nama_jenis_transaksi }}" name="nama_jenis_transaksi" class="form-control" />
					<div style="font-size:10px;margin:5px;color:red" class="err-nama_jenis_transaksi"></div>
				</div>
				<div class="form-group">
					<label>Satuan<span class="text-danger">*</span></label>
					<input type="text" value="{{ $data->satuan }}" name="satuan" class="form-control" />
					<div style="font-size:10px;margin:5px;color:red" class="err-satuan"></div>
				</div>
				<div class="form-group">
					<label>Nilai</label>
					<input type="text" value="{{ $data->nilai }}" name="nilai" class="form-control" />
					<div style="font-size:10px;margin:5px;color:red" class="err-nilai"></div>
				</div>
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>

{!! JsValidator::formRequest('App\Http\Requests\Master\Transaksi\JenisTransaksiRequest','#headerForm') !!}
<script>
	const editData = async () => {
		loading()

		const formData = new FormData(document.getElementById('headerForm'));

		const res = async () => {
			return await fetch(`{{route('Master.JenisTransaksi.update', '')}}/{{\Request::segment(4)}}`,{
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

			localStorage.setItem('success','Jenis Transaksi Berhasil Diupdate');

			return location.replace('{{route("Master.JenisTransaksi.index")}}');
		}else{

			const {satuan,kegiatan_id,nama_jenis_transaksi} = errors;

			document.querySelector('.err-kegiatan_id').innerHTML = kegiatan_id ? kegiatan_id : '';
			document.querySelector('.err-nama_jenis_transaksi').innerHTML = nama_jenis_transaksi ? nama_jenis_transaksi : '';
			document.querySelector('.err-satuan').innerHTML = satuan ? satuan : '';
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