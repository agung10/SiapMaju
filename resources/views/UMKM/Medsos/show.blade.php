@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Detail Media Sosial
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
		<form id="headerForm" action="#" enctype="multipart/form-data" method="POST">
			@csrf
			<div class="card-body">
				<div class="form-group">
					<div class="imageContainer mx-auto d-block">
						<img class="imagePicture mx-auto d-block" src="{{ ((!empty($data)) ? ((!empty($data->icon)) ? (asset('uploaded_files/medsos/'.$data->icon)) : (asset('images/NoPic.png'))) : (asset('images/NoPic.png'))) }}"  width="50" height="50"></img>
					</div>
					<label class="l-center">Icon Media</label>
				</div>
				<div class="form-group">
					<label for="exampleSelect1">Nama Media</label>
					<input type="text" class="form-control" readonly value="{{ ((!empty($data)) ? ($data->nama): ('')) }}"> 
				</div>
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>

@endsection