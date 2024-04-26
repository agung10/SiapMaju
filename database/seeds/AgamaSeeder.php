<?php

use Illuminate\Database\Seeder;
use App\Models\Master\Agama;

class AgamaSeeder extends Seeder
{
    public function run()
    {
        Agama::create([
            'user_created' => 1,
            'nama_agama' => 'Islam'
        ]);

        Agama::create([
            'user_created' => 1,
            'nama_agama' => 'Protestan'
        ]);

        Agama::create([
            'user_created' => 1,
            'nama_agama' => 'Katolik'
        ]);

        Agama::create([
            'user_created' => 1,
            'nama_agama' => 'Hindu'
        ]);

        Agama::create([
            'user_created' => 1,
            'nama_agama' => 'Buddha'
        ]);

        Agama::create([
            'user_created' => 1,
            'nama_agama' => 'Khonghucu'
        ]);
    }
}
