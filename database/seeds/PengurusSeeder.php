<?php

use Illuminate\Database\Seeder;
use App\Models\Tentang\Pengurus;

class PengurusSeeder extends Seeder
{
   
    public function run()
    {
         Pengurus::create([
            'kat_pengurus_id' => 1,
            'nama' => 'Mr.A',
            'jabatan' => 'Takmir',
            'alamat' => 'Tegal',
            'no_urut' => '1',
            'photo' => 'pengurus1.jpg',
        ]);
    }
}
