<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendasTable extends Migration
{
    public function up()
    {
        Schema::create('agenda', function (Blueprint $table) {
            $table->bigIncrements('agenda_id');
            $table->integer('program_id')->nullable();
            $table->string('nama_agenda');
            $table->string('lokasi');
            $table->date('tanggal');
            $table->time('jam');
            $table->text('agenda');
            $table->string('image')->nullable();
            $table->integer('user_updated')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('agenda');
    }
}
