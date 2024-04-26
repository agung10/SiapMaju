<?php

use Illuminate\Database\Seeder;
use App\Models\Master\Keluarga\AnggotaKeluarga;

class AnggotaKeluargaSeeder extends Seeder
{

    public function run()
    {
        AnggotaKeluarga::create([
            'keluarga_id' => 1,
            'nama' => 'Angga',
            'hub_keluarga_id' => 1,
            'email' => 'angga@mail.com',
            'alamat' => 'jalan kenangan no 20',
            'mobile' => '12345667',
            'password' => bcrypt(1234568),
            'is_active' => true,
        ]);

        AnggotaKeluarga::create([
            'keluarga_id' => 2,
            'nama' => 'Sadewa',
            'hub_keluarga_id' => 1,
            'email' => 'sadewa@mail.com',
            'alamat' => 'jalan kenangan no 30',
            'mobile' => '12345667',
            'password' => bcrypt(1234568),
            'is_active' => true,
        ]);

        AnggotaKeluarga::create([
            'keluarga_id' => 1,
            'nama' => 'Ratma',
            'hub_keluarga_id' => 2,
            'email' => 'angga@mail.com',
            'alamat' => 'jalan kenangan no 20',
            'mobile' => '12345667',
            'password' => bcrypt(1234568),
            'is_active' => true,
        ]);

        AnggotaKeluarga::create([
            'keluarga_id' => 2,
            'nama' => 'Dewi',
            'hub_keluarga_id' => 2,
            'email' => 'dewi@mail.com',
            'alamat' => 'jalan kenangan no 30',
            'mobile' => '12345667',
            'password' => bcrypt(1234568),
            'is_active' => true,
        ]);
    }
}
