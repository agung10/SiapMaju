<?php

use Illuminate\Database\Seeder;
use App\Models\Surat\JenisSuratRw;

class JenisSuratRwSeeder extends Seeder
{
    public function run()
    {
        JenisSuratRw::create([
            'jenis_surat' => 'Masuk',
            'user_created' => 1,
        ]);

        JenisSuratRw::create([
            'jenis_surat' => 'Keluar',
            'user_created' => 1,
        ]);
    }
}
