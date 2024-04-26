<?php

use Illuminate\Database\Seeder;
use App\Models\Master\JenisSurat;

class JenisSuratSeeder extends Seeder
{

    public function run()
    {
        JenisSurat::create([
            'jenis_permohonan' => 'KTP baru/perpanjangan',
            'kd_surat' => 1,
            'keterangan' => 'Surat Permohonan KTP baru/perpanjangan'
        ]);

        JenisSurat::create([
            'jenis_permohonan' => 'Pembuatan KK',
            'kd_surat' => 2,
            'keterangan' => 'Surat Permohonan pembuatan KK'
        ]);

        JenisSurat::create([
            'jenis_permohonan' => 'SKCK',
            'kd_surat' => 3,
            'keterangan' => 'Surat Permohonan SKCK'
        ]);

        JenisSurat::create([
            'jenis_permohonan' => 'Tempat tinggal/Domisili',
            'kd_surat' => 4,
            'keterangan' => 'Surat Permohonan Tempat tinggal/Domisili'
        ]);

        JenisSurat::create([
            'jenis_permohonan' => 'Pindah/menetap',
            'kd_surat' => 5,
            'keterangan' => 'Surat Permohonan Pindah/menetap'
        ]);

        JenisSurat::create([
            'jenis_permohonan' => 'Keterangan belum menikah',
            'kd_surat' => 6,
            'keterangan' => 'Surat Permohonan Keterangan belum menikah'
        ]);

        JenisSurat::create([
            'jenis_permohonan' => 'Nikah/talak/cerai/rujuk',
            'kd_surat' => 7,
            'keterangan' => 'Surat Permohonan Nikah/talak/cerai/rujuk'
        ]);

        JenisSurat::create([
            'jenis_permohonan' => 'Kelahiran/kematian',
            'kd_surat' => 8,
            'keterangan' => 'Surat Permohonan Kelahiran/kematian'
        ]);

        JenisSurat::create([
            'jenis_permohonan' => 'Pensiun/Taspen/Astek/ASABRI',
            'kd_surat' => 9,
            'keterangan' => 'Surat Permohonan Pensiun/Taspen/Astek/ASABRI'
        ]);

        JenisSurat::create([
            'jenis_permohonan' => 'Izin Usaha/IMB',
            'kd_surat' => 10,
            'keterangan' => 'Surat Permohonan Izin Usaha/IMB'
        ]);

        JenisSurat::create([
            'jenis_permohonan' => 'Akte Tanah/pertanahan/PBB',
            'kd_surat' => 11,
            'keterangan' => 'Surat Permohonan Akte Tanah/pertanahan/PBB'
        ]);

        JenisSurat::create([
            'jenis_permohonan' => 'Keluarga ekonomi lemah',
            'kd_surat' => 12,
            'keterangan' => 'Surat Permohonan Keluarga ekonomi lemah'
        ]);

        JenisSurat::create([
            'jenis_permohonan' => 'Ket. belum punya rumah',
            'kd_surat' => 13,
            'keterangan' => 'Surat Permohonan Ket. belum punya rumah'
        ]);

        JenisSurat::create([
            'jenis_permohonan' => 'Pencurian/perampasan',
            'kd_surat' => 14,
            'keterangan' => 'Surat Permohonan Pencurian/perampasan'
        ]);

        JenisSurat::create([
            'jenis_permohonan' => 'Lain-lain',
            'kd_surat' => 99,
            'keterangan' => 'Surat Permohonan Lain-lain'
        ]);
    }
}