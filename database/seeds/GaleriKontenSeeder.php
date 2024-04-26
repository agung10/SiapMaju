<?php

use Illuminate\Database\Seeder;
use App\Models\Gallery\GalleryContent;

class GaleriKontenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GalleryContent::create([
            'galeri_id' => 1,
            'agenda_id' => 1,
            'keterangan' => 'lorem Ipsum Dolor Amet',
            'kategori_file' => 'Gambar',
            'file' => 'GaleriKonten1.jpg'
        ]);
    }
}
