@extends('layouts.master')

@section('content')
@include('LaporanSikad.LaporanTransaksiKegiatan.css')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Laporan Transaksi Kegiatan
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
		<form class="search-form">
			<div class="search-input-container">
				<div class="search-input-wrapper">
					<div class="input-data">
						<p>Kategori Kegiatan<span class="text-danger">*</span></p>
						<select name="kat_kegiatan_id" class="form-control katKegiatan">
						    {!! $selectKatKegiatan !!}
					    </select>
					</div>
					<div class="input-data">
                        <p>Kegiatan<span class="text-danger">*</span></p>
                        <select disabled name="kegiatan_id" class="form-control kegiatan">
                                <option>Pilih Kegiatan</option>
                        </select>
					</div>
				</div>
				<div class="search-input-wrapper">
					<div class="input-data">
                        <p>Start  <span class="text-danger">*</span></p>
						<input disabled type="date" name="start_date" class="form-control">
					</div>
					<div class="input-data">
                        <p>End  <span class="text-danger">*</span></p>
						<input disabled type="date" name="end_date" class="form-control">
					</div>
				</div>
				<div class="button-container">
					<button type="submit" class="btn btn-success font-weight-bolder">Cari</button>
				</div>
			</form>
		</div>
		<div class="result-container">
		</div>
	</div>

{!! JsValidator::formRequest('App\Http\Requests\Laporan\LaporanTransaksiKegiatanRequest','.search-form') !!}
@include('LaporanSikad.LaporanTransaksiKegiatan.indexScript')
@endsection

