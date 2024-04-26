<?php

use Illuminate\Database\Seeder;
use App\Models\Master\RW;
use Illuminate\Support\Facades\DB;

class RWSeeder extends Seeder
{
    public function run()
    {
        DB::table('rw')->truncate();

        RW::insert([
            [
                'province_id' => 9,
                'city_id' => 115,
                'subdistrict_id' => 1579,
                'kelurahan_id' => 1,
                'rw' => 'RW 23',
            ],
        ]);
    }
}
