<?php

use Illuminate\Database\Seeder;
use App\Models\Pengumuman;

class PengumumanSeeder extends Seeder
{   
    
    public function run()
    {
        $date = date('Y-m-d H:i:s');

        Pengumuman::create([
            'pengumuman' => "masjid al-Mahallie masih mengadakan pengajian rutin yang diadakan setiap ba'da (setelah) shubuh untuk santri dan jama'ah masjid. Selain itu juga ada pengajian anak-anak yang dimulai ba'da salat Dzuhur setiap hari Senin sampai hari Jum'at. Untuk pengajian ibu-ibu berada pada hari Rabu ba'da Dzuhur. Dan untuk pengajian lainnya diadakan pada malam Jum'at ba'da Maghrib membaca yasin dan tahlil. Malam Minggu ba'da Maghrib pembacaan Rotibul Hadad, ba'da Isya' yaitu pengajian kitab Bidayatul Hidayah. Malam Senin ba'da Maghrib pembacaan salawat maulid Nabi dan ba'da Isya' pembacaan salawat Dalailul Khairat. Kepada para jama'ah, jika tidak berhalangan, kami persilahkan untuk mengikuti kegiatan pengajian tersebut.",
            'image1' => 'image1.jpg',
            'image2' => 'image2.jpg',
            'image3' => 'image3.jpg',
            'tanggal' => $date,
            'aktif' => true,
            'date_created' => $date
        ]);
    }
}
