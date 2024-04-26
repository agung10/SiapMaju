<?php

use Illuminate\Database\Seeder;
use App\Models\Master\Transaksi\JenisTransaksi;

class JenisTransaksiSeeder extends Seeder
{
    public function run()
    {
        JenisTransaksi::create([
            'kegiatan_id' => 1,
            'nama_jenis_transaksi' => 'Uang',
            'satuan' => 'Rp',
            'nilai'  => '50000'
        ]);

        JenisTransaksi::create([
            'kegiatan_id' => 1,
            'nama_jenis_transaksi' => 'Beras',
            'satuan' => 'Kg',
            'nilai'  => '0'
        ]);

        JenisTransaksi::create([
            'kegiatan_id' => 2,
            'nama_jenis_transaksi' => 'Uang',
            'satuan' => 'Rp',
            'nilai'  => '0'
        ]);

        JenisTransaksi::create([
            'kegiatan_id' => 3,
            'nama_jenis_transaksi' => 'Kambing',
            'satuan' => 'Ekor',
            'nilai'  => '0'
        ]);

        JenisTransaksi::create([
            'kegiatan_id' => 3,
            'nama_jenis_transaksi' => 'Sapi',
            'satuan' => 'Ekor',
            'nilai'  => '0'
        ]);

        JenisTransaksi::create([
            'kegiatan_id' => 4,
            'nama_jenis_transaksi' => 'Uang',
            'satuan' => 'Rp',
            'nilai'  => '75000'
        ]);

        JenisTransaksi::create([
            'kegiatan_id' => 5,
            'nama_jenis_transaksi' => 'Uang',
            'satuan' => 'Rp',
            'nilai'  => '90000'
        ]);
    }
}
