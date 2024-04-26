<?php

use App\Models\Master\Kelurahan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelurahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kelurahan')->truncate();

        Kelurahan::insert([
            [
                'nama' => 'Sukamaju',
                'province_id' => 9,
                'city_id' => 115,
                'subdistrict_id' => 1579,
                'kode_pos' => '16415',
                'alamat' => 'Jl. Raya Jakarta-Bogor No.KM.36',
            ],
        ]);
    }
}
