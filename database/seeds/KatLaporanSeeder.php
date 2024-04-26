<?php

use Illuminate\Database\Seeder;
use App\Models\Laporan\KatLaporan;

class KatLaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        KatLaporan::create([
            'nama_kategori' => 'Laporan Bulanan',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        KatLaporan::create([
            'nama_kategori' => 'Laporan Mingguan',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
