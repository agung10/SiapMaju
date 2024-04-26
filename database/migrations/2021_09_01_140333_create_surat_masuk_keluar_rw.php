<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratMasukKeluarRw extends Migration
{
    public function up()
    {
        Schema::create('surat_masuk_keluar_rw', function (Blueprint $table) {
            $table->id('surat_masuk_keluar_rw_id');
            $table->unsignedBigInteger('jenis_surat_rw_id');
            $table->unsignedBigInteger('sifat_surat_id');
            $table->unsignedBigInteger('rt_id');
            $table->unsignedBigInteger('rw_id');
            $table->string('no_surat',50);
            $table->string('lampiran',50)->nullable();
            $table->string('hal',100);
            $table->date('tgl_surat');
            $table->unsignedBigInteger('asal_surat');
            $table->string('nama_pengirim',100);
            $table->unsignedBigInteger('tujuan_surat');
            $table->string('nama_penerima',100);
            $table->text('isi_surat',500);
            $table->unsignedBigInteger('warga_id');
            $table->string('upload_file')->nullable();
            $table->unsignedBigInteger('user_created');
            $table->unsignedBigInteger('surat_balasan_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('surat_masuk_keluar_rw');
    }
}
