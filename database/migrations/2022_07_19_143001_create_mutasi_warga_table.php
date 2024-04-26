<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMutasiWargaTable extends Migration
{
    public function up()
    {
        Schema::create('mutasi_warga', function (Blueprint $table) {
            $table->bigIncrements('mutasi_warga_id');
            $table->unsignedBigInteger('anggota_keluarga_id');
            $table->unsignedBigInteger('status_mutasi_warga_id')->default(null)->nullable();
            $table->date('tanggal_mutasi');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('mutasi_warga');
    }
}