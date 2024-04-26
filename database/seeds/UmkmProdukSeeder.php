<?php

use App\Models\UMKM\UmkmProduk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UmkmProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('umkm_produk')->truncate();

        UmkmProduk::insert([
            [

            ],
        ]);
    }
}
