<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Surat</title>
</head>
@include('Surat.SuratPermohonan.previewSuratCss')
<?php
    $pathLogo = public_path('uploaded_files/logo/'.$logo_kabupaten->logo);
    $typeLogo = pathinfo($pathLogo, PATHINFO_EXTENSION);
    $fileLogo = file_get_contents($pathLogo);
    $base64Logo = 'data:image/'.$typeLogo . ';base64,' . base64_encode($fileLogo);
    
    $rtNumber = str_ireplace('rt','',$data->rt);
?>
<body>
    <div class="header-surat-container">
        <div class="logo-container">
            <img class="logo-kota" src="{{$base64Logo}}" alt="">
        </div>
        <div class="header-surat">
            <h3>RUKUN TETANGGA {{$rtNumber}} {{$data->rw}}</h3>
            <h3>KEL. {{$data->kelurahan?? 'Sukamaju'}} - KEC. {{$anggotaGetAlamat['subdistrict_name'] ?? 'Cilodong'}}</h3>
            <h3>{{$anggotaGetAlamat['type'] ?? 'Kota'}} {{$anggotaGetAlamat['city'] ?? 'Depok'}}</h2>  
        </div>
    </div>
    <div class="no-surat-container">
        <div class="no-surat-wrapper">
            <div class="no-surat">
                <p class="label-no-surat">No</p>
                <p style="margin-right:5px;">:</p>
                <p>{{$data->no_surat}}</p>
            </div>
            <div class="no-surat">
                <p class="label-no-surat">Lampiran</p>
                <p style="margin-right:5px;">:</p>
                @if ($data->lampiran)
                    <p>{{ $data->lampiran }}</p>
                @elseif ($lampiranSurat > 0)
                    <p>{{ $lampiranSurat > 0 ? $lampiranSurat : '' }}</p>
                @endif
            </div>
            <div class="no-surat">
                <p class="label-no-surat">Hal</p>
                <p style="margin-right:5px;">:</p>
                <p>Surat {{$data->hal}}</p>
            </div>
        </div>
        <div class="yth-surat">
            <p>Kepada Yth:</p>
            <p>Kepala Kelurahan {{$data->kelurahan ?? 'Sukamaju'}}</p>
            <p>{{ $data->alamat_kelurahan ?? 'Jl.Raya Bogor Km.36' }}</p>
            <p class="yth-kota">{{$anggotaGetAlamat['city'] ?? 'Depok'}} {{ $data->kode_pos ?? '16415' }}</p>
        </div>
    </div>
    <div class="isi-surat-container">
        <p>Yang bertanda tangan dibawah ini Ketua {{$data->rt}} {{$data->rw}} dengan ini menerangkan:</p>
        <div class="space-p"></div>
        <div class="isi-surat">
            <p class="label">Nama Lengkap</p>
            <p style="margin-right:5px;">:</p>
            <p>{{$data->nama_lengkap}}</p>
        </div>
        <div class="isi-surat">
            <p class="label">Tempat/tgl.lahir </p>
            <p style="margin-right:5px;">:</p>
            <p>{{ucfirst($data->tempat_lahir)}}/{{date('d-m-Y',strtotime($data->tgl_lahir))}}</p>
        </div>
        <div class="isi-surat">
            <p class="label">Bangsa </p>
            <p style="margin-right:5px;">:</p>
            <p>{{$data->bangsa}}</p>
        </div>
        <div class="isi-surat">
            <p class="label">Agama </p>
            <p style="margin-right:5px;">:</p>
            <p>{{$data->nama_agama}}</p>
        </div>
        <div class="isi-surat">
            <p class="label">Status Pernikahan </p>
            <p style="margin-right:5px;">:</p>
            <p>{{$data->nama_status_pernikahan}}</p>
        </div>
        <div class="isi-surat">
            <p class="label">Pekerjaan </p>
            <p style="margin-right:5px;">:</p>
            <p>{{$data->pekerjaan}}</p>
        </div>
        <div class="isi-surat">
            <p class="label">Kartu Keluarga No. </p>
            <p style="margin-right:5px;">:</p>
            <p>{{$data->no_kk}}</p>
        </div>
        <div class="isi-surat">
            <p class="label">KTP nomor </p>
            <p style="margin-right:5px;">:</p>
            <p>{{$data->no_ktp}}</p>
        </div>
        <div class="isi-surat">
            <p class="label">Alamat </p>
            <p style="margin-right:5px;">:</p>
            <p style="width:500px"> {{$data->alamat}}</p>
        </div>
        <div class="isi-surat">
            <p class="label">Untuk Mengurus Membuat </p>
            <p>: {{$data->hal}}</p>
        </div>
        <p>Demikian Surat Pengantar ini dibuat untuk bahan pertimbangan lebih lanjut.</p>
    </div>
    <div class="space-isi-surat-ttd"></div>
    <div class="ttd-container">
        <div class="rw-container">
            <p>Mengetahui</p>
            <p>Ketua {{$data->rw}}</p>
            @if(!empty($data->tgl_approve_rw))
            <img class="ttd-img" src='{{asset("uploaded_files/cap_rw/$data->cap_rw")}}' alt="">
            @endif
            <p>({{$ketuaRW->nama ?? '..................'}})</p>
        </div>
        <div class="qr-code-container">
            @if($data->validasi)
                {!! QrCode::size(140)->generate($data->validasi) !!}
            @endif
        </div>
        <div class="rt-container">
            <p>{{$data->kelurahan ?? 'Sukamaju'}}, {{date('d-m-Y',strtotime($data->tgl_permohonan))}}</p>
            <p>Ketua {{$data->rt}}</p>
            @if(!empty($data->tgl_approve_rt))
            <div class="ttd-rt-container">
                <div class="ttd-rt-spacer"></div>
                <img class="ttd-img" src='{{asset("uploaded_files/cap_rt/$data->cap_rt")}}' alt="">
            </div>
            @endif
            <p>({{$ketuaRT->nama ?? '..................'}})</p>
        </div>
    </div>
    <script>
        if(screen.width > 600){
            setTimeout(() => {
                window.print()
                window.close()
            },1000)
        }
    </script>
</body>
</html>