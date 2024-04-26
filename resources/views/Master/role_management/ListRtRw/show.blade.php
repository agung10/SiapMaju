@extends('layouts.master')

@section('content')

<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Detail Header	                	            </h5>
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
						<label>Judul </label>
						<input type="text" value="{{!empty($data->judul) ? $data->judul : '' }}" readonly name="judul" class="form-control"  placeholder="Masukan Judul"/>
						<div style="font-size:10px;margin:5px;color:red" class="err-judul"></div>
					</div>
					<div class="form-group mb-1">
						<label for="exampleTextarea">Keterangan </label>
						<textarea name="keterangan" class="form-control" readonly id="exampleTextarea" rows="3">{{!empty($data->keterangan) ? $data->keterangan : '' }}</textarea>
						<div style="font-size:10px;margin:5px;color:red" class="err-keterangan"></div>
					</div>
                    <div class="form-group">
						<label>Gambar Header</label>
						<div></div>
						<div class="imageContainer">
							<img class="imagePicture" src="{{asset('upload/header/'.$data->image)}}" />
						</div>
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