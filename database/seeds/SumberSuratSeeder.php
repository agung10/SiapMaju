<?php

use Illuminate\Database\Seeder;
use App\Models\Surat\SumberSurat;

class SumberSuratSeeder extends Seeder
{
    public function run()
    {
        SumberSurat::create([
            'asal_surat' => 'kecamatan',
            'user_created' => 1
        ]);

        SumberSurat::create([
            'asal_surat' => 'kelurahan',
            'user_created' => 2
        ]);
    }
}
