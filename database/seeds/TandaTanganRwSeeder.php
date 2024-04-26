<?php

use Illuminate\Database\Seeder;
use App\Models\Master\TandaTanganRW;

class TandaTanganRwSeeder extends Seeder
{
    public function run()
    {
        TandaTanganRW::create([
            'tanda_tangan_rw' => 'tanda_tangan_rw_1268765475.jpg',
            'rw_id' => 1,
        ]);
    }
}
