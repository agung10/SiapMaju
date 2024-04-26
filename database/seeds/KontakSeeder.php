<?php

use Illuminate\Database\Seeder;
use App\Models\Kontak;

class KontakSeeder extends Seeder
{

    public function run()
    {   
        $date = date('Y-m-d H:i:s');

        Kontak::create([
            'alamat' => 'Jagakarsa',
            'no_telp' => '1234',
            'mobile' => '5667',
            'email' => 'test@mail.com',
            'nama_lokasi' => 'jalan b',
            'web' => $date,
            'rekening' => $date,
            'date_created' => $date,
            'user_updated' => 1,
        ]);
    }
}
