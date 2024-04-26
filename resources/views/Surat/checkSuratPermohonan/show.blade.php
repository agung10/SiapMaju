<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat {{!empty($surat->no_surat) ? $surat->no_surat : '-'}}</title>
</head>
@include('Surat.checkSuratPermohonan.css')
<?php
        $pathLogo = public_path('uploaded_files/logo/'.$logo_kabupaten->logo);
        $typeLogo = pathinfo($pathLogo,PATHINFO_EXTENSION);
        $fileLogo = file_get_contents($pathLogo);
        $base64Logo = 'data:image/'.$typeLogo . ';base64,' . base64_encode($fileLogo);
        
        $rtNumber = str_ireplace('rt','',$surat->rt);
?>
<body>
    <div class="header-surat-container">
        <div class="logo-container">
            <img class="logo-kota" src="{{$base64Logo}}" alt="">
        </div>
        <div class="header-surat">
            <h3>RUKUN TETANGGA {{$rtNumber}} {{$surat->rw}}</h3>
            <h3>KEL. {{$surat->kelurahan?? 'Sukamaju'}} - KEC. {{$anggotaGetAlamat['subdistrict_name'] ?? 'Cilodong'}}</h3>
            <h3>{{$anggotaGetAlamat['type'] ?? 'Kota'}} {{$anggotaGetAlamat['city'] ?? 'Depok'}}</h2> 
        </div>
    </div>
    <div class="isi-surat-container">
        <p>Surat permohonan dibawah ini:</p>
        <div class="detail-surat">
            <p class="label">No Surat</p>
            <p>:</p>
            <p>{{!empty($surat->no_surat) ? $surat->no_surat : ''}}</p>
        </div>
        <div class="detail-surat">
            <p class="label">Nama Pemohon</p>
            <p>:</p>
            <p>{{!empty($surat->nama_lengkap) ? $surat->nama_lengkap : ''}}</p>
        </div>
        <div class="detail-surat">
            <p class="label">RT/RW</p>
            <p>:</p>
            <p>{{!empty($surat->rt) ? preg_replace('/[A-Za-z ]/','',$surat->rt) : '-'}}/{{!empty($surat->rw) ? preg_replace('/[A-Za-z ]/','',$surat->rw) : '-'}}</p>
        </div>
        <div class="detail-surat">
            <p class="label">Tanggal Validasi</p>
            <p>:</p>
            <p>{{!empty($surat->tgl_approve_rw) ? \helper::datePrint($surat->tgl_approve_rw) : ''}}</p>
        </div>
        <p>Adalah benar terdaftar dan terverifikasi dalam sistem SIKAD - {{ $surat->rw }} - Taman Depok Permai</p>

          
        <p>Terimakasih...</p>
    </div>
    @if($authorize)
    <form class="btn-approve-lurah-container" method="post" action="{{route('Surat.checkSuratPermohonan.approveLurah',\Crypt::encryptString($surat->surat_permohonan_id))}}">
        @csrf
        <button class="btn-approve-lurah">
            Approve Kelurahan
        </button>
    </form>
    @endif
</body>
</html>