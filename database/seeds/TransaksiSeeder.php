<?php

use Illuminate\Database\Seeder;
use App\Models\Master\Transaksi\Transaksi;

class TransaksiSeeder extends Seeder
{

    public function run()
    {
        Transaksi::create([
            'nama_transaksi' => 'Masuk'
        ]);

        Transaksi::create([
            'nama_transaksi' => 'Keluar'
        ]);
    }
}
