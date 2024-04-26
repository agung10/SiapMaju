@include('Transaksi.Approval.css')
<div class="container">
    <table class="tableHeader">
        <tr>
            <th style="text-align:left;border-bottom:1px solid;width: 10rem;">Transaksi: <span style="font-weight:normal;">{{$data->nama_transaksi}}</span></th>
            <th style="text-align:left;border-bottom:1px solid;width:7rem">Kegiatan: <span style="font-weight:normal;">{{$data->nama_kegiatan}}</span></th>
        </tr>
        <tr>
            <th style="text-align:left;line-height:25px;">No. Bukti<br> <span style="font-weight:normal;">{{$data->no_bukti}}</span></th>
            <th style="text-align:left;line-height:25px;">Tanggal: <span style="font-weight:normal;">{{$tanggal}}</span></th>
        </tr>
        <tr>
            <th style="text-align:left;line-height:20px;">Nama Kegiatan<br> <span style="font-weight:normal;">({{$data->kode_kat}}) {{$data->nama_kegiatan}}</span></th>
            <th style="text-align:left;line-height:20px;">Nama (Kepala Keluarga)<br> <span style="font-weight:normal;">{{$data->nama}}</span></th>
        </tr>
        <tr >
            <th style="text-align:left;line-height:20px;">No. Pendaftaran<br> <span style="font-weight:normal;">{{$data->no_pendaftaran}}</span></th>
            <th style="text-align:left;line-height:20px;">Alamat<br> <span style="font-weight:normal;">{{$data->alamat}}</span></th>
        </tr>
    </table>

    <table class="tableDetail">
        <thead>
        <tr>
            <th class="border-bottom-right">Nama</th>
            <th class="border-bottom-right">Jenis</th>
            <th class="border-bottom-right">Nilai</th>
            <th class="border-bottom-right">Jumlah</th>
            <th class="border-bottom-right">Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($detailTransaksi as $key => $val)
			<tr style="line-height:20px;">
				<td class="border-bottom-right">{{$val->nama_detail_trx}}</td>
				<td class="border-bottom-right">{{$val->nama_jenis_transaksi}} ({{$val->satuan}})</td>
				<td class="border-bottom-right">{{number_format($val->nilai,0,',','.')}}</td>
				<td class="border-bottom-right">{{$val->jumlah}}</td>
				<td class="border-bottom-right">{{number_format($val->total,0,',','.')}}</td>
			</tr>
		@endforeach
        </tbody>
    </table>

    <h5 class="hormatKami">Hormat Kami,</h5>
    <table class="signature">
        <thead>
            <th style="text-align:left">
                <span style="font-weight:normal;">({{!empty($petugas->nama) ? $petugas->nama 
                                                                            : (!empty($petugas) && $petugas->is_admin === true ? "Admin, $petugas->email" : 'Petugas')}})</span>
            </th>
            <th>
                <span style="font-weight:normal;">({{$data->nama}})</span>
            </th>
        </thead>
    </table>
</div>
