<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusPernikahansTable extends Migration
{
   
    public function up()
    {
        Schema::create('status_pernikahan', function (Blueprint $table) {
            $table->id('status_pernikahan_id');
            $table->string('nama_status_pernikahan');
            $table->integer('user_created');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('status_pernikahan');
    }
}
