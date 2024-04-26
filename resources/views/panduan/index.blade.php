@extends('layouts.master')

@section('content')
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Panduan
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		@include('partials.alert')
		<form id="formPanduan" action="{{ route('panduan.update') }}" enctype="multipart/form-data" method="POST">
			@csrf
			<div class="card-body">
				<h4>Upload Panduan</h4>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>File Panduan</label>
							<div class="custom-file">
								<input name="panduan" type="file" class="custom-file-input" accept="application/pdf" />
								<label id="label-upload-materi" class="custom-file-label" for="customFile">Choose file </label>
							</div>
							<span class="form-text text-muted">Allowed file types:  pdf. Max 10MB</span>
						</div>
					</div>
					<div class="col-md-12">
						<a target="_blank" href="{{ asset('uploaded_files/panduan/panduan.pdf') }}" >
							<span class="far fa-file-pdf"></span>&nbsp; Preview
						</a>
					</div>
				</div>
			</div>
			<div class="card-footer">
				<button type="submit" class="btn btn-primary mr-2 submit">Submit</button>	
			</div>
		</form>
	</div>
</div>

{!! JsValidator::formRequest('App\Http\Requests\Panduan\PanduanRequest','#formPanduan') !!}
@endsection