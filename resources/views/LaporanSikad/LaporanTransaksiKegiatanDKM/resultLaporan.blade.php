<table class="table table-bordered table-checkable table-search-result">
  <thead>
    <tr>
      <th width="5%">No.</th>
      <th>Transaksi</th>
      <th>Kategori Kegiatan</th>
      <th>No Pendaftaran</th>
      <th>Keluarga</th>
    </tr>
  </thead>
  <tbody>
    @foreach($laporan as $key => $val)
        <tr> 
            <td>{{$loop->iteration}}</td>
            <td>{{$val->nama_transaksi}}</td>
            <td>{{$val->nama_kat_kegiatan}}</td>
            <td>{{$val->no_pendaftaran}}</td>
            <td>{{$val->nama}}</td>
        </tr>
    @endForeach
  </tbody>
</table>
