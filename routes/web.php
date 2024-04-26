<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::post('reset-password/process', 'Login\LupaPasswordController@resetPasswordProcess')->name('reset-password-process');
Route::get('reset-password', 'Login\LupaPasswordController@resetPassword')->name('reset-password')->middleware('signed');
Route::post('LupaPassword/sendEmailResetPass', 'Login\LupaPasswordController@sendEmailResetPass')->name('Login.LupaPassword.sendEmailResetPass');
Route::post('LupaPassword/checkEmail', 'Login\LupaPasswordController@checkEmail')->name('Login.LupaPassword.checkEmail');
Route::resource('LupaPassword', 'Login\LupaPasswordController', ['names' => 'Login.LupaPassword']);

Route::resource('checkSuratPermohonan', 'Surat\CheckSuratPermohonanController', ['names' => 'Surat.checkSuratPermohonan']);

Route::get('get-cities', 'DetailAlamatController@getCities')->name('DetailAlamat.getCities');
Route::get('get-subdistricts', 'DetailAlamatController@getSubdistricts')->name('DetailAlamat.getSubdistricts');
Route::get('get-Kelurahan', 'DetailAlamatController@getKelurahan')->name('DetailAlamat.getKelurahan');
Route::get('get-RW', 'DetailAlamatController@getRW')->name('DetailAlamat.getRW');
Route::get('get-RT', 'DetailAlamatController@getRT')->name('DetailAlamat.getRT');
Route::get('get-Blok', 'DetailAlamatController@getBlok')->name('DetailAlamat.getBlok');

Route::prefix('Logo')->group(function () {
    Route::get('Kabupaten/dataTables', 'LogoKabupatenController@dataTables')->name('LogoKabupaten.dataTables');
    Route::resource('Kabupaten', 'LogoKabupatenController', ['names' => 'LogoKabupaten']);
});

