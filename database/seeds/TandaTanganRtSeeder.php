<?php

use Illuminate\Database\Seeder;
use App\Models\Master\TandaTanganRT;

class TandaTanganRtSeeder extends Seeder
{
    public function run()
    {
        TandaTanganRT::create([
            'tanda_tangan_rt' => 'tanda_tangan.jpg',
            'rt_id' => 1,
        ]);

        TandaTanganRT::create([
            'tanda_tangan_rt' => 'tanda_tangan.jpg',
            'rt_id' => 2,
        ]);

        TandaTanganRT::create([
            'tanda_tangan_rt' => 'tanda_tangan.jpg',
            'rt_id' => 3,
        ]);

        TandaTanganRT::create([
            'tanda_tangan_rt' => 'tanda_tangan.jpg',
            'rt_id' => 4,
        ]);
        
        TandaTanganRT::create([
            'tanda_tangan_rt' => 'tanda_tangan.jpg',
            'rt_id' => 5,
        ]);

        TandaTanganRT::create([
            'tanda_tangan_rt' => 'tanda_tangan.jpg',
            'rt_id' => 6,
        ]);
    }
}
