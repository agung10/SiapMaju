<?php

use Illuminate\Database\Seeder;
use App\Models\Surat\SifatSurat;

class SifatSuratSeeder extends Seeder
{
    public function run()
    {
        SifatSurat::create([
            'sifat_surat' => 'Umum',
            'user_created' => 1
        ]);

        SifatSurat::create([
            'sifat_surat' => 'Rahasia',
            'user_created' => 1
        ]);
    }
}
