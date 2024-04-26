@extends('layouts.master')

@section('content')

<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Tambah Header	                	            </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
	<form id="dataForm" action="{{ route('DataStatistik.DataCovid.update', $data->covid19_id) }}" enctype="multipart/form-data" method="POST">
				@csrf
				@method('PUT')
				<div class="card-body">
					<div class="form-group mb-8">
					</div>
					<div class="form-group">
						<label>Tanggal Input<span class="text-danger">*</span></label>
						<input type="date" value="{{!empty($data->tgl_input) ? $data->tgl_input : ''}}" name="tgl_input" class="form-control" />
					</div>
					<div class="form-group">
						<label>Jumlah Positif <span class="text-danger">*</span></label>
						<input type="text" value="{{!empty($data->jml_positif) ? $data->jml_positif : ''}}" name="jml_positif" class="form-control" />
					</div>
					<div class="form-group">
						<label>Jumlah Sembuh <span class="text-danger">*</span></label>
						<input type="text" value="{{!empty($data->jml_sembuh) ? $data->jml_sembuh : ''}}" name="jml_sembuh" class="form-control" />
					</div>
					<div class="form-group">
						<label>Jumlah Meninggal <span class="text-danger">*</span></label>
						<input type="text" value="{{!empty($data->jml_meninggal) ? $data->jml_meninggal : ''}}" name="jml_meninggal" class="form-control" />
					</div>
					<div class="form-group">
						<label>RT<span class="text-danger">*</span></label>
							<select class="form-control rt" name="rt_id">
										{!! \helper::select('rt','rt',$data->rt_id) !!}
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

		const res = await fetch(`{{route('DataStatistik.DataCovid.update', '')}}/{{\Request::segment(3)}}`,{
						headers:{
							"X-CSRF-TOKEN":"{{ csrf_token() }}",
							'X-Requested-With':'XMLHttpRequest'
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

			localStorage.setItem('success','Data Covid Berhasil di Update');

			return location.replace('{{route("DataStatistik.DataCovid.index")}}');
		}
	}
</script>
@endsection