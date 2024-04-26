<?php

use Database\Seeders\MusrenbangMasterSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([HeaderSeeder::class,
                    UserSeeder::class,
                    //Start Role Management seeder
                    MenuSeeder::class,
                    RoleSeeder::class,
                    PermissionSeeder::class,
                    RoleMenuSeeder::class,
                    UserRoleSeeder::class,
                    MenuPermissionSeeder::class,
                    //End Role Management seeder
                    KontakSeeder::class,
                    ProgramSeeder::class,
                    PengumumanSeeder::class,
                    KatKajianSeeder::class,
                    KajianSeeder::class,
                    KatPengurusSeeder::class,
                    KatLaporanSeeder::class,
                    AgendaSeeder::class,
                    ProfileSeeder::class,
                    PengurusSeeder::class,
                    AgendaSeeder::class,
                    LaporanSeeder::class,
                    GaleriSeeder::class,
                    GaleriKontenSeeder::class,
                    HubKeluargaSeeder::class,
                    BlokSeeder::class,
                    TransaksiSeeder::class,
                    KatKegiatanSeeder::class,
                    KegiatanSeeder::class,
                    KeluargaSeeder::class,
                    AnggotaKeluargaSeeder::class,
                    JenisTransaksiSeeder::class,
                    RTSeeder::class,
                    CapRtSeeder::class,
                    TandaTanganRtSeeder::class,
                    KelurahanSeeder::class,
                    RWSeeder::class,
                    CapRwSeeder::class,
                    TandaTanganRwSeeder::class,
                    JenisSuratSeeder::class,
                    SumberSuratSeeder::class,
                    SifatSuratSeeder::class,
                    JenisSuratRwSeeder::class,
                    AgamaSeeder::class,
                    StatusPernikahanSeeder::class,
                    MedsosSeeder::class,
                    // UMKMSeeder
                    UmkmProdukSeeder::class,
                    UmkmSeeder::class,
                    LampiranSeeder::class,
                    StatusDomisiliSeeder::class,
                    StatusMutasiWargaSeeder::class,
                    // Musrenbang
                    MusrenbangMasterSeeder::class,
                    MenuUrusanSeeder::class,
                    BidangUrusanSeeder::class,
                    KegiatanUrusanSeeder::class,
                    UsulanUrusanSeeder::class,
                    MusrenbangApprovalSeeder::class,
        ]);
    }
}
