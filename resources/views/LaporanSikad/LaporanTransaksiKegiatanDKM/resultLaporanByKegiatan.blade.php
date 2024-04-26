<style>
  table{
    text-align:center;
  }
</style>
<table class="table table-bordered table-checkable table-search-result table-responsive">
  <thead>
    <tr >
      <th rowspan="2">No.</th>
      <th rowspan="2">Jenis Transaksi</th>
      <th rowspan="2">Kategori Kegiatan</th>
      <th rowspan="2">No Kwitansi</th>
      <th rowspan="2">Nama Warga</th>
      <th rowspan="2">Jenis Barang</th>
      <th colspan="2">Masuk</th>
      <th colspan="2">Keluar</th>
    </tr>
    <tr>
        <th>Tanggal</th>
        <th>Nilai</th>
        <th>Tanggal</th>
        <th>Nilai</th>
    </tr>
  </thead>
  <tbody>
    @foreach($laporan as $key => $val)
        <tr> 
            <td>{{$loop->iteration}}</td>
            <td>{{$val->nama_transaksi}}</td>
            <td>{{$val->nama_kat_kegiatan}}</td>
            <td>{{$val->no_pendaftaran}}</td>
            <td>{{$val->nama_detail_trx}}</td>
            <td>{{$val->nama_jenis_transaksi}} ({{$val->satuan}})</td>
            <td>{{$val->nama_transaksi == 'Masuk' ? $val->tgl_pendaftaran : ''}}</td>
            <td class="nilai-masuk">{{$val->nama_transaksi == 'Masuk' ? $val->total : ''}}</td>
            <td>{{$val->nama_transaksi == 'Keluar' ? $val->tgl_pendaftaran : ''}}</td>
            <td class="nilai-keluar">{{$val->nama_transaksi == 'Keluar' ? $val->total : ''}}</td>
        </tr>
    @endForeach
  </tbody>
</table>
