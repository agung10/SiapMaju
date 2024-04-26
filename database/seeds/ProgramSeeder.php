<?php

use Illuminate\Database\Seeder;
use App\Models\Program;

class ProgramSeeder extends Seeder
{

    public function run()
    {
        Program::create([
            'nama_program' => 'program A',
            'program' => 'Dakwah',
            'pic' => 'mr.A',
            'tanggal' => date('Y-m-d H:i:s'),
            'image' => 'pic2.jpg',
            'date_created' => date('Y-m-d H:i:s')
        ]);
    }
}
