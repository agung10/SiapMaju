<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJenisSuratTable extends Migration
{

    public function up()
    {
        Schema::create('jenis_surat', function (Blueprint $table) {
            $table->id('jenis_surat_id');
            $table->string('jenis_permohonan',100);
            $table->integer('kd_surat')->unique();
            $table->text('keterangan',100);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jenis_surat');
    }
}
