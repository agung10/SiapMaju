<?php

use Illuminate\Database\Seeder;
use App\Models\Gallery\Gallery;

class GaleriSeeder extends Seeder
{

    public function run()
    {
        Gallery::create([
            'agenda_id' => 1,
            'detail_galeri' => 'lorem ipsum dolor ammet',
            'image_cover' => 'cover1.jpg',
        ]);
    }
}
