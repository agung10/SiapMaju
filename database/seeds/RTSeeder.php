<?php

use Illuminate\Database\Seeder;
use App\Models\Master\RT;
use Illuminate\Support\Facades\DB;

class RTSeeder extends Seeder
{
    public function run()
    {
        DB::table('rt')->truncate();

        RT::insert([
            [
                'province_id' => 9,
                'city_id' => 115,
                'subdistrict_id' => 1579,
                'kelurahan_id' => 1,
                'rw_id' => 1,
                'rt' => 'RT 1'
            ],
            [
                'province_id' => 9,
                'city_id' => 115,
                'subdistrict_id' => 1579,
                'kelurahan_id' => 1,
                'rw_id' => 1,
                'rt' => 'RT 2'
            ],
            [
                'province_id' => 9,
                'city_id' => 115,
                'subdistrict_id' => 1579,
                'kelurahan_id' => 1,
                'rw_id' => 1,
                'rt' => 'RT 3'
            ],
            [
                'province_id' => 9,
                'city_id' => 115,
                'subdistrict_id' => 1579,
                'kelurahan_id' => 1,
                'rw_id' => 1,
                'rt' => 'RT 4'
            ],
            [
                'province_id' => 9,
                'city_id' => 115,
                'subdistrict_id' => 1579,
                'kelurahan_id' => 1,
                'rw_id' => 1,
                'rt' => 'RT 5'
            ],
            [
                'province_id' => 9,
                'city_id' => 115,
                'subdistrict_id' => 1579,
                'kelurahan_id' => 1,
                'rw_id' => 1,
                'rt' => 'RT 6'
            ],
        ]);
    }
}