Route::group(['middleware' => ['auth', 'web', 'check_route']], function () {
    Route::prefix('role-management')->group(function () {
        Route::get('/role/dataTables', 'RoleManagement\RoleController@dataTables')->name('master.role.dataTables');
        Route::resource('role', 'RoleManagement\RoleController', ['names' => 'master.role']);

        // menu
        Route::get('/menu/dataTables', 'RoleManagement\MenuController@dataTables')->name('master.menu.dataTables');
        Route::resource('menu', 'RoleManagement\MenuController', ['names' => 'master.menu']);

        // permission
        Route::get('/permission/dataTables', 'RoleManagement\PermissionController@dataTables')->name('master.permission.dataTables');
        Route::resource('permission', 'RoleManagement\PermissionController', ['names' => 'master.permission']);

        // role-menu
        Route::get('/role-menu/dataTables', 'RoleManagement\RoleMenuController@dataTables')->name('master.role-menu.dataTables');
        Route::resource('role-menu', 'RoleManagement\RoleMenuController', ['names' => 'master.role-menu']);

        // menu-permission
        Route::get('/menu-permission/dataTables', 'RoleManagement\MenuPermissionController@dataTables')->name('master.menu-permission.dataTables');
        Route::resource('menu-permission', 'RoleManagement\MenuPermissionController', ['names' => 'master.menu-permission']);

        // user
        Route::post('/user/updateProfilPicture/{id}', 'RoleManagement\UserController@updateProfilPicture')->name('master.user.updateProfilPicture');
        Route::get('/user/dataTables', 'RoleManagement\UserController@dataTables')->name('master.user.dataTables');
        Route::resource('user', 'RoleManagement\UserController', ['names' => 'master.user']);

        Route::get('ListRtRwRtRw/dataTables', 'RoleManagement\ListRtRwController@dataTables')->name('role_management.ListRtRw.dataTables');
        Route::resource('ListRtRw', 'RoleManagement\ListRtRwController', ['names' => 'role_management.ListRtRw']);

        Route::get('ListWarga/dataTables', 'RoleManagement\ListWargaController@dataTables')->name('role_management.ListWarga.dataTables');
        Route::resource('ListWarga', 'RoleManagement\ListWargaController', ['names' => 'role_management.ListWarga']);
        Route::put('/ListWarga/ApprovalRT/{id}', 'RoleManagement\ListWargaController@ApprovalRT')->name('role_management.ListWarga.ApprovalRT');
    });

    Route::resource('/', 'landing\LandingController', ['names' => 'landing']);
    Route::get('/search', 'landing\LandingController@search')->name('landing.search');
    Route::get('/searchTahunPajak/{tahun_pajak}', 'landing\LandingController@searchTahunPajak')->name('landing.searchTahunPajak');

    Route::resource('FormValidation', 'formValidation\FormValidationController', ['names' => 'FormValidation']);

    Route::prefix('Beranda')->group(function () {
        Route::get('Header/dataTables', 'beranda\HeaderController@dataTables')->name('Beranda.Header.dataTables');
        Route::resource('Header', 'beranda\HeaderController', ['names' => 'Beranda.Header']);

        Route::get('Kontak/dataTables', 'beranda\KontakController@dataTables')->name('Beranda.Kontak.dataTables');
        Route::resource('Kontak', 'beranda\KontakController', ['names' => 'Beranda.Kontak']);
    });

    Route::prefix('Program')->group(function () {
        Route::get('Kegiatan/dataTables', 'program\KegiatanController@dataTables')->name('Program.Kegiatan.dataTables');
        Route::resource('Kegiatan', 'program\KegiatanController', ['names' => 'Program.Kegiatan']);
    });

    Route::prefix('Pengumuman')->group(function () {
        Route::get('List/dataTables', 'pengumuman\PengumumanController@dataTables')->name('Pengumuman.List.dataTables');
        Route::resource('List', 'pengumuman\PengumumanController', ['names' => 'Pengumuman.List']);
    });

    Route::prefix('Kajian')->group(function () {
        Route::get('Kategori/dataTables', 'kajian\KategoriController@dataTables')->name('Kajian.Kategori.dataTables');
        Route::resource('Kategori', 'kajian\KategoriController', ['names' => 'Kajian.Kategori']);

        Route::get('Konten/dataTables', 'kajian\KajianController@dataTables')->name('Kajian.Konten.dataTables');
        Route::post('Konten/checkMateri/{id}', 'kajian\KajianController@checkMateri')->name('Kajian.Kontent.checkMateri');
        Route::post('Konten/deleteMateri/{id}', 'kajian\KajianController@deleteMateri')->name('Kajian.Konten.deleteMateri');
        Route::resource('Konten', 'kajian\KajianController', ['names' => 'Kajian.Konten']);
    });

    Route::prefix('keluhKesan')->group(function () {
        Route::get('keluhKesan/dataTables', 'KeluhKesan\KeluhKesanController@dataTables')->name('keluhKesan.keluhKesan.dataTables');
        Route::resource('keluhKesan', 'KeluhKesan\KeluhKesanController', ['names' => 'keluhKesan.keluhKesan']);

        Route::get('keluhKesan/getBalasan/{id}', 'KeluhKesan\KeluhKesanController@getBalasan')->name('keluhKesan.keluhKesan.getBalasan');
        Route::post('keluhKesan/storeBalasan', 'KeluhKesan\KeluhKesanController@storeBalasan')->name('keluhKesan.keluhKesan.storeBalasan');
        Route::post('keluhKesan/updateBalasan', 'KeluhKesan\KeluhKesanController@updateBalasan')->name('keluhKesan.keluhKesan.updateBalasan');
        Route::post('keluhKesan/destroyBalasan', 'KeluhKesan\KeluhKesanController@destroyBalasan')->name('keluhKesan.keluhKesan.destroyBalasan');
    });

    Route::prefix('fasilitas')->group(function () {
        Route::get('fasilitas/dataTables', 'Fasilitas\FasilitasController@dataTables')->name('fasilitas.fasilitas.dataTables');
        Route::resource('fasilitas', 'Fasilitas\FasilitasController', ['names' => 'fasilitas.fasilitas']);
    });

    Route::prefix('Gallery')->group(function () {
        Route::get('GalleryList/dataTables', 'Gallery\GalleryController@dataTables')->name('Gallery.List.dataTables');
        Route::resource('GalleryList', 'Gallery\GalleryController', ['names' => 'Gallery.List']);

        Route::get('{galeri_id}/GalleryContent/dataTables', 'Gallery\GalleryContentController@dataTables')->name('Gallery.Content.dataTables');
        Route::resource('{galeri_id}/GalleryContent', 'Gallery\GalleryContentController', ['names' => 'Gallery.Content']);
    });

    Route::prefix('Tentang')->group(function () {
        Route::get('Profile/dataTables', 'Tentang\ProfileController@dataTables')->name('Tentang.Profile.dataTables');
        Route::resource('Profile', 'Tentang\ProfileController', ['names' => 'Tentang.Profile']);

        Route::get('Pengurus/KategoriPengurus/dataTables', 'Tentang\Pengurus\KategoriPengurusController@dataTables')->name('Tentang.KategoriPengurus.dataTables');
        Route::resource('Pengurus/KategoriPengurus', 'Tentang\Pengurus\KategoriPengurusController', ['names' => 'Tentang.KategoriPengurus']);

        Route::get('Pengurus/ListPengurus/dataTables', 'Tentang\Pengurus\ListPengurusController@dataTables')->name('Tentang.ListPengurus.dataTables');
        Route::resource('Pengurus/ListPengurus', 'Tentang\Pengurus\ListPengurusController', ['names' => 'Tentang.ListPengurus']);
    });

    Route::prefix('Laporan')->group(function () {
        Route::get('KategoriLaporan/dataTables', 'Laporan\KategoriLaporanController@dataTables')->name('Laporan.KategoriLaporan.dataTables');
        Route::resource('KategoriLaporan', 'Laporan\KategoriLaporanController', ['names' => 'Laporan.KategoriLaporan']);

        Route::get('Laporan/dataTables', 'Laporan\LaporanController@dataTables')->name('Laporan.Laporan.dataTables');
        Route::resource('Laporan', 'Laporan\LaporanController', ['names' => 'Laporan.Laporan']);
    });

    Route::prefix('Agenda')->group(function () {
        Route::get('AgendaKegiatan/dataTables', 'Agenda\AgendaKegiatanController@dataTables')->name('Agenda.AgendaKegiatan.dataTables');
        Route::resource('AgendaKegiatan', 'Agenda\AgendaKegiatanController', ['names' => 'Agenda.AgendaKegiatan']);
    });

    Route::prefix('SumberBiaya')->group(function () {
        Route::get('KategoriSumberBiaya/dataTables', 'SumberBiaya\KatSumberBiayaController@dataTables')->name('SumberBiaya.KatSumberBiaya.dataTables');
        Route::resource('KategoriSumberBiaya', 'SumberBiaya\KatSumberBiayaController', ['names' => 'SumberBiaya.KatSumberBiaya']);
    });

    Route::prefix('Transaksi')->group(function () {
        Route::post('Header/getKepalaKeluarga', 'Transaksi\HeaderTransaksiController@getKepalaKeluarga')->name('Transaksi.Header.getKepalaKeluarga');
        Route::post('Header/getKodeKegiatan', 'Transaksi\HeaderTransaksiController@getKodeKegiatan')->name('Transaksi.Header.getKodeKegiatan');
        Route::post('Header/getJenisTransaksi', 'Transaksi\HeaderTransaksiController@getJenisTransaksi')->name('Transaksi.Header.getJenisTransaksi');
        Route::post('Header/saveDetail', 'Transaksi\HeaderTransaksiController@saveDetail')->name('Transaksi.Header.saveDetail');
        Route::post('Header/deleteDetail/{id}', 'Transaksi\HeaderTransaksiController@deleteDetail')->name('Transaksi.Header.deleteDetail');
        Route::post('Header/updateDetail/{id}', 'Transaksi\HeaderTransaksiController@updateDetail')->name('Transaksi.Header.updateDetail');
        Route::get('Header/dataTables', 'Transaksi\HeaderTransaksiController@dataTables')->name('Transaksi.Header.dataTables');
        Route::get('Header/create_pdf/{id}', 'Transaksi\HeaderTransaksiController@create_pdf')->name('Transaksi.Header.create_pdf');
        Route::resource('Header', 'Transaksi\HeaderTransaksiController', ['names' => 'Transaksi.Header']);

        Route::post('DKM/deleteDetail/{id}', 'Transaksi\HeaderTransaksiController@deleteDetail')->name('Transaksi.DKM.deleteDetail');
        Route::post('DKM/updateDetail/{id}', 'Transaksi\HeaderTransaksiController@updateDetail')->name('Transaksi.DKM.updateDetail');
        Route::post('DKM/saveDetail', 'Transaksi\HeaderTransaksiController@saveDetail')->name('Transaksi.DKM.saveDetail');
        Route::get('DKM/dataTables', 'Transaksi\DKMTransaksiController@dataTables')->name('Transaksi.DKM.dataTables');
        Route::get('DKM/create_pdf/{id}', 'Transaksi\DKMTransaksiController@create_pdf')->name('Transaksi.DKM.create_pdf');
        Route::resource('DKM', 'Transaksi\DKMTransaksiController', ['names' => 'Transaksi.DKM']);

        Route::get('Approval/dataTables', 'Transaksi\ApprovalTransaksiController@dataTables')->name('Transaksi.Approval.dataTables');
        Route::resource('Approval', 'Transaksi\ApprovalTransaksiController', ['names' => 'Transaksi.Approval']);

        Route::get('ApprovalDKM/dataTables', 'Transaksi\ApprovalTransaksiDKMController@dataTables')->name('Transaksi.ApprovalDKM.dataTables');
        Route::resource('ApprovalDKM', 'Transaksi\ApprovalTransaksiDKMController', ['names' => 'Transaksi.ApprovalDKM']);
    });

    Route::prefix('Polling')->group(function () {
        Route::post('Pertanyaan/getRTsByRW', 'Polling\PertanyaanController@getRTsByRW')->name('Polling.Pertanyaan.getRTsByRW');
        Route::post('Pertanyaan/getRWsByWard', 'Polling\PertanyaanController@getRWsByWard')->name('Polling.Pertanyaan.getRWsByWard');
        Route::post('Pertanyaan/getWardsBySubdistrict', 'Polling\PertanyaanController@getWardsBySubdistrict')->name('Polling.Pertanyaan.getWardsBySubdistrict');
        Route::post('Pertanyaan/getSubdistrictsByCity', 'Polling\PertanyaanController@getSubdistrictsByCity')->name('Polling.Pertanyaan.getSubdistrictsByCity');
        Route::post('Pertanyaan/getCitiesByProvince', 'Polling\PertanyaanController@getCitiesByProvince')->name('Polling.Pertanyaan.getCitiesByProvince');
        Route::post('Pertanyaan/removeAnswer', 'Polling\PertanyaanController@removeAnswer')->name('Polling.Pertanyaan.removeAnswer');
        Route::get('Pertanyaan/dataTables', 'Polling\PertanyaanController@dataTables')->name('Polling.Pertanyaan.dataTables');
        Route::resource('Pertanyaan', 'Polling\PertanyaanController', ['names' => 'Polling.Pertanyaan']);

        Route::get('Polling/{id}/editPolling', 'Polling\PollingController@editPolling')->name('Polling.Polling.editPolling');
        Route::get('Polling/show_poll/{id}', 'Polling\PollingController@show_poll')->name('Polling.Polling.show_poll');
        Route::get('Polling/dataTables', 'Polling\PollingController@dataTables')->name('Polling.Polling.dataTables');
        Route::resource('Polling', 'Polling\PollingController', ['names' => 'Polling.Polling']);

        Route::get('Laporan/dataTables', 'Polling\LaporanController@dataTables')->name('Polling.Laporan.dataTables');
        Route::resource('Laporan', 'Polling\LaporanController', ['names' => 'Polling.Laporan']);

        Route::get('LaporanAudit/dataTables', 'Polling\LaporanAuditController@dataTables')->name('Polling.LaporanAudit.dataTables');
        Route::resource('Laporan-Audit', 'Polling\LaporanAuditController', ['names' => 'Polling.LaporanAudit']);

        Route::get('LaporanRT/dataTables', 'Polling\LaporanRTController@dataTables')->name('Polling.LaporanRT.dataTables');
        Route::resource('Laporan-RT', 'Polling\LaporanRTController', ['names' => 'Polling.LaporanRT']);
    });

    Route::prefix('Master')->group(function () {
        Route::get('Blok/dataTables', 'Master\BlokController@dataTables')->name('Master.Blok.dataTables');
        Route::resource('Blok', 'Master\BlokController', ['names' => 'Master.Blok']);

        Route::get('Keluarga/HubKeluarga/dataTables', 'Master\Keluarga\HubKeluargaController@dataTables')->name('Master.HubKeluarga.dataTables');
        Route::resource('Keluarga/HubKeluarga', 'Master\Keluarga\HubKeluargaController', ['names' => 'Master.HubKeluarga']);

        Route::post('Keluarga/ListKeluarga/storeAnggotaKeluarga', 'Master\Keluarga\ListKeluargaController@storeAnggotaKeluarga')->name('Master.ListKeluarga.storeAnggotaKeluarga');
        Route::post('Keluarga/ListKeluarga/updateAnggotaKeluarga/{id}', 'Master\Keluarga\ListKeluargaController@updateAnggotaKeluarga')->name('Master.ListKeluarga.updateAnggotaKeluarga');
        Route::post('Keluarga/ListKeluarga/updateKeluarga/{id}', 'Master\Keluarga\ListKeluargaController@updateKeluarga')->name('Master.ListKeluarga.updateKeluarga');
        Route::get('Keluarga/ListKeluarga/dataTables', 'Master\Keluarga\ListKeluargaController@dataTables')->name('Master.ListKeluarga.dataTables');
        Route::resource('Keluarga/ListKeluarga', 'Master\Keluarga\ListKeluargaController', ['names' => 'Master.ListKeluarga']);
        Route::post('Keluarga/ListKeluarga/getDataWarga/{id}', 'Master\Keluarga\ListKeluargaController@getDataWarga')->name('Master.ListKeluarga.getDataWarga');

        Route::get('Keluarga/MutasiWarga/dataTables', 'Master\Keluarga\MutasiWargaController@dataTables')->name('Master.MutasiWarga.dataTables');
        Route::resource('Keluarga/MutasiWarga', 'Master\Keluarga\MutasiWargaController', ['names' => 'Master.MutasiWarga']);

        Route::post('Keluarga/AnggotaKeluarga/getDataKeluarga/{id}', 'Master\Keluarga\AnggotaKeluargaController@getDataKeluarga')->name('Master.AnggotaKeluarga.getDataKeluarga');
        Route::get('Keluarga/AnggotaKeluarga/dataTables', 'Master\Keluarga\AnggotaKeluargaController@dataTables')->name('Master.AnggotaKeluarga.dataTables');
        Route::resource('Keluarga/AnggotaKeluarga', 'Master\Keluarga\AnggotaKeluargaController', ['names' => 'Master.AnggotaKeluarga']);

        Route::get('Kegiatan/KatKegiatan/dataTables', 'Master\Kegiatan\KatKegiatanController@dataTables')->name('Master.KatKegiatan.dataTables');
        Route::resource('Kegiatan/KatKegiatan', 'Master\Kegiatan\KatKegiatanController', ['names' => 'Master.KatKegiatan']);

        Route::get('Kegiatan/ListKegiatan/dataTables', 'Master\Kegiatan\ListKegiatanController@dataTables')->name('Master.ListKegiatan.dataTables');
        Route::resource('Kegiatan/ListKegiatan', 'Master\Kegiatan\ListKegiatanController', ['names' => 'Master.ListKegiatan']);

        Route::get('Transaksi/Transaksi/dataTables', 'Master\Transaksi\TransaksiController@dataTables')->name('Master.Transaksi.dataTables');
        Route::resource('Transaksi/Transaksi', 'Master\Transaksi\TransaksiController', ['names' => 'Master.Transaksi']);

        Route::get('Transaksi/JenisTransaksi/dataTables', 'Master\Transaksi\JenisTransaksiController@dataTables')->name('Master.JenisTransaksi.dataTables');
        Route::resource('Transaksi/JenisTransaksi', 'Master\Transaksi\JenisTransaksiController', ['names' => 'Master.JenisTransaksi']);

        Route::get('RT/dataTables', 'Master\RT\RTController@dataTables')->name('Master.rt.dataTables');
        Route::resource('RT', 'Master\RT\RTController', ['names' => 'Master.rt']);

        Route::get('RW/dataTables', 'Master\RW\RWController@dataTables')->name('Master.rw.dataTables');
        Route::resource('RW', 'Master\RW\RWController', ['names' => 'Master.rw']);

        Route::get('Kelurahan/dataTables', 'Master\Kelurahan\KelurahanController@dataTables')->name('Master.kelurahan.dataTables');
        Route::resource('Kelurahan', 'Master\Kelurahan\KelurahanController', ['names' => 'Master.kelurahan']);

        Route::get('JenisSurat/dataTables', 'Master\JenisSurat\JenisSuratController@dataTables')->name('Master.JenisSurat.dataTables');
        Route::resource('JenisSurat', 'Master\JenisSurat\JenisSuratController', ['names' => 'Master.JenisSurat']);

        Route::get('SumberSurat/dataTables', 'Master\Surat\SumberSuratController@dataTables')->name('Master.SumberSurat.dataTables');
        Route::resource('SumberSurat', 'Master\Surat\SumberSuratController', ['names' => 'Master.SumberSurat']);

        Route::get('SifatSurat/dataTables', 'Master\Surat\SifatSuratController@dataTables')->name('Master.SifatSurat.dataTables');
        Route::resource('SifatSurat', 'Master\Surat\SifatSuratController', ['names' => 'Master.SifatSurat']);

        Route::get('JenisSuratRw/dataTables', 'Master\Surat\JenisSuratRwController@dataTables')->name('Master.JenisSuratRw.dataTables');
        Route::resource('JenisSuratRw', 'Master\Surat\JenisSuratRwController', ['names' => 'Master.JenisSuratRw']);

        Route::get('RW/dataTables', 'Master\RW\RWController@dataTables')->name('Master.rw.dataTables');
        Route::resource('RW', 'Master\RW\RWController', ['names' => 'Master.rw']);

        Route::get('CapRW/dataTables', 'Master\RW\CapRWController@dataTables')->name('Master.CapRW.dataTables');
        Route::resource('CapRW', 'Master\RW\CapRWController', ['names' => 'Master.CapRW']);

        Route::get('TandaTanganRW/dataTables', 'Master\RW\TandaTanganRWController@dataTables')->name('Master.TandaTanganRW.dataTables');
        Route::resource('TandaTanganRW', 'Master\RW\TandaTanganRWController', ['names' => 'Master.TandaTanganRW']);

        Route::get('CapKelurahan/dataTables', 'Master\Kelurahan\CapKelurahanController@dataTables')->name('Master.CapKelurahan.dataTables');
        Route::resource('CapKelurahan', 'Master\Kelurahan\CapKelurahanController', ['names' => 'Master.CapKelurahan']);

        Route::get('TandaTanganKelurahan/dataTables', 'Master\Kelurahan\TandaTanganKelurahanController@dataTables')->name('Master.TandaTanganKelurahan.dataTables');
        Route::resource('TandaTanganKelurahan', 'Master\Kelurahan\TandaTanganKelurahanController', ['names' => 'Master.TandaTanganKelurahan']);

        Route::get('Agama/dataTables', 'Master\AgamaController@dataTables')->name('Master.Agama.dataTables');
        Route::resource('Agama', 'Master\AgamaController', ['names' => 'Master.Agama']);

        Route::get('StatusPernikahan/dataTables', 'Master\StatusPernikahanController@dataTables')->name('Master.StatusPernikahan.dataTables');
        Route::resource('StatusPernikahan', 'Master\StatusPernikahanController', ['names' => 'Master.StatusPernikahan']);

        Route::get('CapRT/dataTables', 'Master\CapRtController@dataTables')->name('Master.CapRT.dataTables');
        Route::resource('CapRT', 'Master\CapRtController', ['names' => 'Master.CapRT']);

        Route::get('TandaTanganRT/dataTables', 'Master\TandaTanganRTController@dataTables')->name('Master.Tanda_Tangan_RT.dataTables');
        Route::resource('TandaTanganRT', 'Master\TandaTanganRTController', ['names' => 'Master.Tanda_Tangan_RT']);

        Route::get('TeleponPenting/dataTables', 'Master\TeleponPentingController@dataTables')->name('Master.TeleponPenting.dataTables');
        Route::resource('TeleponPenting', 'Master\TeleponPentingController', ['names' => 'Master.TeleponPenting']);
    });

    Route::prefix('Surat')->group(function () {

        Route::get('SuratPermohonan/dataTables', 'Surat\SuratPermohonanController@dataTables')->name('Surat.SuratPermohonan.dataTables');
        Route::get('SuratPermohonan/editLampiran/{id}', 'Surat\SuratPermohonanController@editAttachment')->name('Surat.SuratPermohonan.editAttachment');
        Route::get('SuratPermohonan/previewSurat/{id}', 'Surat\SuratPermohonanController@previewSurat')->name('Surat.SuratPermohonan.previewSurat');
        Route::post('SuratPermohonan/getDataWarga/{id}', 'Surat\SuratPermohonanController@getDataWarga')->name('Surat.SuratPermohonan.getDataWarga');
        Route::post('SuratPermohonan/getDataLampiran/{id}', 'Surat\SuratPermohonanController@getDataLampiran')->name('Surat.SuratPermohonan.getDataLampiran');
        Route::resource('SuratPermohonan', 'Surat\SuratPermohonanController', ['names' => 'Surat.SuratPermohonan']);
        Route::put('/SuratPermohonan/ApprovalDraft/{id}', 'Surat\SuratPermohonanController@ApprovalDraft')->name('Surat.SuratPermohonan.ApprovalDraft');
        Route::put('/SuratPermohonan/ApprovalRT/{id}', 'Surat\SuratPermohonanController@ApprovalRT')->name('Surat.SuratPermohonan.ApprovalRT');
        Route::put('/SuratPermohonan/ApprovalRW/{id}', 'Surat\SuratPermohonanController@ApprovalRW')->name('Surat.SuratPermohonan.ApprovalRW');

        Route::post('SuratMasukKeluarRw/generateNoSuratKeluar', 'Surat\SuratMasukKeluarRwController@generateNoSuratKeluar')->name('Surat.SuratMasukKeluarRw.generateNoSuratKeluar');
        Route::get('SuratMasukKeluarRw/dataTables', 'Surat\SuratMasukKeluarRwController@dataTables')->name('Surat.SuratMasukKeluarRw.dataTables');
        Route::resource('SuratMasukKeluarRw', 'Surat\SuratMasukKeluarRwController', ['names' => 'Surat.SuratMasukKeluarRw']);

        Route::get('Lampiran/dataTables', 'Surat\LampiranController@dataTables')->name('Surat.Lampiran.dataTables');
        Route::resource('Lampiran', 'Surat\LampiranController', ['names' => 'Surat.Lampiran']);
    });

    Route::prefix('LaporanSikad')->group(function () {

        Route::get('LaporanSuratPermohonan/dataTables', 'LaporanSikad\LaporanSuratPermohonanController@dataTables')->name('LaporanSikad.LaporanSuratPermohonan.dataTables');
        Route::post('LaporanSuratPermohonan/searchLaporan', 'LaporanSikad\LaporanSuratPermohonanController@searchLaporan')->name('LaporanSikad.LaporanSuratPermohonan.searchLaporan');
        Route::resource('LaporanSuratPermohonan', 'LaporanSikad\LaporanSuratPermohonanController', ['names' => 'LaporanSikad.LaporanSuratPermohonan']);

        Route::get('LaporanTransaksiKegiatan/dataTables', 'LaporanSikad\LaporanTransaksiKegiatanController@dataTables')->name('LaporanSikad.LaporanTransaksiKegiatan.dataTables');
        Route::get('LaporanTransaksiKegiatan/printLaporanByKegiatan', 'LaporanSikad\LaporanTransaksiKegiatanController@printLaporanByKegiatan')->name('LaporanSikad.LaporanTransaksiKegiatan.printLaporanByKegiatan');
        Route::post('LaporanTransaksiKegiatan/searchLaporanByKegiatan', 'LaporanSikad\LaporanTransaksiKegiatanController@searchLaporanByKegiatan')->name('LaporanSikad.LaporanTransaksiKegiatan.searchLaporanByKegiatan');
        Route::post('LaporanTransaksiKegiatan/searchLaporan', 'LaporanSikad\LaporanTransaksiKegiatanController@searchLaporan')->name('LaporanSikad.LaporanTransaksiKegiatan.searchLaporan');
        Route::resource('LaporanTransaksiKegiatan', 'LaporanSikad\LaporanTransaksiKegiatanController', ['names' => 'LaporanSikad.LaporanTransaksiKegiatan']);

        Route::get('LaporanTransaksiKegiatanDKM/printLaporanByKegiatan', 'LaporanSikad\LaporanTransaksiKegiatanDKMController@printLaporanByKegiatan')->name('LaporanSikad.LaporanTransaksiKegiatanDKM.printLaporanByKegiatan');
        Route::resource('LaporanTransaksiKegiatanDKM', 'LaporanSikad\LaporanTransaksiKegiatanDKMController', ['names' => 'LaporanSikad.LaporanTransaksiKegiatanDKM']);

        Route::get('LaporanTransaksiIdulFitri/printLaporanByKegiatan', 'LaporanSikad\LaporanTransaksiIdulFitriController@printLaporanByKegiatan')->name('LaporanSikad.LaporanTransaksiIdulFitri.printLaporanByKegiatan');
        Route::post('LaporanTransaksiIdulFitri/searchLaporan', 'LaporanSikad\LaporanTransaksiIdulFitriController@searchLaporan')->name('LaporanSikad.LaporanTransaksiIdulFitri.searchLaporan');
        Route::get('LaporanTransaksiIdulFitri/dataTables', 'LaporanSikad\LaporanTransaksiIdulFitriController@dataTables')->name('LaporanSikad.LaporanTransaksiIdulFitri.dataTables');
        Route::resource('LaporanTransaksiIdulFitri', 'LaporanSikad\LaporanTransaksiIdulFitriController', ['names' => 'LaporanSikad.LaporanTransaksiIdulFitri']);
    });

    Route::prefix('Geolocation')->group(function () {
        Route::post('Sitemap/getBlokData/', 'Geolocation\SitemapController@getBlokData')->name('Geolocation.Sitemap.getBlokData');
        Route::resource('Sitemap', 'Geolocation\SitemapController', ['names' => 'Geolocation.Sitemap']);
    });

    Route::prefix('DataStatistik')->group(function () {
        Route::get('DataCovid/dataTables', 'DataStatistik\DataCovidController@dataTables')->name('DataStatistik.DataCovid.dataTables');
        Route::resource('DataCovid', 'DataStatistik\DataCovidController', ['names' => 'DataStatistik.DataCovid']);
    });

    Route::prefix('WhatsApp')->group(function () {
        Route::get('New', 'Whatsapp\WhatsappController@index')->name('WhatsApp.New.index');
        Route::post('New/Store', 'Whatsapp\WhatsappController@store')->name('WhatsApp.New.store');
        Route::post('create_test', 'Whatsapp\WhatsappController@create_test')->name('WhatsApp.create_test');
    });

    Route::post('checkSuratPermohonan/approveLurah/{id}', 'Surat\CheckSuratPermohonanController@approveLurah')->name('Surat.checkSuratPermohonan.approveLurah');
    Route::resource('checkSuratPermohonan', 'Surat\CheckSuratPermohonanController', ['names' => 'Surat.checkSuratPermohonan']);

    Route::prefix('Kelurahan')->group(function () {
        Route::get('Surat/previewSuratKelurahan/{id}', 'Kelurahan\SuratController@previewSuratKelurahan')->name('Kelurahan.Surat.previewSuratKelurahan');
        Route::get('Surat/previewSurat/{id}', 'Kelurahan\SuratController@previewSurat')->name('Kelurahan.Surat.previewSurat');
        Route::get('Surat/dataTables', 'Kelurahan\SuratController@dataTables')->name('Kelurahan.Surat.dataTables');
        Route::resource('Surat', 'Kelurahan\SuratController', ['names' => 'Kelurahan.Surat']);

        Route::get('SuratMasuk/dataTablesNoSearch', 'Kelurahan\SuratMasukController@dataTablesNoSearch')->name('Kelurahan.SuratMasuk.dataTablesNoSearch');
        Route::post('dataTables/search', 'Kelurahan\SuratMasukController@dataTables')->name('Kelurahan.SuratMasuk.dataTables');
        Route::post('SuratMasuk/storeLetterContent/{id}', 'Kelurahan\SuratMasukController@storeLetterContent')->name('Kelurahan.SuratMasuk.storeLetterContent');
        Route::resource('SuratMasuk', 'Kelurahan\SuratMasukController', ['names' => 'Kelurahan.SuratMasuk']);

        Route::get('Kepala-Seksi/dataTablesNoSearch', 'Kelurahan\KepalaSeksiController@dataTablesKepalaSeksiNoSearch')->name('Kelurahan.Kepala-Seksi.dataTablesKepalaSeksiNoSearch');
        Route::get('Kepala-Seksi/dataTables', 'Kelurahan\KepalaSeksiController@dataTables')->name('Kelurahan.Kepala-Seksi.dataTables');
        Route::resource('Kepala-Seksi', 'Kelurahan\KepalaSeksiController', ['names' => 'Kelurahan.Kepala-Seksi']);

        Route::get('Sekretaris/dataTablesNoSearch', 'Kelurahan\SekretarisController@dataTablesSecretaryNoSearch')->name('Kelurahan.Sekretaris.dataTablesSecretaryNoSearch');
        Route::get('Sekretaris/dataTables', 'Kelurahan\SekretarisController@dataTables')->name('Kelurahan.Sekretaris.dataTables');
        Route::resource('Sekretaris', 'Kelurahan\SekretarisController', ['names' => 'Kelurahan.Sekretaris']);

        Route::get('Lurah/dataTablesNoSearch', 'Kelurahan\LurahController@dataTablesGrooveNoSearch')->name('Kelurahan.Lurah.dataTablesGrooveNoSearch');
        Route::get('Lurah/dataTables', 'Kelurahan\LurahController@dataTables')->name('Kelurahan.Lurah.dataTables');
        Route::resource('Lurah', 'Kelurahan\LurahController', ['names' => 'Kelurahan.Lurah']);
    });

    Route::prefix('UMKM')->group(function () {
        Route::get('Medsos/dataTables', 'UMKM\MedsosController@dataTables')->name('UMKM.Medsos.dataTables');
        Route::resource('Medsos', 'UMKM\MedsosController', ['names' => 'UMKM.Medsos']);

        Route::get('Kategori/dataTables', 'UMKM\UmkmKategoriController@dataTables')->name('UMKM.Kategori.dataTables');
        Route::resource('Kategori', 'UMKM\UmkmKategoriController', ['names' => 'UMKM.Kategori']);

        Route::get('Umkm/dataTables', 'UMKM\UmkmController@dataTables')->name('UMKM.Umkm.dataTables');
        Route::resource('Umkm', 'UMKM\UmkmController', ['names' => 'UMKM.Umkm']);

        Route::get('Produk/dataTables', 'UMKM\UmkmProdukController@dataTables')->name('UMKM.Produk.dataTables');
        Route::post('Produk/cartImage/{id}', 'UMKM\UmkmProdukController@cartImage')->name('UMKM.Produk.cartImage');
        Route::resource('Produk', 'UMKM\UmkmProdukController', ['names' => 'UMKM.Produk']);

        Route::get('Pemesanan/dataTables', 'UMKM\PemesananController@dataTables')->name('UMKM.Pemesanan.dataTables');
        Route::resource('Pemesanan', 'UMKM\PemesananController', ['names' => 'UMKM.Pemesanan']);
    });

    Route::get('/home', 'HomeController@index')->name('home');

    Route::prefix('pbb')->group(function () {
        Route::get('pembagian/dataTables', 'PBB\PembagianController@dataTables')->name('pbb.pembagian.dataTables');
        Route::get('pembagian/getWargaByBlok', 'PBB\PembagianController@getWargaByBlok')->name('pbb.pembagian.getWargaByBlok');
        Route::resource('pembagian', 'PBB\PembagianController', ['names' => 'pbb.pembagian']);

        Route::get('pembayaran/dataTables', 'PBB\PembayaranController@dataTables')->name('pbb.pembayaran.dataTables');
        Route::get('/pembayaran/dataTables/{tahun_pajak}', 'PBB\PembayaranController@dataTables')->name('pbb.pembayaran.dataTables.filtered');
        Route::resource('pembayaran', 'PBB\PembayaranController', ['names' => 'pbb.pembayaran']);

        Route::get('nop/dataTables', 'PBB\NOPController@dataTables')->name('pbb.nop.dataTables');
        Route::resource('nop', 'PBB\NOPController', ['names' => 'pbb.nop']);
    });

    Route::prefix('RamahAnak')->group(function () {
        Route::get('Vaksin/dataTables', 'RamahAnak\VaksinController@dataTables')->name('RamahAnak.Vaksin.dataTables');
        Route::resource('Vaksin', 'RamahAnak\VaksinController', ['names' => 'RamahAnak.Vaksin']);

        Route::get('Posyandu/dataTables', 'RamahAnak\PosyanduController@dataTables')->name('RamahAnak.Posyandu.dataTables');
        Route::resource('Posyandu', 'RamahAnak\PosyanduController', ['names' => 'RamahAnak.Posyandu']);

        Route::get('Laporan/dataTables', 'RamahAnak\LaporanController@dataTables')->name('RamahAnak.Laporan.dataTables');
        Route::resource('Laporan', 'RamahAnak\LaporanController', ['names' => 'RamahAnak.Laporan']);
    });

    Route::prefix('Musrenbang')->group(function () {
        // Jenis
        Route::get('Master/Menu-Urusan/dataTables', 'Musrenbang\MenuUrusanController@dataTables')->name('Musrenbang.Menu-Urusan.dataTables');
        Route::resource('Master/Menu-Urusan', 'Musrenbang\MenuUrusanController', ['names' => 'Musrenbang.Menu-Urusan']);

        // Bidang
        Route::get('Master/Bidang-Urusan/dataTables', 'Musrenbang\BidangUrusanController@dataTables')->name('Musrenbang.Bidang-Urusan.dataTables');
        Route::resource('Master/Bidang-Urusan', 'Musrenbang\BidangUrusanController', ['names' => 'Musrenbang.Bidang-Urusan']);

        // Kegiatan
        Route::get('Master/Kegiatan-Urusan/dataTables', 'Musrenbang\KegiatanUrusanController@dataTables')->name('Musrenbang.Kegiatan-Urusan.dataTables');
        Route::resource('Master/Kegiatan-Urusan', 'Musrenbang\KegiatanUrusanController', ['names' => 'Musrenbang.Kegiatan-Urusan']);

        // Usulan 
        Route::get('Usulan-Urusan/dataTables', 'Musrenbang\UsulanUrusanController@dataTables')->name('Musrenbang.Usulan-Urusan.dataTables');
        Route::resource('Usulan-Urusan', 'Musrenbang\UsulanUrusanController', ['names' => 'Musrenbang.Usulan-Urusan']);

        Route::get('Approval-Kelurahan/dataTables', 'Musrenbang\ApprovalKelurahanController@dataTables')->name('Musrenbang.Approval-Kelurahan.dataTables');
        Route::resource('Approval-Kelurahan', 'Musrenbang\ApprovalKelurahanController', ['names' => 'Musrenbang.Approval-Kelurahan']);

        Route::get('Approval-Kecamatan/dataTables', 'Musrenbang\ApprovalKecamatanController@dataTables')->name('Musrenbang.Approval-Kecamatan.dataTables');
        Route::resource('Approval-Kecamatan', 'Musrenbang\ApprovalKecamatanController', ['names' => 'Musrenbang.Approval-Kecamatan']);

        Route::get('Approval-Walikota/dataTables', 'Musrenbang\ApprovalWalikotaController@dataTables')->name('Musrenbang.Approval-Walikota.dataTables');
        Route::resource('Approval-Walikota', 'Musrenbang\ApprovalWalikotaController', ['names' => 'Musrenbang.Approval-Walikota']);
    });

    Route::get('panduan', 'Panduan\PanduanController@index')->name('panduan.index');
    Route::post('panduan/update', 'Panduan\PanduanController@update')->name('panduan.update');
});

// Download mobile apk file
Route::get('app/{filename}', 'DownloadController@index')->where('filename', '[A-Za-z0-9\-\_\.]+');
