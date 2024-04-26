@extends('layouts.master')

@section('content')
<style>
	.bukti-pembayaran-container{
		display: flex;
		justify-content:flex-end;
		align-items:flex-end;
		flex-direction:column;
	}
</style>
<div class="container">
    <div style='z-index:-1' class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Detail Transaksi Kegiatan
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
	<form class="form" method="post" action="{{route('Transaksi.ApprovalDKM.update',$data->header_trx_kegiatan_id)}}">
		@method('PUT')
		@csrf
		<div class="card-body">
			<div class="form-group row">
				<div class="col-lg-6">
					<label>Transaksi</label>
						<input disabled type="text" value="{{!empty($data->nama_transaksi) ? $data->nama_transaksi : ''}}" class="form-control" placeholder="Transaksi"/>
					<span class="errorHeader"></span>
				</div>
				<div class="col-lg-6">
					<label>Kat Kegiatan</label>
						<input disabled type="text" value="{{!empty($data->nama_kat_kegiatan) ? $data->nama_kat_kegiatan : ''}}" class="form-control" placeholder="Kategori Kegiatan"/>
					<span class="errorHeader"></span>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-lg-12">
					<label>No. Bukti</label>
					<div class="form-group row">
						<div class="col-lg-2">
							<input disabled  value="{{$noBukti}}" type="text" class="form-control buktiNo" placeholder="No"/>
						</div>
						<div class="col-lg-2">
							<input disabled type="text" value="{{!empty($data->kode_kat) ? $data->kode_kat : ''}}" class="form-control buktiKode" placeholder="Kode Kegiatan"/>
						</div>
						<div class="col-lg-2">
							<input disabled type="text" value="{{!empty($data->nama_transaksi) ? $data->nama_transaksi : ''}}" class="form-control buktiJenisTrans" placeholder="Transaksi"/>
						</div>
						<div class="col-lg-3">
							<input disabled type="month" value="{{date('Y-m',strtotime($data->created_at))}}" class="form-control buktiDate" placeholder="Pilih Bulan"/>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-lg-6">
					<label>Nama Kegiatan</label>
						<input disabled type="text" value="{{!empty($data->nama_kegiatan) ? $data->nama_kegiatan : ''}}" class="form-control" placeholder="Nama Kegiatan"/>
						<span class="errorHeader"></span>
				</div>
				<div class="col-lg-6">
					<label>Nama (Kepala Keluarga)</label>
						<input disabled type="text" value="{{!empty($data->nama) ? $data->nama : ''}}" class="form-control" placeholder="Nama Kepala Keluarga"/>
						<span class="errorHeader"></span>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-lg-12">
					<label>No. Pendaftaran</label>
					<div class="form-group row">
						<div class="col-lg-2">
							<input disabled type="text" value="{{$regPendaftaran}}" class="form-control regPendaftaran" placeholder="Reg"/>
						</div>
						<div class="col-lg-2">
							<input disabled type="text" value="{{!empty($data->kode_kat) ? $data->kode_kat : ''}}" class="form-control" placeholder="Kode Kegiatan"/>
							<span class="errorHeader"></span>
						</div>
						<div class="col-lg-3">
							<input type="month" disabled value="{{date('Y-m',strtotime($data->tgl_pendaftaran))}}" class="form-control headerInput monthPendaftaran" placeholder="Pilih Bulan"/>
							<span class="errorHeader"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-lg-6">
					<label>Alamat</label>
					<input disabled type="text" value="{{!empty($data->alamat) ? $data->alamat : ''}}" class="form-control alamat" placeholder="Alamat"/>
				</div>
				@if($data->bukti_pembayaran)
				<div class="col-lg-6 bukti-pembayaran-container">
					<label>Lihat Bukti Pembayaran</label>
					<a href='{{asset("upload/transaksi_kegiatan/$data->bukti_pembayaran")}}' class="btn btn-primary" target="_blank">Download Bukti Pembayaran</a>
				</div>
				@endif
			</div>
		</div>
		<div class="detailTransaksiContainer" style="display:">
			<div class="col-lg-12">
				<h5 class="detailTransText">Detail Transaksi</h5 style=>
			</div>
			<div class="col-lg-12">
					<table class="table table-bordered table-checkable" id="kt_datatable">
						<thead>
							<tr>
								<th class="text-center">Nama</th>
								<th class="text-center">Jenis Transaksi</th>
								<th class="text-center">Nilai</th>
								<th class="text-center">Quantity</th>
								<th class="text-center">Total</th>
							</tr>
						</thead>
						<tbody id="tbodyDetail">
							@foreach($detailTransaksi as $key => $val)
								<tr>
									<td class="text-center">{{$val->nama_detail_trx}}</td>
									<td class="text-center">{{$val->nama_jenis_transaksi}} ({{$val->satuan}})</td>
									<td class="text-center">{{number_format($val->nilai,0,',','.')}}</td>
									<td class="text-center">{{number_format($val->jumlah,0,',','.')}}</td>
									<td class="text-center">{{number_format($val->total,0,',','.')}}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
			</div>
			<div class="row sendButtonContainer">
				<div class="col-lg-6">
				  @if(empty($data->no_bukti))
						<button type="submit" class="btn btn-primary mr-2 approveTransaksi">Approve</button>
					@else
						<button disabled type="submit" class="btn btn-success mr-2 approveTransaksi">Disetujui</button>
				 @endif
				</div>
			</div>
		</div>
	</form>
		<div class="card-footer mt-5">
				<div class="col-lg-6 text-right">
				<button type="reset" onClick="goBack()" class="btn btn-secondary">Back</button>
				</div>
			</div>
		</div>
	</div>


<script>
	if('{{session()->has('error')}}'){
        swal({
            	title: 'Error',
                text: '{{session()->get('error')}}',
                icon:'error',
        });
    }
	$(document).ready( function () {
   		$('#kt_datatable').DataTable();

		const emptyTable = document.querySelector('.dataTables_empty')

		if(emptyTable){
			emptyTable.innerHTML = 'Tidak Ada Data Yang Tersedia'
		}
	});
</script>
@endsection