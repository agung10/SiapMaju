<?php

use Illuminate\Database\Seeder;
use App\Models\Master\CapRW;

class CapRwSeeder extends Seeder
{
   
    public function run()
    {
        CapRW::create([
            'cap_rw' => 'cap_rw_1568816653.jpeg',
            'rw_id' => 1
        ]);
    }
}
