<?php

use Illuminate\Database\Seeder;
use App\Models\Kajian\Kategori;

class KatKajianSeeder extends Seeder
{

    public function run()
    {
        Kategori::create([
            'kategori' => 'Pertaruran Pemerintah',
            'date_created' => date('Y-m-d H:i:s')
        ]);

        Kategori::create([
            'kategori' => 'Surat Edaran',
            'date_created' => date('Y-m-d H:i:s')
        ]);
    }
}
