<?php

use App\Models\UMKM\Umkm;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UmkmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('umkm')->truncate();

        Umkm::insert([
            [
                
            ],
        ]);
    }
}
