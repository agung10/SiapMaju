<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKontakTable extends Migration
{

    public function up()
    {
        Schema::create('kontak', function (Blueprint $table) {
            $table->bigIncrements('kontak_id');
            $table->string('alamat');
            $table->string('no_telp');
            $table->string('mobile');
            $table->string('nama_lokasi');
            $table->string('email');
            $table->date('web');
            $table->date('rekening');
            $table->date('date_created');
            $table->date('date_updated')->nullable();
            $table->integer('user_updated')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kontak');
    }
}
