<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKatPengurusesTable extends Migration
{
    public function up()
    {
        Schema::create('kat_pengurus', function (Blueprint $table) {
            $table->bigIncrements('kat_pengurus_id');
            $table->string('nama_kategori');
            $table->integer('user_updated')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kat_pengurus');
    }
}
