<?php

use App\Musrenbang\MenuUrusan;
use Illuminate\Database\Seeder;

class MenuUrusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = date('Y-m-d');

        MenuUrusan::create([
            'nama_jenis' => 'Wajib',
            'user_create' => 1,
            'user_update' => 1,
            'created_at' => $date,
            'updated_at' => $date
        ]);
        MenuUrusan::create([
            'nama_jenis' => 'Pilihan',
            'user_create' => 1,
            'user_update' => 1,
            'created_at' => $date,
            'updated_at' => $date
        ]);
    }
}
