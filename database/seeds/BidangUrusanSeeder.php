<?php

use App\Musrenbang\BidangUrusan;
use Illuminate\Database\Seeder;

class BidangUrusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = date('Y-m-d');

        BidangUrusan::create([
            'nama_bidang' => 'Infrastruktur',
            'user_create' => 1,
            'user_update' => 1,
            'created_at' => $date,
            'updated_at' => $date
        ]);
        BidangUrusan::create([
            'nama_bidang' => 'Ekonomi, Sosial, dan Kesmas',
            'user_create' => 1,
            'user_update' => 1,
            'created_at' => $date,
            'updated_at' => $date
        ]);
        BidangUrusan::create([
            'nama_bidang' => 'Teknologi Digital',
            'user_create' => 1,
            'user_update' => 1,
            'created_at' => $date,
            'updated_at' => $date
        ]);
    }
}
