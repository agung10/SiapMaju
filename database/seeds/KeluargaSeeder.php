<?php

use Illuminate\Database\Seeder;
use App\Models\Master\Keluarga\Keluarga;

class KeluargaSeeder extends Seeder
{

    public function run()
    {
        Keluarga::create([
            'blok_id' => 1,
            'no_telp' => '12345667',
            'email' => 'keluarga1@mail.com',
            'alamat' => 'Jalan Kenangan No 20',
            'is_active' => true,
            'rt_id' => 1,
            'rw_id' => 1,
            'user_created' => 1,
        ]);

        Keluarga::create([
            'blok_id' => 2,
            'no_telp' => '12345667',
            'email' => 'keluarga2@mail.com',
            'alamat' => 'Jalan Kenangan No 30',
            'is_active' => true,
            'rt_id' => 1,
            'rw_id' => 1,
            'user_created' => 1,
        ]);
    }
}
