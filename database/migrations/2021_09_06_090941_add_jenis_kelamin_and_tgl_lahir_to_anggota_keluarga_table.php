<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJenisKelaminAndTglLahirToAnggotaKeluargaTable extends Migration
{

    public function up()
    {
        Schema::table('anggota_keluarga', function (Blueprint $table) {
            $table->string('jenis_kelamin')->nullable();
            $table->date('tgl_lahir')->nullable();
        });
    }

    public function down()
    {
        Schema::table('anggota_keluarga', function (Blueprint $table) {
            $table->dropColumn('jenis_kelamin');
            $table->dropColumn('tgl_lahir');
        });
    }
}
