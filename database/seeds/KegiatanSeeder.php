<?php

use Illuminate\Database\Seeder;
use App\Models\Master\Kegiatan\Kegiatan;

class KegiatanSeeder extends Seeder
{

    public function run()
    {
        Kegiatan::create([
            'kat_kegiatan_id' => 1,
            'nama_kegiatan' => 'Pembayaran Zakat Fitrah'   
        ]);

        Kegiatan::create([
            'kat_kegiatan_id' => 1,
            'nama_kegiatan' => 'Pembayaran Panitia Zakat Fitrah'   
        ]);

        Kegiatan::create([
            'kat_kegiatan_id' => 2,
            'nama_kegiatan' => 'Kurban Idul Adha'   
        ]);

        Kegiatan::create([
            'kat_kegiatan_id' => 3,
            'nama_kegiatan' => 'Kotak Amal Masjid Al-Mujahidin'   
        ]);

        Kegiatan::create([
            'kat_kegiatan_id' => 4,
            'nama_kegiatan' => 'Pembangunan Masjid'   
        ]);
    }
}
