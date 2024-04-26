<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPetugasAndTglApproveLurahToSuratPermohonanTable extends Migration
{
    public function up()
    {
        Schema::table('surat_permohonan', function (Blueprint $table) {
            $table->date('tgl_approve_kelurahan')->nullable();
            $table->unsignedBigInteger('petugas_kelurahan_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('surat_permohonan', function (Blueprint $table) {
            $table->dropColumn('tgl_approve_kelurahan');
            $table->dropColumn('petugas_kelurahan_id');
        });
    }
}
