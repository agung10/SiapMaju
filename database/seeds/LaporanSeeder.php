<?php

use Illuminate\Database\Seeder;
use App\Models\Laporan\Laporan;

class LaporanSeeder extends Seeder
{

    public function run()
    {
        Laporan::create([
            'kat_laporan_id' => 1,
            'agenda_id' => 1,
            'laporan' => 'Lorem Ipsum',
            'detail_laporan' => 'Dolor Amet',
            'upload_materi' => 'materi1.jpg',
            'image' => 'image1.jpg',
        ]);
    }
}
