<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengurusesTable extends Migration
{
    public function up()
    {
        Schema::create('pengurus', function (Blueprint $table) {
            $table->bigIncrements('pengurus_id');
            $table->integer('kat_pengurus_id');
            $table->string('nama');
            $table->string('jabatan');
            $table->string('alamat');
            $table->integer('no_urut')->nullable();
            $table->string('photo')->nullable();
            $table->integer('user_updated')->nullable();
            $table->timestamps();

            
            // $table->foreign('kat_pengurus_id')->references('kat_pengurus_id')->on('kat_pengurus')->onDelete('cascade');
        });

    }

    public function down()
    {
        Schema::dropIfExists('pengurus');
    }
}
