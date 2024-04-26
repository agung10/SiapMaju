<?php

use Illuminate\Database\Seeder;
use App\Models\Tentang\KatPengurus;

class KatPengurusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        KatPengurus::create([
            'nama_kategori' => 'Pengajian',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        KatPengurus::create([
            'nama_kategori' => 'Kebersihan',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
