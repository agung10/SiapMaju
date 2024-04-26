<?php

use Illuminate\Database\Seeder;
use App\Models\Master\Keluarga\StatusMutasiWarga;

class StatusMutasiWargaSeeder extends Seeder
{
    public function run()
    {
        StatusMutasiWarga::insert([
            ['nama_status' => 'Warga Baru', 'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d')],
            ['nama_status' => 'Pindah', 'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d')],
            ['nama_status' => 'Meninggal', 'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d')]
        ]);
    }
}
