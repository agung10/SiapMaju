<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsDkmToAnggotaKeluargaTable extends Migration
{

    public function up()
    {
        Schema::table('anggota_keluarga', function (Blueprint $table) {
            $table->string('is_dkm')->nullable();
        });
    }

    public function down()
    {
        Schema::table('anggota_keluarga', function (Blueprint $table) {
            $table->dropColumn('is_dkm')->nullable();
        });
    }
}
