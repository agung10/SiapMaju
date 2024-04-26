<?php

use Illuminate\Database\Seeder;
use App\Models\Master\Blok;

class BlokSeeder extends Seeder
{
    public function run()
    {
        Blok::create([
            'nama_blok' 	=> 'A',
            'created_at'	=> date('Y-m-d H:i:s')
        ]);

        Blok::create([
            'nama_blok' 	=> 'B',
            'created_at'	=> date('Y-m-d H:i:s')
        ]);
    }
}
