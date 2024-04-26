<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('header', 'API\ApiHeaderController');
Route::apiResource('kontak', 'API\ApiKontakController');

Route::post('program/searchProgram', 'API\ApiProgramController@searchProgram');
Route::apiResource('program', 'API\ApiProgramController');

Route::apiResource('pengumuman', 'API\ApiPengumumanController');
Route::apiResource('kat_kajian', 'API\ApiKatKajianController');
Route::apiResource('kajian', 'API\ApiKajianController');
Route::apiResource('fasilitas', 'API\ApiFasilitasController');
Route::apiResource('profile', 'API\ApiProfileController');
Route::apiResource('pengurus', 'API\ApiPengurusController');

Route::post('agenda/searchAgenda', 'API\ApiAgendaController@searchAgenda');
Route::apiResource('agenda', 'API\ApiAgendaController');

Route::apiResource('laporan', 'API\ApiLaporanController');
Route::apiResource('kat_laporan', 'API\ApiKatLaporanController');
Route::apiResource('galeri', 'API\ApiGaleryController');
Route::apiResource('galeri_konten', 'API\ApiGaleryContentController');

Route::get('jenisSurat', 'API\JenisSuratAPIController@getJenisSurat');
Route::get('katKegiatan/getKatKegiatan', 'API\KatKegiatanAPIController@getKatKegiatan')->name('katKegiatan.general');
Route::get('katKegiatan/getKatKegiatan/dkm', 'API\KatKegiatanAPIController@getKatKegiatan')->name('katKegiatan.DKM');
Route::post('kegiatan/getKegiatan', 'API\KegiatanAPIController@getKegiatan');
Route::post('laporanTransaksiKegiatan/searchResult', 'API\LaporanTransaksiKegiatanAPIController@searchResult');
Route::get('statusPernikahan', 'API\statusPernikahanAPIController@statusPernikahan');
Route::get('agama', 'API\AgamaAPIController@agama');
Route::get('blok/getBlok', 'API\BlockAPIController@getBlock');
Route::post('blok/getAnggotaKeluarga', 'API\BlockAPIController@getAnggotaKeluarga');
Route::get('jenisTransaksi/getJenisTransaksi', 'API\JenisTransaksiController@getJenisTransaksi');
Route::get('teleponPenting/getTeleponPenting', 'API\TeleponPentingAPIController@getTeleponPenting');

Route::get('hubKeluarga/getData', 'API\HubKeluargaAPIController@getData');

Route::get('covid19/getData', 'API\Covid19APIController@getData');

Route::post('tagihan/storeTagihan', 'API\TagihanController@storeTagihan');
Route::post('tagihan/storeTagihanDKM', 'API\TagihanController@storeTagihanDKM');
Route::post('tagihan/storeTagiganDKM/detail', 'API\TagihanController@storeDetailTagihanDKM');

Route::prefix('uploadLampiran')->group(function () {
    Route::post('temp', 'API\UploadLampiranAPIController@temp');
});

Route::post('uploadBuktiPembayaran/temp', 'API\BuktiPembayaranController@temp');

Route::prefix('suratPermohonan')->group(function () {
    Route::post('store', 'API\SuratPermohonanAPIController@store');
    Route::get('detail/{id}', 'API\SuratPermohonanAPIController@detail');
});

Route::prefix('transaksi')->group(function () {
    Route::get('detail/{id}', 'API\TransaksiAPIController@detail');
});

Route::prefix('laporan')->group(function () {
    Route::post('suratPermohonan/search', 'API\laporanSuratPermohonanAPIController@search');
});

Route::post('register', 'passportAuthController@registerUser');
Route::post('login', 'passportAuthController@loginUser');

Route::prefix('assets')->group(function () {
    Route::get('menuIcon', 'API\AssetAPIController@menuIcon');
});


