<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBloksTable extends Migration
{
   
    public function up()
    {
        Schema::create('blok', function (Blueprint $table) {
            $table->bigIncrements('blok_id');
            $table->string('nama_blok', 20);
            $table->float('long')->nullable();
            $table->float('lang')->nullable();
            $table->integer('user_updated')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blok');
    }
}
