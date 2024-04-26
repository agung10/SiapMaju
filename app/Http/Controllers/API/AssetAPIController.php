<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AssetAPIController extends Controller
{
    public function menuIcon()
    {
        $menu = [['menu' => 'Map',
                  'icon' => asset('assets/icon/menu/map.png'),
                  'key' => 'map'
                 ],
                 ['menu' => 'Persuratan',
                  'icon' => asset('assets/icon/menu/envelope.png'),
                  'key' => 'suratPermohonan'
                 ],
                 ['menu' => 'Tagihan',
                  'icon' => asset('assets/icon/menu/calculator.png'),
                  'key' => 'tagihan'
                 ],
                 ['menu' => 'Program',
                  'icon' => asset('assets/icon/menu/task.png'),
                  'key' => 'programKegiatan'
                 ],
                 ['menu' => 'Kegiatan',
                  'icon' => asset('assets/icon/menu/agenda.png'),
                  'key' => 'agenda'
                 ],
                 ['menu' => 'Gallery',
                  'icon' => asset('assets/icon/menu/gallery.png'),
                  'key' => 'gallery'
                 ],
                 ['menu' => 'Aturan dan Perundangan',
                  'icon' => asset('assets/icon/menu/aturan-dan-perundangan.png'),
                  'key' => 'perundangan'
                 ],
                 ['menu' => 'Cuaca',
                  'icon' => asset('assets/icon/menu/cuaca.png'),
                  'key' => 'cuaca'
                 ],
                 ['menu' => 'No Telepon Penting',
                  'icon' => asset('assets/icon/menu/no-telp-penting.png'),
                  'key' => 'telpPenting'
                 ],
                 ['menu' => 'UMKM',
                  'icon' => asset('assets/icon/menu/umkm.png'),
                  'key' => 'umkm'
                 ],
                 ['menu' => 'Fasilitas',
                  'icon' => asset('assets/icon/menu/fasilitas.png'),
                  'key' => 'fasilitas'
                 ],
                 ['menu' => 'Keluh Kesan',
                  'icon' => asset('assets/icon/menu/keluh-kesan.png'),
                  'key' => 'keluh'
                 ],
                 ['menu' => 'Laporan Surat Permohonan',
                  'icon' => asset('assets/icon/menu/reportSurat.png'),
                  'key' => 'laporanSuratPermohonan'
                 ],
                 ['menu' => 'Laporan Transaksi Kegiatan',
                  'icon' => asset('assets/icon/menu/reportKegiatan.png'),
                  'key' => 'laporanTransaksiKegiatan'
                 ],
                 ['menu' => 'Laporan Transaksi Kegiatan DKM',
                  'icon' => asset('assets/icon/menu/mosque.png'),
                  'key' => 'laporanTransaksiKegiatanDKM'
                 ],
                 ['menu' => 'Covid19',
                  'icon' => asset('assets/icon/menu/covid19.png'),
                  'key' => 'covid19'
                ],
                 ['menu' => 'Transaksi DKM',
                  'icon' => asset('assets/icon/menu/mosque.png'),
                  'key' => 'transaksiDKM'
                 ]
                ];

        return response()->json(compact('menu'));
    }
}
