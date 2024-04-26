<table class="table table-bordered table-checkable table-responsive table-search-result">
  <thead>
    <tr>
        <th>No.</th>
        <th>No Surat</th>
        <th>Jenis Permohonan</th>
        <th>Tanggal Surat</th>
        <th>Nama Warga</th>
        <th>RT</th>
        <th>Status Surat</th>
        <th>Lama Proses</th>
    </tr>
  </thead>
  <tbody>
    @foreach($laporan as $key => $val)
        <?php
            $tgl_permohonan = new \DateTime($val->tgl_permohonan);
            $tgl_apporval_rw = new \DateTime($val->tgl_approve_rw);
            
            $lamaProses = $tgl_permohonan->diff($tgl_apporval_rw);
            $lamaProsesInDay = $lamaProses->d;
    
            $badgeColor =  $lamaProsesInDay <= 1 ? 'badge-success' 
                                                 : ($lamaProsesInDay > 1 && $lamaProsesInDay <= 3 ? 'badge-warning' 
                                                                                                  : 'badge-danger'); 

            $prosesSurat = '<span class="badge badge-pill '.$badgeColor.'">'.$lamaProsesInDay.' Hari</span>';

            $badgeColor = !empty($val->tgl_approve_rw) ? 'badge-success' : 'badge-warning';
            $statusSurat = !empty($val->tgl_approve_rw) ? 'Selesai' : 'Proses';
                            
            $badgeStatusSurat =  '<span class="badge badge-pill '.$badgeColor.'">'.$statusSurat.'</span>';
        ?>
        <tr> 
            <td>{{$loop->iteration}}</td>
            <td>{{$val->no_surat}}</td>
            <td>{{$val->jenis_permohonan}}</td>
            <td>{{date('d-m-Y',strtotime($val->created_at))}}</td>
            <td>{{$val->nama}}</td>
            <td>{{$val->rt}}</td>
            <td>{!! $badgeStatusSurat !!}</td>
            <td>{!! $prosesSurat !!}</td>
        </tr>
    @endForeach
  </tbody>
</table>
