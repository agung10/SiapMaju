<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFasilitasTable extends Migration
{

    public function up()
    {
        Schema::create('fasilitas', function (Blueprint $table) {
            $table->id('fasilitas_id');
            $table->string('nama_fasilitas', 500);
            $table->string('keterangan', 500)->nullable();
            $table->string('lokasi', 500)->nullable();
            $table->string('pict1', 200)->nullable();
            $table->string('pict2', 200)->nullable();
            $table->integer('id_user_updated')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fasilitas');
    }
}
