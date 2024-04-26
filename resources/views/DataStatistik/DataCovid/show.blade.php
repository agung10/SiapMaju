@extends('layouts.master')

@section('content')

<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Detail Data Covid19	                	            </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
			<form id="headerForm" action="{{route('Beranda.Header.store')}}" enctype="multipart/form-data" method="POST">
				@csrf
				<div class="card-body">
					<div class="form-group mb-8">
					</div>
					<div class="form-group">
						<label>Tanggal Input</label>
						<input type="date" value="{{!empty($data->tgl_input) ? $data->tgl_input : ''}}" disabled name="tgl_input" class="form-control" />
					</div>
					<div class="form-group">
						<label>Jumlah Positif </label>
						<input type="text" value="{{!empty($data->jml_positif) ? $data->jml_positif : ''}}" disabled name="jml_positif" class="form-control" />
					</div>
					<div class="form-group">
						<label>Jumlah Sembuh </label>
						<input type="text" value="{{!empty($data->jml_sembuh) ? $data->jml_sembuh : ''}}" disabled name="jml_sembuh" class="form-control" />
					</div>
					<div class="form-group">
						<label>Jumlah Meninggal </label>
						<input type="text" value="{{!empty($data->jml_meninggal) ? $data->jml_meninggal : ''}}" disabled name="jml_meninggal" class="form-control" />
					</div>
					<div class="form-group">
						<label>RT </label>
						<input type="text" value="{{!empty($data->rt) ? $data->rt : ''}}" disabled name="rt" class="form-control" />
					</div>
				</div>
				<div class="card-footer">
					@include('partials.buttons.submit')
				</div>
			</form>
		</div>
<script>

</script>
@endsection