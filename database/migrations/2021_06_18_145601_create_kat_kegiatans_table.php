<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKatKegiatansTable extends Migration
{
   
    public function up()
    {
        Schema::create('kat_kegiatan', function (Blueprint $table) {
            $table->bigIncrements('kat_kegiatan_id');
            $table->string('nama_kat_kegiatan', 20);
            $table->string('kode_kat', 20);
            $table->integer('user_updated')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kat_kegiatan');
    }
}
