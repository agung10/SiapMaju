<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePilihJawabanTable extends Migration
{
    public function up()
    {
        Schema::create('pilih_jawaban', function (Blueprint $table) {
            $table->bigIncrements('id_pilih_jawaban');
            $table->unsignedBigInteger('id_polling');
            $table->text('isi_pilih_jawaban');
            $table->integer('user_create');
            $table->integer('user_update')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('pilih_jawaban');
    }
}