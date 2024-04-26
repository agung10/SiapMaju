<?php

use Illuminate\Database\Seeder;
use App\Models\Master\Kegiatan\KatKegiatan;

class KatKegiatanSeeder extends Seeder
{

    public function run()
    {
        KatKegiatan::create([
            'nama_kat_kegiatan' => 'Idul Fitri',
            'kode_kat' => 'ZTR-FTR'
        ]);

        KatKegiatan::create([
            'nama_kat_kegiatan' => 'Idul Adha',
            'kode_kat' => 'KRB-IDA'
        ]);

        KatKegiatan::create([
            'nama_kat_kegiatan' => 'Kotak Amal',
            'kode_kat' => 'KTK-AML'
        ]);

        KatKegiatan::create([
            'nama_kat_kegiatan' => 'Pembangunan Masjid',
            'kode_kat' => 'PBN-MSJ'
        ]);
    }
}
