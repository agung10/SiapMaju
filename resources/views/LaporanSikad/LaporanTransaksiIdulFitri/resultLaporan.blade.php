<style>
  table{
    text-align:center;
  }
</style>

<table class="table table-bordered table-checkable table-search-result table-responsive">
  <thead>
    <tr>
      <th rowspan="2">No.</th>
      <th rowspan="2">Transaksi</th>
      <th rowspan="2">Kategori Kegiatan</th>
      <th rowspan="2">No Pendaftaran</th>
      <th rowspan="2">Nama Warga</th>
      <th rowspan="2">Alamat</th>
      <th rowspan="2">Tanggal Pendaftaran</th>
      <th colspan="2">Zakat Fitrah</th>
      <th >Mal</th>
      <th colspan="2">Fidyah</th>
      <th >Infaq</th>
    </tr>
    <tr>
      <th>Uang (Rp)</th>
      <th>Beras (Kg)</th>
      <th>Uang (Rp)</th>
      <th>Uang (Rp)</th>
      <th>Beras (Kg)</th>
      <th>Uang (Rp)</th>
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
            <td>{{$val->alamat}}</td>
            <td>{{$val->tgl_pendaftaran}}</td>
            <td>
              @if($val->nama_kegiatan === 'Pembayaran Zakat Fitrah')
                {{$val->nama_jenis_transaksi === 'Uang' ? $val->nilai : ''}}
              @endif
            </td>
            <td>
              @if($val->nama_kegiatan === 'Pembayaran Zakat Fitrah')
                {{$val->nama_jenis_transaksi === 'Beras' ? $val->nilai : ''}}
              @endif
            </td>
            <td>
              @if($val->nama_kegiatan === 'Pembayaran Zakat Maal')
                {{$val->nama_jenis_transaksi === 'Uang' ? $val->nilai : ''}}
              @endif
            </td>
            <td>
              @if($val->nama_kegiatan === 'Fidiyah')
                {{$val->nama_jenis_transaksi === 'Uang' ? $val->nilai : ''}}
              @endif
            </td>
            <td>
              @if($val->nama_kegiatan === 'Fidiyah')
                {{$val->nama_jenis_transaksi === 'Beras' ? $val->nilai : ''}}
              @endif
            </td>
            <td>
              @if($val->nama_kegiatan === 'Infaq')
                {{$val->nama_jenis_transaksi === 'Uang' ? $val->nilai : ''}}
              @endif
            </td>
        </tr>
    @endForeach
  </tbody>
</table>
