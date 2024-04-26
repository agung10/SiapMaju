<?php

use App\Musrenbang\KegiatanUrusan;
use Illuminate\Database\Seeder;

class KegiatanUrusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = date('Y-m-d');

        KegiatanUrusan::create([
            'nama_kegiatan' => 'PJL',
            'user_create' => 1,
            'user_update' => 1,
            'created_at' => $date,
            'updated_at' => $date
        ]);
        KegiatanUrusan::create([
            'nama_kegiatan' => 'Septictank',
            'user_create' => 1,
            'user_update' => 1,
            'created_at' => $date,
            'updated_at' => $date
        ]);
        KegiatanUrusan::create([
            'nama_kegiatan' => 'Drainase',
            'user_create' => 1,
            'user_update' => 1,
            'created_at' => $date,
            'updated_at' => $date
        ]);
        KegiatanUrusan::create([
            'nama_kegiatan' => 'Aspal',
            'user_create' => 1,
            'user_update' => 1,
            'created_at' => $date,
            'updated_at' => $date
        ]);
        KegiatanUrusan::create([
            'nama_kegiatan' => 'Sumur Resapan',
            'user_create' => 1,
            'user_update' => 1,
            'created_at' => $date,
            'updated_at' => $date
        ]);
        KegiatanUrusan::create([
            'nama_kegiatan' => 'Cermin Cembung',
            'user_create' => 1,
            'user_update' => 1,
            'created_at' => $date,
            'updated_at' => $date
        ]);
        KegiatanUrusan::create([
            'nama_kegiatan' => 'Lubang Biopori',
            'user_create' => 1,
            'user_update' => 1,
            'created_at' => $date,
            'updated_at' => $date
        ]);
    }
}
