<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapRtTable extends Migration
{
    public function up()
    {
        Schema::create('cap_rt', function (Blueprint $table) {
            $table->id('cap_rt_id');
            $table->string('cap_rt');
            $table->unsignedBigInteger('rt_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cap_rt');
    }
}
