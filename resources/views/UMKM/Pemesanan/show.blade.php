@extends('layouts.master')

@section('content')
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Detail Pemesanan
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
		<form id="headerForm" action="#" enctype="multipart/form-data" method="POST">
			@csrf
			<div class="card-body">
				
					<div class="pb-10 pb-md-20">
						<div class="row">
							<div class="col-md-6">
								<h5 class="font-weight-bolder">Produk</h5>
								<br />
								<div class="d-flex align-items-center">
									<div class="symbol symbol-120 symbol-sm flex-shrink-0">
						                <img class="" src="{{ helper::imageLoad('umkm', $pemesanan->produk->image) }}" alt="photo" />
						            </div>
						            <div class="ml-4">
						                <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">
						                    {{ $pemesanan->nama_produk }}
						                </div>
						                <span class="text-muted font-weight-bold text-hover-primary">
						                    {{ $pemesanan->umkm->nama }}
						                </span>
						            </div>
								</div>
							</div>

							<div class="col-md-6">
								<h5 class="font-weight-bolder">Pembeli</h5>
								<br />
								<div class="d-flex align-items-center">
						            <div class="ml-4">
						                <div class="text-dark-75 font-size-lg mb-0">
						                    <span class="fa fa-user"></span>&nbsp;
						                    {{ $pemesanan->anggota_keluarga->nama }}
						                </div>
						                <br />
						                <div class="text-dark-75 font-size-lg mb-0">
						                    <span class="fa fa-home"></span>&nbsp;
						                    {{ $pemesanan->anggota_keluarga->keluarga->alamat }}
						                </div>
						                <br />
						                <div class="text-dark-75 font-size-lg mb-0">
						                    <span class="fa fa-phone"></span>&nbsp;
						                    {{ $pemesanan->anggota_keluarga->mobile }}
						                </div>
						            </div>
								</div>
							</div>
						</div>
					</div>


					<div class="pb-10 pb-md-20">
						<div class="row">
							<div class="col-md-6">
								<h5 class="font-weight-bolder">Transaksi</h5>
								<br />
								<div class="d-flex align-items-center">
						            <div class="ml-4">
						                <div class="text-dark-75 font-size-lg mb-0">
						                    <span class="font-weight-bolder">
						                    	Harga:
						                    </span>
						                    <span class="ml-4">
						                     Rp. {{ helper::number_formats($pemesanan->harga_produk, 'view', 0) }}
							                 </span>
						                </div>
						                <br />
						                <div class="text-dark-75 font-size-lg mb-0">
						                    <span class="font-weight-bolder">
						                    	Jumlah:
						                    </span>
						                    <span class="ml-4">
						                    {{ $pemesanan->jumlah }}
							                 </span>
						                </div>
						                <br />
						                <div class="text-dark-75 font-size-lg mb-0">
						                    <span class="font-weight-bolder">
						                    	Total:
						                    </span>
						                    <span class="ml-4">
						                    Rp. {{ helper::number_formats($pemesanan->total, 'view', 0) }}
							                 </span>
						                </div>
						            </div>
								</div>
							</div>

							<div class="col-md-6">
								<h5 class="font-weight-bolder">Detail</h5>
								<br />
								<div class="d-flex align-items-center">
						            <div class="ml-4">
						                <div class="text-dark-75 font-size-lg mb-0">
						                    <span class="font-weight-bolder">
						                    	Waktu Pemesanan:
						                    </span>
						                    <span class="ml-4">
						                     {{ date('d F Y (H:i)', strtotime($pemesanan->created_at)) }}
							                 </span>
						                </div>
						                <br />
						                <div class="text-dark-75 font-size-lg mb-0">
						                    <span class="font-weight-bolder">
						                    	Status:
						                    </span>
						                    <span class="ml-4">
						                    	{!! $pemesanan->status !!}
							                 </span>
						                </div>
						            </div>
								</div>
							</div>
						</div>
					</div>
			</div>
			<div class="card-footer">
				@include('partials.buttons.submit')
			</div>
		</form>
	</div>
<script>
@endsection