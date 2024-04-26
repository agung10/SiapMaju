<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHaveUmkmToAnggotaKeluargaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('anggota_keluarga', function (Blueprint $table) {
            $table->boolean('have_umkm')->default(false)->nullable();
        });
    }

    public function down()
    {
        Schema::table('anggota_keluarga', function (Blueprint $table) {
            $table->dropColumn('have_umkm');
        });
    }
}
