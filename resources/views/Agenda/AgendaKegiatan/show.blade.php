@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Show Agenda
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
		<form id="headerForm" action="#" enctype="multipart/form-data" method="POST">
			<div class="card-body">
				<div class="form-group">
					<label>Nama Agenda <span class="text-danger">*</span></label>
					<input type="text" name="nama_agenda" class="form-control" value="{{ $data->nama_agenda }}" readonly />
				</div>
				<div class="form-group">
					<label>Lokasi <span class="text-danger">*</span></label>
					<input name="lokasi" type="text" class="form-control" value="{{ $data->lokasi }}" readonly />
				</div>
				<div class="form-group">
					<label>Tanggal <span class="text-danger">*</span></label>
					<input name="tanggal" type="text" class="form-control" value="{{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('d F Y') }}" readonly />
				</div>
				<div class="form-group">
					<label>Jam <span class="text-danger">*</span></label>
					<input name="jam" type="text" class="form-control" value="{{ \Carbon\Carbon::createFromFormat('H:i:s',$data->jam)->format('h:i') }}" readonly />
				</div>
				<div class="form-group">
					<label>Agenda <span class="text-danger">*</span></label>
					<textarea name="agenda" class="form-control" rows="3">{{ $data->agenda }}</textarea>
				</div>
				<div class="form-group">
					<label for="exampleSelect1">Program </label>
					<input type="text" class="form-control"  value="{{ $data->nama_program }}" readonly>
				</div>
				<div class="form-group">
					<label for="exampleSelect1">Kategori Sumber Biaya </label>
					<input type="text" class="form-control"  value="{{ $data->nama_sumber ?? 'Belum memilih data' }}" readonly>
				</div>
				<div class="form-group">
					<label for="exampleSelect1">Nilai</label>
					<input type="text" class="form-control" value="{{ \helper::number_formats($data->nilai, 'view_currency') }}" readonly>
				</div>
				<div class="form-group">
					<div class="imageContainer">
						<img class="imagePicture" src="{{ ((!empty($data)) ? ((!empty($data->image)) ? (asset('upload/agenda/'.$data->image)) : (asset('images/NoPic.png'))) : (asset('images/NoPic.png'))) }}"  width="200" height="200">
					</div>
					<label>Gambar / Video</label>
				</div>
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>
@endsection