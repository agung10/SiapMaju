@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Jenis Surat Detail
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
					<label>Jenis Permohonan</span></label>
					<input type="text" value="{{ !empty($data->jenis_permohonan) ? $data->jenis_permohonan : '' }}" name="jenis_permohonan" class="form-control" disabled />
				</div>
				<div class="form-group">
					<label>Kode Surat</span></label>
					<input type="text" value="{{ !empty($data->kd_surat) ? $data->kd_surat : '' }}" name="kd_surat" class="form-control" disabled />
				</div>
				<div class="form-group">
					<label>Keterangan</span></label>
					<textarea type="text"name="keterangan" class="form-control" disabled>{{ !empty($data->jenis_permohonan) ? $data->jenis_permohonan : '' }}</textarea>
				</div>
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>
<script>
@endsection