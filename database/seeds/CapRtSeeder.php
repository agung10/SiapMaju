<?php

use Illuminate\Database\Seeder;
use App\Models\Master\CapRT;

class CapRtSeeder extends Seeder
{
    public function run()
    {
        CapRT::create([
            'cap_rt' => 'cap_rt_1.jpg',
            'rt_id' => 1
        ]);

        CapRT::create([
            'cap_rt' => 'cap_rt_1.jpg',
            'rt_id' => 2
        ]);

        CapRT::create([
            'cap_rt' => 'cap_rt_1.jpg',
            'rt_id' => 3
        ]);

        CapRT::create([
            'cap_rt' => 'cap_rt_1.jpg',
            'rt_id' => 4
        ]);

        CapRT::create([
            'cap_rt' => 'cap_rt_1.jpg',
            'rt_id' => 5
        ]);

        CapRT::create([
            'cap_rt' => 'cap_rt_1.jpg',
            'rt_id' => 6
        ]);
    }
}