Route::group(['middleware' => 'auth:api'], function () {
    Route::get('currentUser/transaction-status', 'API\currentUserController@transactionStatus');
    Route::post('currentUser/deleteProfilPic', 'API\currentUserController@deleteProfilPic');
    Route::post('currentUser/changeProfilPic', 'API\currentUserController@changeProfilPic');
    Route::post('currentUser/changePassword', 'API\currentUserController@changePassword');
    Route::get('currentUser', 'API\currentUserController@currentUser');

    Route::get('keluarga/getKepalaKeluarga', 'API\KeluargaAPIController@getKepalaKeluarga');
    Route::post('keluarga/addAnggotaKeluarga', 'API\KeluargaAPIController@addAnggotaKeluarga');
    Route::post('keluarga/getAnggotaKeluarga', 'API\KeluargaAPIController@getAnggotaKeluarga');

    Route::prefix('keluhKesan')->group(function () {
        Route::get('getKeluhKesan', 'API\KeluhKesanAPIController@getKeluhKesan');
        Route::post('getBalasKeluhanKesan', 'API\KeluhKesanAPIController@getBalasKeluhanKesan');
        Route::post('sendKeluhKesan', 'API\KeluhKesanAPIController@sendKeluhKesan');
        Route::post('sendKeluhKesan/temp', 'API\KeluhKesanAPIController@sendKeluhKesanTemp');
        Route::post('sendBalasKeluhKesan/temp', 'API\KeluhKesanAPIController@sendBalasKeluhKesanTemp');
        Route::post('sendKeluhKesan/delete', 'API\KeluhKesanAPIController@deleteTempImage');
        Route::post('sendBalasKeluhKesan/delete', 'API\KeluhKesanAPIController@deleteTempImage');
        Route::post('balasKeluhKesan', 'API\KeluhKesanAPIController@balasKeluhKesan');
    });
});

Route::apiResource('medsos', 'API\MedsosAPIController')->only('index');

Route::prefix('umkm')->group(function () {
    Route::get('', 'API\UmkmAPIController@index');
    Route::get('detail/{id}', 'API\UmkmAPIController@detail');
    Route::post('search', 'API\UmkmAPIController@search');
});

Route::apiResource('kategori', 'API\UmkmKategoriAPIController')->only('index');

Route::prefix('produk')->group(function () {
    Route::get('', 'API\UmkmProdukAPIController@index');
    Route::get('detail/{id}', 'API\UmkmProdukAPIController@detail');
    Route::post('search', 'API\UmkmProdukAPIController@search');
});

