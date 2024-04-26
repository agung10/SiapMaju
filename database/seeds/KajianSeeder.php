<?php

use Illuminate\Database\Seeder;
use App\Models\Kajian\Kajian;

class KajianSeeder extends Seeder
{
    public function run()

    {
        Kajian::create([
            'kat_kajian_id' => 1,
            'judul' => 'Lorem',
            'author' => 'Ustd A',
            'kajian' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean elementum aliquet erat pulvinar cursus. In leo est, luctus vitae dapibus a, vulputate et est.',
            'upload_materi_1' => 'kajian.mp4',
            'image' => 'kajian1.jpg',
            'date_created' => date('Y-m-d H:i:s')
        ]);

        Kajian::create([
            'kat_kajian_id' => 1,
            'judul' => 'Ipsum',
            'author' => 'Ustd A',
            'kajian' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean elementum aliquet erat pulvinar cursus. In leo est, luctus vitae dapibus a, vulputate et est.',
            'upload_materi_1' => 'kajian2.pdf',
            'image' => 'kajian2.jpg',
            'date_created' => date('Y-m-d H:i:s')
        ]);

        Kajian::create([
            'kat_kajian_id' => 2,
            'judul' => 'Dolor',
            'author' => 'Ustd A',
            'kajian' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean elementum aliquet erat pulvinar cursus. In leo est, luctus vitae dapibus a, vulputate et est.',
            'upload_materi_1' => 'kajian3.docx',
            'image' => 'kajian3.jpg',
            'date_created' => date('Y-m-d H:i:s')
        ]);

        Kajian::create([
            'kat_kajian_id' => 2,
            'judul' => 'Dolor',
            'author' => 'Ustd A',
            'kajian' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean elementum aliquet erat pulvinar cursus. In leo est, luctus vitae dapibus a, vulputate et est.',
            'upload_materi_1' => 'kajian3.docx',
            'image' => 'kajian4.jpg',
            'date_created' => date('Y-m-d H:i:s')
        ]);
    }
}
