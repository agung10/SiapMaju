@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Tambah Data Covid	                	            </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
	<form id="headerForm" action="{{route('DataStatistik.DataCovid.store')}}" enctype="multipart/form-data" method="POST">
				@csrf
				<div class="card-body">
					<div class="form-group mb-8">
					</div>
					<div class="form-group">
						<label>Tanggal Input<span class="text-danger">*</span></label>
						<input type="date" name="tgl_input" class="form-control" />
					</div>
					<div class="form-group">
						<label>Jumlah Positif <span class="text-danger">*</span></label>
						<input type="text" name="jml_positif" class="form-control" />
					</div>
					<div class="form-group">
						<label>Jumlah Sembuh <span class="text-danger">*</span></label>
						<input type="text" name="jml_sembuh" class="form-control" />
					</div>
					<div class="form-group">
						<label>Jumlah Meninggal <span class="text-danger">*</span></label>
						<input type="text" name="jml_meninggal" class="form-control" />
					</div>
					<div class="form-group">
						<label>RT<span class="text-danger">*</span></label>
							<select class="form-control rt" name="rt_id">
										{!! \helper::select('rt','rt') !!}
							</select>
					</div>
				</div>
				<div class="card-footer">
					@include('partials.buttons.submit')
				</div>
			</form>
		</div>

{!! JsValidator::formRequest('App\Http\Requests\DataStatistik\DataCovidRequest','#headerForm') !!}
<script>

	//Select Script
	const selectRT = document.querySelector('.rt')

	selectPlaceholder(selectRT, 'RT')

	function selectPlaceholder(selector,name){
		selector.childNodes[1].innerHTML = `Pilih ${name}`
		selector.childNodes[1].setAttribute('disabled','')
	}
	//

	const storeData = async () => {
		loading()

		const formData = new FormData(document.getElementById('headerForm'));

			const res = async () => {
				return await fetch("{{route('DataStatistik.DataCovid.store')}}",{
						headers:{
							"X-CSRF-TOKEN":"{{ csrf_token() }}"
						},
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

			localStorage.setItem('success','Data Covid Berhasil Ditambahkan');

			return location.replace('{{route("DataStatistik.DataCovid.index")}}');
		}
	}

	document.querySelector('#headerForm').addEventListener('submit', (e) => {
		e.preventDefault();
		const form  = e.currentTarget
		const valid = $(form).valid()
		if(!valid) return;

		storeData();
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