Route::group(['prefix' => 'v2', 'middleware' => 'auth:api'], function () {
    Route::get('pengumuman', 'API\V2\PengumumanController@get');
    Route::get('program', 'API\V2\ProgramController@get');
    Route::post('program/searchProgram', 'API\V2\ProgramController@searchProgram');
    Route::get('agenda', 'API\V2\AgendaController@get');
    Route::post('agenda/searchAgenda', 'API\V2\AgendaController@searchAgenda');

    Route::prefix('surat')->group(function () {
        Route::prefix('surat-permohonan')->group(function () {
            Route::post('getPemohon', 'API\V2\SuratPermohonanController@getPemohon');
            Route::post('detail', 'API\V2\SuratPermohonanController@detail');
            Route::get('active', 'API\V2\SuratPermohonanController@active');
            Route::get('finished', 'API\V2\SuratPermohonanController@finished');
            Route::get('wait-approval', 'API\V2\SuratPermohonanController@waitApproval');
            Route::post('approve', 'API\V2\SuratPermohonanController@approve');
            Route::post('getLampiran', 'API\V2\SuratPermohonanController@getLampiran');
            Route::post('store', 'API\V2\SuratPermohonanController@store');
            Route::post('update', 'API\V2\SuratPermohonanController@update');
        });
    });
    Route::prefix('keluarga')->group(function () {
        Route::post('addAnggotaKeluarga', 'API\V2\KeluargaController@addAnggotaKeluarga');
        Route::get('getBlok', 'API\V2\KeluargaController@getBlok');
        Route::post('updateAnggotaKeluarga', 'API\V2\KeluargaController@updateAnggotaKeluarga');
        Route::post('getAnggotaKeluarga', 'API\V2\KeluargaController@getAnggotaKeluarga');
        Route::post('anggotaKeluargaDetail', 'API\V2\KeluargaController@anggotaKeluargaDetail');
    });
    Route::prefix('produk')->group(function () {
        Route::get('', 'API\UmkmProdukAPIController@index');
        Route::get('detail/{id}', 'API\UmkmProdukAPIController@detail');
        Route::post('search', 'API\UmkmProdukAPIController@search');
    });
    Route::prefix('kegiatan')->group(function () {
        Route::get('/', 'API\V2\KegiatanController@get');
        Route::post('getKegiatanByKategori', 'API\V2\KegiatanController@getKegiatanByKategori');
    });
    Route::prefix('tagihan')->group(function () {
        Route::post('detail', 'API\V2\TagihanController@detail');
        Route::get('pending', 'API\V2\TagihanController@pending');
        Route::get('finished', 'API\V2\TagihanController@finished');
        Route::get('wait-approval', 'API\V2\TagihanController@waitApproval');
        Route::post('approve', 'API\V2\TagihanController@approve');
        Route::post('delete', 'API\V2\TagihanController@delete');
    });

    Route::prefix('umkm')->group(function () {
        Route::get('', 'API\V2\UmkmController@index');
        Route::get('get-map', 'API\V2\UmkmController@getMap');
        Route::get('list-umkm-per-blok', 'API\V2\UmkmController@listUmkmPerBlok');
        Route::get('list-medsos', 'API\V2\UmkmController@listMedsos');
        Route::get('detail/{id}', 'API\V2\UmkmController@detail');
        Route::get('detailAsOwner/', 'API\V2\UmkmController@detailAsOwner');
        Route::post('search', 'API\V2\UmkmController@search');
        Route::post('store', 'API\V2\UmkmController@store');
        Route::post('update', 'API\V2\UmkmController@update');
    });

    Route::prefix('produk')->group(function () {
        Route::get('', 'API\V2\UmkmProdukController@index');
        Route::get('listAsOwner', 'API\V2\UmkmProdukController@listAsOwner');
        Route::get('list-kategori', 'API\V2\UmkmProdukController@listKategori');
        Route::get('detail/{id}', 'API\V2\UmkmProdukController@detail');
        Route::post('search', 'API\V2\UmkmProdukController@search');
        Route::post('store', 'API\V2\UmkmProdukController@store');
        Route::post('update', 'API\V2\UmkmProdukController@update');
    });

    Route::prefix('galeri')->group(function () {
        Route::get('/', 'API\V2\GaleriController@get');
        Route::get('content', 'API\V2\GaleriController@content');
    });

    Route::prefix('fasilitas')->group(function () {
        Route::get('/', 'API\V2\FasilitasController@get');
        Route::get('content', 'API\V2\GaleriController@content');
    });

    Route::prefix('laporan')->group(function () {
        Route::post('transaksi-kegiatan', 'API\V2\LaporanTransaksiKegiatanController@get');
        Route::post('surat-permohonan', 'API\V2\LaporanSuratPermohonanController@get');
        Route::post('publish-laporan', 'API\V2\PublishLaporanController@get');
    });

    Route::prefix('pbb')->group(function () {
        Route::get('/', 'API\V2\PbbController@get');
        Route::post('detail', 'API\V2\PbbController@detail');
        Route::post('bayar', 'API\V2\PbbController@bayar');
    });

    Route::prefix('polling')->group(function () {
        Route::get('list-pertanyaan', 'API\V2\PollingController@listPertanyaan');
        Route::get('list-jawaban', 'API\V2\PollingController@listJawaban');
        Route::get('list-anggota', 'API\V2\PollingController@listAnggota');
        Route::post('store-jawaban', 'API\V2\PollingController@storeJawaban');
        Route::post('result', 'API\V2\PollingController@result');
    });

    Route::prefix('ramah-anak')->group(function () {
        Route::get('list-anak', 'API\V2\RamahAnakController@listAnak');
        Route::post('detail', 'API\V2\RamahAnakController@detail');
    });

    Route::prefix('pemesanan')->group(function () {
        Route::get('list-order-as-owner', 'API\V2\PemesananController@listOrderAsOwner');
        Route::get('list-order-as-buyer', 'API\V2\PemesananController@listOrderAsBuyer');
        Route::post('order', 'API\V2\PemesananController@order');
        Route::post('cancel-order', 'API\V2\PemesananController@Cancelorder');
        Route::post('confirm-order', 'API\V2\PemesananController@confirmOrder');
        Route::post('pay-order', 'API\V2\PemesananController@payOrder');
        Route::post('order-delivered', 'API\V2\PemesananController@orderDelivered');
    });
});
