<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKelurahanRwRtIdToAnggotaKeluargaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('anggota_keluarga', function (Blueprint $table) {
            $table->integer('kelurahan_id')->default(1)->nullable();
            $table->integer('rw_id')->default(1)->nullable();
            $table->integer('rt_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('anggota_keluarga', function (Blueprint $table) {
            $table->dropColumn('kelurahan_id');
            $table->dropColumn('rw_id');
            $table->dropColumn('rt_id');
        });
    }
}
