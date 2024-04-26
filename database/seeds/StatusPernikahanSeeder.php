<?php

use Illuminate\Database\Seeder;
use App\Models\Master\StatusPernikahan;

class StatusPernikahanSeeder extends Seeder
{
    public function run()
    {
        StatusPernikahan::create([
            'user_created' => 1,
            'nama_status_pernikahan' => 'Menikah'
        ]);

        StatusPernikahan::create([
            'user_created' => 1,
            'nama_status_pernikahan' => 'Belum Menikah'
        ]);

        StatusPernikahan::create([
            'user_created' => 1,
            'nama_status_pernikahan' => 'Cerai Hidup'
        ]);

        StatusPernikahan::create([
            'user_created' => 1,
            'nama_status_pernikahan' => 'Cerai Mati'
        ]);
    }
}
