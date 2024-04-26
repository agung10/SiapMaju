<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeaderTrxKegiatanTable extends Migration
{
    public function up()
    {
        Schema::create('header_trx_kegiatan', function (Blueprint $table) {
            $table->bigIncrements('header_trx_kegiatan_id');
            $table->unsignedBigInteger('transaksi_id');
            $table->unsignedBigInteger('kat_kegiatan_id');
            $table->unsignedBigInteger('kegiatan_id');
            $table->unsignedBigInteger('anggota_keluarga_id');
            $table->string('no_pendaftaran',50);
            $table->date('tgl_pendaftaran');
            $table->string('no_bukti',50)->nullable();
            $table->date('tgl_approval')->nullable();
            $table->unsignedBigInteger('user_approval')->nullable();
            $table->unsignedBigInteger('user_updated_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('header_trx_kegiatan');
    }
}
