<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGaleriKontenTable extends Migration
{
    public function up()
    {
        Schema::create('galeri_konten', function (Blueprint $table) {
            $table->bigIncrements('galeri_konten_id');
            $table->integer('galeri_id')->nullable();
            $table->integer('agenda_id')->nullable();
            $table->text('keterangan');
            $table->string('kategori_file');
            $table->string('file')->nullable();
            $table->integer('user_updated')->nullable();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('galeri_konten');
    }
}
