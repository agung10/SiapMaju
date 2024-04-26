<?php

use App\Models\StatusDomisili;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusDomisiliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('status_domisili')->truncate();

        StatusDomisili::insert([
            [
                'keterangan' => 'Alamat KK Sesuai Domisili',
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'keterangan' => 'Warga Asli Tinggal Diluar',
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'keterangan' => 'Warga Pendatang',
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
        ]);
    }
}
