<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeluargasTable extends Migration
{
   
    public function up()
    {
        Schema::create('keluarga', function (Blueprint $table) {
            $table->bigIncrements('keluarga_id');
            $table->integer('blok_id');
            $table->string('no_telp');
            $table->text('alamat');
            $table->string('email')->unique();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('rt_id');
            $table->unsignedBigInteger('rw_id');
            $table->integer('user_created');
            $table->integer('user_updated')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('keluarga');
    }
}
