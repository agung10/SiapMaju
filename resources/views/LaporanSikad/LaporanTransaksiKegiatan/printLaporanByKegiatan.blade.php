<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<style>
  body{
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
  }

  table, td, th {
    border: 1px solid black;
  }

  table{
    text-align:center;
    border-collapse: collapse;
  }
</style>
<body>
  <h2>Laporan Transaksi Kegiatan</h2>
<table class="table table-bordered table-checkable table-search-result">
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
  @if(sizeof($laporan) > 0)
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
  @else
    <tr>
      <td colspan="10">Laporan tidak ditemukan</td>
    </tr>
  @endif
  </tbody>
</table>
</body>
</html>

<script>

  // sumTotalHandler()

  function sumTotalHandler(){

    const totalMasuk = document.querySelector('.total-masuk')
    const totalKeluar = document.querySelector('.total-keluar')

    const sumNilaiMasuk = (selector) => {
      let arrNilai = [];
      
      document.querySelectorAll(selector).forEach(node => {
        const nilai = parseInt(node.innerText)

        if(nilai > 0){
          arrNilai = [...arrNilai,nilai]
        }
      })

      return arrNilai.reduce((a,b) => {
                  return a+b
                  },0)
    }

    const totalNilaiMasuk = sumNilaiMasuk('.nilai-masuk') ?? 0
    const totalNilaiKeluar = sumNilaiMasuk('.nilai-keluar') ?? 0

    if(totalMasuk || totalKeluar){
      totalMasuk.innerText = totalNilaiMasuk
      totalKeluar.innerText = totalNilaiKeluar
    }
  }

</script>