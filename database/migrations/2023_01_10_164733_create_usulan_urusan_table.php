<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsulanUrusanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usulan_urusan', function (Blueprint $table) {
            $table->id('usulan_urusan_id');
            $table->unsignedBigInteger('menu_urusan_id');
            $table->unsignedBigInteger('bidang_urusan_id');
            $table->unsignedBigInteger('kegiatan_urusan_id');
            $table->unsignedBigInteger('rt_id');
            $table->unsignedBigInteger('rw_id');
            $table->unsignedBigInteger('user_id');
            $table->text('alamat');
            $table->string('jumlah');
            $table->year('tahun');
            $table->integer('status_usulan');
            $table->text('keterangan');
            $table->string('gambar_1', 255);
            $table->string('gambar_2', 255)->nullable();
            $table->string('gambar_3', 255)->nullable();
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->integer('user_create');
            $table->integer('user_update')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usulan_urusan');
    }
}
