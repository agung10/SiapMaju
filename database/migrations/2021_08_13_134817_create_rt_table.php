<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRtTable extends Migration
{

    public function up()
    {
        Schema::create('rt', function (Blueprint $table) {
            $table->id('rt_id');
            $table->string('rt',4);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rt');
    }
}
