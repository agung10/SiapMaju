<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRwTable extends Migration
{
    public function up()
    {
        Schema::create('rw', function (Blueprint $table) {
            $table->id('rw_id');
            $table->string('rw',5);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rw');
    }
}
