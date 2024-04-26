@extends('layouts.master')

@section('content')
@include('Master.Keluarga.ListKeluarga.css')
<div class="container">
	<div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<div class="d-flex align-items-center flex-wrap mr-1">
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						Tambah Kepala Keluarga
					</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-custom gutter-b">
		<div class="card card-custom">
			<!--begin::Header-->
			<div class="card-header card-header-tabs-line">
				<ul class="nav nav-dark nav-bold nav-tabs nav-tabs-line" data-remember-tab="tab_id" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#data_baru"><strong>Tambah Data Baru</strong></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#data_lama"><strong>Tambah Data Lama (Anggota Keluarga Menjadi Kepala Keluarga)</strong></a>
					</li>
				</ul>
			</div>
			<!--end::Header-->
			<div class="card-body">
				<div class="tab-content pt-3">
					<!--begin::Tab Pane-->
					<div class="tab-pane active" id="data_baru">
						@include('Master.Keluarga.ListKeluarga.kk_baru')
					</div>
					<!--end::Tab Pane-->

					<!--begin::Tab Pane-->
					<div class="tab-pane" id="data_lama">
						@include('Master.Keluarga.ListKeluarga.kk_lama')
					</div>
					<!--end::Tab Pane-->
				</div>
			</div>	
		</div>
	</div>
</div>

{!! JsValidator::formRequest('App\Http\Requests\Master\KeluargaRequest','.form-keluarga') !!}
{!! JsValidator::formRequest('App\Http\Requests\Master\KeluargaLamaRequest','.form-keluarga-lama') !!}
{!! JsValidator::formRequest('App\Http\Requests\Master\AnggotaKeluargaRequest','#anggota-form') !!}
@include('Master.Keluarga.ListKeluarga.createScript')
@endsection