<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePertanyaanTable extends Migration
{
    public function up()
    {
        Schema::create('pertanyaan', function (Blueprint $table) {
            $table->bigIncrements('id_polling');
            $table->integer('province_id');
            $table->integer('city_id');
            $table->integer('subdistrict_id');
            $table->unsignedBigInteger('kelurahan_id');
            $table->unsignedBigInteger('rw_id');
            $table->unsignedBigInteger('rt_id');
            $table->date('close_date');
            $table->text('isi_pertanyaan');
            $table->integer('user_create');
            $table->integer('user_update')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('pertanyaan');
    }
}