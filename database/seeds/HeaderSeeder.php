<?php

use Illuminate\Database\Seeder;
use App\Models\Header;

class HeaderSeeder extends Seeder
{
    public function run()
    {
        Header::create([
            'image' => 'image1.jpg',
            'judul' => 'header1',
            'keterangan' => 'lorem ipsum',
            'date_created' => date('Y-m-d'),
        ]);

        Header::create([
            'image' => 'image2.jpg',
            'judul' => 'header2',
            'keterangan' => 'lorem ipsum',
            'date_created' => date('Y-m-d'),
        ]);

        Header::create([
            'image' => 'image3.jpg',
            'judul' => 'header3',
            'keterangan' => 'lorem ipsum',
            'date_created' => date('Y-m-d'),
        ]);
    }
}
