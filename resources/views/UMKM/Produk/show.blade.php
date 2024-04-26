@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Detail Produk
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
                        <img class="imagePicture mx-auto d-block" src="{{ ((!empty($produk)) ? ((!empty($produk->image)) ? (asset('uploaded_files/umkm/'.$produk->image)) : (asset('images/NoPic.png'))) : (asset('images/NoPic.png'))) }}"
                            width="50" height="50"></img>
                    </div>
                    <label class="l-center">Gambar</label>					
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="exampleSelect1">Nama Produk</label>
							<input type="text" name="nama" class="form-control" value="{{ $produk->nama }}" readonly>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>UMKM</label>
                            <input type="text" name="nama_umkm" class="form-control" value="{{ $produk->nama_umkm }}" readonly>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Kategori</label>
                            <input type="text" name="kategori" class="form-control" value="{{ $produk->kategori }}" readonly>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<label for="deskripsi">Deskripsi</label>
						<textarea type="text" name="deskripsi" class="form-control" rows="5" readonly>{{ $produk->deskripsi }}</textarea>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="exampleSelect1">Harga</label>
							<input type="text" name="harga" class="form-control" value="{{ \helper::number_formats($produk->harga, 'view') }}" readonly>
						</div>
					</div>
                    <div class="col-md-6">
						<div class="form-group">
							<label for="exampleSelect1">Stok</label>
							<input type="text" name="stok" class="form-control" value="{{ $produk->stok }}" readonly>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="exampleSelect1">Berat (gram)</label>
							<input type="text" name="berat" class="form-control" value="{{ $produk->berat }}" readonly>
						</div>
					</div>
                    <div class="col-md-6">
						<div class="form-group">
							<label>Status</label>
                            @if($produk->aktif)
								<input type="text" class="form-control bg-light-primary" value="Aktif" readonly>
							@else
								<input type="text" class="form-control bg-light-danger" value="Tidak Aktif" readonly>
                            @endif
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Siap Dipesan</label>
                            <input type="text" class="form-control" value="{{ $produk->siap_dipesan ? 'Ya' : 'Tidak' }}" readonly>
						</div>
					</div>
				</div>

				@if(sizeof($produkImage) > 0)
                    <div class="separator separator-dashed my-8"></div>
                        <div id="sosmed-repeater">
                            <div class="form-group">
                                <h3 class="card-label">
                                    Gambar Produk
                                </h3>
                            </div>
                        </div>
                    <div class="row">
                    @foreach($produkImage as $key => $val)
                      @include('partials.form-file', [
                        'title'       => __('Gambar Produk ' . $loop->iteration),
                        'name'        => 'image', 
                        'multiColumn' => true,
                        'value'       => $val->file_image,
                        'folder'      => 'umkm/umkm_image',
                        'disabled'    => true
                      ])
                    @endforeach
                    </div>
                @endif
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
    </div>
</div>
@endsection