<?php

use Illuminate\Database\Seeder;
use App\Models\Master\Keluarga\HubKeluarga;

class HubKeluargaSeeder extends Seeder
{
    
    public function run()
    {
        HubKeluarga::create([
            'hubungan_kel' => 'Kepala Keluarga',
            'created_at'	=> date('Y-m-d H:i:s')
        ]);

        HubKeluarga::create([
            'hubungan_kel' => 'Ibu Kandung',
            'created_at'	=> date('Y-m-d H:i:s')
        ]);
    }
}
