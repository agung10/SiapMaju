<?php

use Illuminate\Database\Seeder;
use App\Models\Agenda;

class AgendaSeeder extends Seeder
{

    public function run()
    {
        Agenda::create([
            'program_id' => 1,
            'nama_agenda' => 'Agenda A',
            'lokasi' => 'Jakarta',
            'tanggal' => '2021-06-16',
            'jam' => '09:58:21',
            'agenda' => 'Agenda AA',
            'image' => 'agenda1.jpg',
            'user_updated' => 1,
        ]);
    }
}
