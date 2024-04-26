<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeaderTable extends Migration
{
    public function up()
    {
        Schema::create('header', function (Blueprint $table) {
            $table->bigIncrements('header_id');
            $table->string('image');
            $table->string('judul');
            $table->string('keterangan');
            $table->date('date_created');
            $table->string('date_updated')->nullable();
            $table->unsignedBigInteger('user_updated')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('header');
    }
}
