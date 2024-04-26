<?php

use Illuminate\Database\Seeder;
use App\Models\Master\Medsos;
use Illuminate\Support\Facades\DB;

class MedsosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('medsos')->truncate();

        Medsos::insert([
            [
                'nama'       => 'Facebook',
                'icon'       => 'icon_1_1626329592.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Instagram',
                'icon'       => 'icon_2_1626330277.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Whatsapp',
                'icon'       => 'icon_3_1626329831.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Gofood',
                'icon'       => 'icon_4_1626330414.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'GrabFood',
                'icon'       => 'icon_5_1626330553.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Lazada',
                'icon'       => 'icon_6_1626330584.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Shopee',
                'icon'       => 'icon_7_1626330624.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'ShopeeFood',
                'icon'       => 'icon_8_1626330649.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Tokopedia',
                'icon'       => 'icon_9_1626330823.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
