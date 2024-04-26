<?php

use Illuminate\Database\Seeder;
use App\Models\RoleManagement\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // DASHBOARD 1
        $dashboard = Menu::create([
                            'name'       => 'Dashboard',
                            'icon'       => 'fa fa-home',
                            'order'      => 1,
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);

        $dashboardSIKAD = Menu::create([
                            'name'       => 'Dashboard SIKAD',
                            'route'      => 'landing',
                            'id_parent'  => $dashboard->menu_id,
                            'order'      => 1,
                            'created_at' => $now,
                            'updated_at' => $now
        ]);           

        $location = Menu::create([
                            'name'       => 'Location TDP',
                            'icon'       => 'fa fa-map-marker',
                            'order'      => 2,
                            'created_at' => $now,
                            'updated_at' => $now
        ]);

        $sitemap = Menu::create([
            'name'       => 'Sitemap TDP',
            'route'      => 'Geolocation.Sitemap',
            'id_parent'  => $location->menu_id,
            'order'      => 1,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        // ROLE MANANAGEMENT 2
        $roleManagement = Menu::create([
                            'name'       => 'Role Management',
                            'icon'       => 'fa fa-users',
                            'order'      => 2,
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);

        $master = Menu::create([
                    'name'       => 'Master',
                    'id_parent'  => $roleManagement->menu_id,
                    'order'      => 1,
                    'created_at' => $now,
                    'updated_at' => $now
                ]);

        $role = Menu::create([
                    'name'       => 'Role',
                    'route'      => 'master.role',
                    'id_parent'  => $master->menu_id,
                    'order'      => 1,
                    'created_at' => $now,
                    'updated_at' => $now
                ]);

        $menu = Menu::create([
                    'name'       => 'Menu',
                    'route'      => 'master.menu',
                    'id_parent'  => $master->menu_id,
                    'order'      => 2,
                    'created_at' => $now,
                    'updated_at' => $now
                ]);

        $permission = Menu::create([
                        'name'       => 'Permission',
                        'route'      => 'master.permission',
                        'id_parent'  => $master->menu_id,
                        'order'      => 3,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $roleMenu = Menu::create([
                        'name'       => 'Role Menu',
                        'route'      => 'master.role-menu',
                        'id_parent'  => $roleManagement->menu_id,
                        'order'      => 2,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $menuPermission = Menu::create([
                        'name'       => 'Hak Akses User',
                        'route'      => 'master.menu-permission',
                        'id_parent'  => $roleManagement->menu_id,
                        'order'      => 3,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $menuPermission = Menu::create([
                        'name'       => 'User',
                        'route'      => 'master.user',
                        'id_parent'  => $roleManagement->menu_id,
                        'order'      => 4,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);
                    
        $warga = Menu::create([
                        'name'       => 'Warga',
                        'id_parent'  => $roleManagement->menu_id,
                        'order'      => 5,
                        'created_at' => $now,
                        'updated_at' => $now
        ]);

        $list_rt_rw = Menu::create([
            'name'       => 'List RT/RW',
            'route'      => 'role_management.ListRtRw',
            'id_parent'  => $warga->menu_id,
            'order'      => 1,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $list_warga = Menu::create([
            'name'       => 'List Warga',
            'route'      => 'role_management.ListWarga',
            'id_parent'  => $warga->menu_id,
            'order'      => 2,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $beranda = Menu::create([
                            'name'       => 'Beranda',
                            'icon'       => 'fa fa-hourglass-half',
                            'order'      => 3,
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);

        $header =  Menu::create([
                        'name'       => 'Header',
                        'id_parent'  => $beranda->menu_id,
                        'route'      => 'Beranda.Header',
                        'order'      => 1,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);
        $Kontak =  Menu::create([
                        'name'       => 'Kontak',
                        'id_parent'  => $beranda->menu_id,
                        'route'      => 'Beranda.Kontak',
                        'order'      => 2,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $tentang = Menu::create([
                            'name'       => 'Tentang',
                            'icon'       => 'fa fa-users',
                            'order'      => 4,
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);

        $Profile =  Menu::create([
                        'name'       => 'Profile',
                        'id_parent'  => $tentang->menu_id,
                        'route'      => 'Tentang.Profile',
                        'order'      => 1,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $Pengurus =  Menu::create([
                        'name'       => 'Pengurus',
                        'id_parent'  => $tentang->menu_id,
                        'order'      => 2,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $KategoriPengurus =  Menu::create([
                        'name'       => 'Kategori Pengurus',
                        'id_parent'  => $Pengurus->menu_id,
                        'route'      => 'Tentang.KategoriPengurus',
                        'order'      => 1,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $Pengurus =  Menu::create([
                        'name'       => 'Pengurus',
                        'id_parent'  => $Pengurus->menu_id,
                        'route'      => 'Tentang.ListPengurus',
                        'order'      => 2,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $programkegiatan = Menu::create([
                            'name'       => 'Program Kegiatan',
                            'icon'       => 'fa fa-list-ol',
                            'order'      => 5,
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);

        $Program =  Menu::create([
                        'name'       => 'Program',
                        'id_parent'  => $programkegiatan->menu_id,
                        'route'      => 'Program.Kegiatan',
                        'order'      => 1,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $agenda = Menu::create([
                            'name'       => 'Agenda',
                            'icon'       => 'fa fa-clock',
                            'order'      => 6,
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);

        $agendaKegiatan =  Menu::create([
                        'name'       => 'Agenda Kegiatan',
                        'id_parent'  => $agenda->menu_id,
                        'route'      => 'Agenda.AgendaKegiatan',
                        'order'      => 1,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $pengumuman = Menu::create([
                            'name'       => 'Pengumuman',
                            'icon'       => 'fa fa-bullhorn',
                            'order'      => 7,
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);

        $pengumuman1 =  Menu::create([
                        'name'       => 'Pengumuman',
                        'id_parent'  => $pengumuman->menu_id,
                        'route'      => 'Pengumuman.List',
                        'order'      => 1,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $kajian = Menu::create([
                            'name'       => 'Peraturan dan Edaran',
                            'icon'       => 'fa fa-book',
                            'order'      => 8,
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);

        $kajianKategori =  Menu::create([
                        'name'       => 'Kategori',
                        'id_parent'  => $kajian->menu_id,
                        'route'      => 'Kajian.Kategori',
                        'order'      => 1,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $kajianKonten =  Menu::create([
                        'name'       => 'Peraturan dan Edaran',
                        'id_parent'  => $kajian->menu_id,
                        'route'      => 'Kajian.Konten',
                        'order'      => 2,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        // Keluh Kesan
        $keluhKesan = Menu::create([
            'name'       => 'Keluh Kesan',
            'icon'       => 'fa fa-inbox',
            'order'      => 9,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $subMenuKeluhKesan =  Menu::create([
                'name'       => 'Keluh Kesan',
                'id_parent'  => $keluhKesan->menu_id,
                'route'      => 'keluhKesan.keluhKesan',
                'order'      => 1,
                'created_at' => $now,
                'updated_at' => $now
        ]);

        // Fasilitas
        $fasilitas = Menu::create([
            'name'       => 'Fasilitas',
            'icon'       => 'fa fa-plug',
            'order'      => 10,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $subFasilitas =  Menu::create([
                'name'       => 'Fasilitas',
                'id_parent'  => $fasilitas->menu_id,
                'route'      => 'fasilitas.fasilitas',
                'order'      => 1,
                'created_at' => $now,
                'updated_at' => $now
        ]);

        // UMKM
        $fasilitas = Menu::create([
            'name'       => 'UMKM',
            'icon'       => 'fa fa-shopping-bag',
            'order'      => 11,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $laporan = Menu::create([
                            'name'       => 'Publish Laporan',
                            'icon'       => 'fa fa-file',
                            'order'      => 12,
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);

        $katLaporan =  Menu::create([
                        'name'       => 'Kategori Laporan',
                        'id_parent'  => $laporan->menu_id,
                        'route'      => 'Laporan.KategoriLaporan',
                        'order'      => 1,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $kajianKonten =  Menu::create([
                        'name'       => 'Laporan',
                        'id_parent'  => $laporan->menu_id,
                        'route'      => 'Laporan.Laporan',
                        'order'      => 2,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $galeri = Menu::create([
                            'name'       => 'Gallery',
                            'icon'       => 'fa fa-image',
                            'order'      => 13,
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);

        $katLaporan =  Menu::create([
                        'name'       => 'Gallery',
                        'id_parent'  => $galeri->menu_id,
                        'route'      => 'Gallery.List',
                        'order'      => 1,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $kajianKonten =  Menu::create([
                        'name'       => 'Gallery Content',
                        'id_parent'  => $galeri->menu_id,
                        'route'      => 'Gallery.Content',
                        'order'      => 2,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $menuMaster = Menu::create([
                            'name'       => 'Menu Master',
                            'icon'       => 'fa fa-archive',
                            'order'      => 14,
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);

        $blokRumah =  Menu::create([
                        'name'       => 'Blok Rumah',
                        'id_parent'  => $menuMaster->menu_id,
                        'route'      => 'Master.Blok',
                        'order'      => 1,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $par_keluarga =  Menu::create([
                        'name'       => 'Keluarga',
                        'id_parent'  => $menuMaster->menu_id,
                        'order'      => 2,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $HubKeluarga =  Menu::create([
                        'name'       => 'Hubungan Keluarga',
                        'id_parent'  => $par_keluarga->menu_id,
                        'route'      => 'Master.HubKeluarga',
                        'order'      => 1,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $ListKeluarga =  Menu::create([
                        'name'       => 'Keluarga',
                        'id_parent'  => $par_keluarga->menu_id,
                        'route'      => 'Master.ListKeluarga',
                        'order'      => 2,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $AnggotaKeluarga =  Menu::create([
                        'name'       => 'Anggota Keluarga',
                        'id_parent'  => $par_keluarga->menu_id,
                        'route'      => 'Master.AnggotaKeluarga',
                        'order'      => 3,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $par_kegiatan =  Menu::create([
                        'name'       => 'Kegiatan',
                        'id_parent'  => $menuMaster->menu_id,
                        'order'      => 3,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $par_katkegiatan =  Menu::create([
                        'name'       => 'Kategori Kegiatan',
                        'id_parent'  => $par_kegiatan->menu_id,
                        'route'      => 'Master.KatKegiatan',
                        'order'      => 1,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $parkegiatan =  Menu::create([
                        'name'       => 'Kegiatan',
                        'id_parent'  => $par_kegiatan->menu_id,
                        'route'      => 'Master.ListKegiatan',
                        'order'      => 2,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $par_transaksi =  Menu::create([
                        'name'       => 'Transaksi',
                        'id_parent'  => $menuMaster->menu_id,
                        'order'      => 3,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $tranasksi =  Menu::create([
                        'name'       => 'Transaksi',
                        'id_parent'  => $par_transaksi->menu_id,
                        'route'      => 'Master.Transaksi',
                        'order'      => 1,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $jenis_transaksi =  Menu::create([
                        'name'       => 'Jenis Transaksi',
                        'id_parent'  => $par_transaksi->menu_id,
                        'route'      => 'Master.JenisTransaksi',
                        'order'      => 1,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $par_rt =  Menu::create([
                        'name'       => 'RT',
                        'id_parent'  => $menuMaster->menu_id,
                        'order'      => 4,
                        'created_at' => $now,
                        'updated_at' => $now
        ]);

        $rt =  Menu::create([
                        'name'       => 'RT',
                        'id_parent'  => $par_rt->menu_id,
                        'route'      => 'Master.rt',
                        'order'      => 1,
                        'created_at' => $now,
                        'updated_at' => $now
        ]);

        $CapRt =  Menu::create([
                        'name'       => 'Cap RT',
                        'id_parent'  => $par_rt->menu_id,
                        'route'      => 'Master.CapRT',
                        'order'      => 2,
                        'created_at' => $now,
                        'updated_at' => $now
        ]);

        $ttd_RT =  Menu::create([
                        'name'       => 'Tanda Tangan RT',
                        'id_parent'  => $par_rt->menu_id,
                        'route'      => 'Master.Tanda_Tangan_RT',
                        'order'      => 3,
                        'created_at' => $now,
                        'updated_at' => $now
        ]);

        $par_rw =  Menu::create([
                        'name'       => 'RW',
                        'id_parent'  => $menuMaster->menu_id,
                        'order'      => 5,
                        'created_at' => $now,
                        'updated_at' => $now
        ]);

        $rw =  Menu::create([
                        'name'       => 'RW',
                        'id_parent'  => $par_rw->menu_id,
                        'route'      => 'Master.rw',
                        'order'      => 1,
                        'created_at' => $now,
                        'updated_at' => $now
        ]);

        $CapRw =  Menu::create([
                        'name'       => 'Cap RW',
                        'id_parent'  => $par_rw->menu_id,
                        'route'      => 'Master.CapRW',
                        'order'      => 2,
                        'created_at' => $now,
                        'updated_at' => $now
        ]);

        $ttd_RW =  Menu::create([
                        'name'       => 'Tanda Tangan RW',
                        'id_parent'  => $par_rw->menu_id,
                        'route'      => 'Master.TandaTanganRW',
                        'order'      => 3,
                        'created_at' => $now,
                        'updated_at' => $now
        ]);

        $Agama =  Menu::create([
                        'name'       => 'Agama',
                        'id_parent'  => $menuMaster->menu_id,
                        'route'      => 'Master.Agama',
                        'order'      => 6,
                        'created_at' => $now,
                        'updated_at' => $now
        ]);

        $StatusPernikahan =  Menu::create([
                        'name'       => 'Status Pernikahan',
                        'id_parent'  => $menuMaster->menu_id,
                        'route'      => 'Master.StatusPernikahan',
                        'order'      => 7,
                        'created_at' => $now,
                        'updated_at' => $now
        ]);

        $par_surat =  Menu::create([
            'name'       => 'Surat',
            'id_parent'  => $menuMaster->menu_id,
            'order'      => 8,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $jenisSurat =  Menu::create([
                        'name'       => 'Jenis Surat',
                        'id_parent'  => $par_surat->menu_id,
                        'route'      => 'Master.JenisSurat',
                        'order'      => 1,
                        'created_at' => $now,
                        'updated_at' => $now
        ]); 

        $sumberSurat =  Menu::create([
                        'name'       => 'Sumber Surat',
                        'id_parent'  => $par_surat->menu_id,
                        'route'      => 'Master.SumberSurat',
                        'order'      => 2,
                        'created_at' => $now,
                        'updated_at' => $now
        ]);
        
        $sifatSurat =  Menu::create([
            'name'       => 'Sifat Surat',
            'id_parent'  => $par_surat->menu_id,
            'route'      => 'Master.SifatSurat',
            'order'      => 3,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $JenisSuratRw =  Menu::create([
            'name'       => 'Jenis Surat RW',
            'id_parent'  => $par_surat->menu_id,
            'route'      => 'Master.JenisSuratRw',
            'order'      => 4,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $tlp_penting =  Menu::create([
            'name'       => 'Telpon Penting',
            'id_parent'  => $menuMaster->menu_id,
            'route'      => 'Master.TeleponPenting',
            'order'      => 9,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $transaksiKeg = Menu::create([
                            'name'       => 'Transaksi Kegiatan',
                            'icon'       => 'fa fa-briefcase',
                            'order'      => 15,
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);

        $transaksiKeg2 =  Menu::create([
                        'name'       => 'Transaksi Kegiatan',
                        'id_parent'  => $transaksiKeg->menu_id,
                        'route'      => 'Transaksi.Header',
                        'order'      => 1,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

        $transaksiKeg3 =  Menu::create([
                        'name'       => 'Approval Transaksi Kegiatan',
                        'id_parent'  => $transaksiKeg->menu_id,
                        'route'      => 'Transaksi.Approval',
                        'order'      => 2,
                        'created_at' => $now,
                        'updated_at' => $now
        ]);

        $surat = Menu::create([
            'name'       => 'Surat',
            'icon'       => 'fa fa-envelope',
            'order'      => 16,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $surat_permohonan =  Menu::create([
            'name'       => 'Surat Permohonan',
            'id_parent'  => $surat->menu_id,
            'route'      => 'Surat.SuratPermohonan',
            'order'      => 1,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        
        $surat_masuk_keluar_rw =  Menu::create([
            'name'       => 'Surat Masuk Dan Keluar RW',
            'id_parent'  => $surat->menu_id,
            'route'      => 'Surat.SuratMasukKeluarRw',
            'order'      => 1,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $laporanSikad = Menu::create([
            'name'       => 'Laporan',
            'icon'       => 'fa fa-pen',
            'order'      => 17,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $laporan_surat_permohonan =  Menu::create([
            'name'       => 'Laporan Surat Permohonan',
            'id_parent'  => $laporanSikad->menu_id,
            'route'      => 'LaporanSikad.LaporanSuratPermohonan',
            'order'      => 1,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $laporanTransaksiKegiatan =  Menu::create([
            'name'       => 'Laporan Transaksi Kegiatan',
            'id_parent'  => $laporanSikad->menu_id,
            'route'      => 'LaporanSikad.LaporanTransaksiKegiatan',
            'order'      => 1,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $laporanTransaksiIdulFitri =  Menu::create([
            'name'       => 'Laporan Transaksi Idul Fitri',
            'id_parent'  => $laporanSikad->menu_id,
            'route'      => 'LaporanSikad.LaporanTransaksiIdulFitri',
            'order'      => 2,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $dataStatistik = Menu::create([
            'name'       => 'Data Statistik',
            'icon'       => 'fa fa-chart-pie',
            'order'      => 18,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $dataCovid =  Menu::create([
            'name'       => 'Data Covid-19',
            'id_parent'  => $laporanSikad->menu_id,
            'route'      => 'DataStatistik.DataCovid',
            'order'      => 1,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        $whatApp = Menu::create([
            'name'       => 'WhatsApp',
            'icon'       => 'fa fa-phone',
            'order'      => 19,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $dataCovid =  Menu::create([
            'name'       => 'Tambah Baru',
            'id_parent'  => $whatApp->menu_id,
            'route'      => 'WhatsApp.New',
            'order'      => 1,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $kelurahan = Menu::create([
            'name'       => 'Kelurahan',
            'icon'       => 'fa fa-landmark',
            'order'      => 20,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $suratKelurahan =  Menu::create([
            'name'       => 'Surat',
            'id_parent'  => $kelurahan->menu_id,
            'route'      => 'Kelurahan.Surat',
            'order'      => 1,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $suratKelurahanMasuk =  Menu::create([
            'name'       => 'Surat Masuk',
            'id_parent'  => $kelurahan->menu_id,
            'route'      => 'Kelurahan.SuratMasuk',
            'order'      => 1,
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }

    $pbb = Menu::create([
        'name'       => 'PBB',
        'icon'       => 'fa fa-percent',
        'order'      => 19,
        'created_at' => $now,
        'updated_at' => $now
    ]);


    $pembagianPbb =  Menu::create([
        'name'       => 'Pembagian PBB',
        'id_parent'  => $pbb->menu_id,
        'route'      => 'pbb.pembagian',
        'order'      => 1,
        'created_at' => $now,
        'updated_at' => $now
    ]);

    $pembayaranPbb =  Menu::create([
        'name'       => 'Pembayaran PBB',
        'id_parent'  => $pbb->menu_id,
        'route'      => 'pbb.pembayaran',
        'order'      => 2,
        'created_at' => $now,
        'updated_at' => $now
    ]);
}
