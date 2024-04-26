<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapRWTable extends Migration
{
    public function up()
    {
        Schema::create('cap_rw', function (Blueprint $table) {
            $table->id('cap_rw_id');
            $table->string('cap_rw');
            $table->unsignedBigInteger('rw_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cap_rw');
    }
}
