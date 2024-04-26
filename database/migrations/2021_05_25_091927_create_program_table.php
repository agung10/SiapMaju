<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramTable extends Migration
{

    public function up()
    {
        Schema::create('program', function (Blueprint $table) {
            $table->bigIncrements('program_id');
            $table->text('nama_program');
            $table->text('program');
            $table->text('pic');
            $table->date('tanggal');
            $table->string('image');
            $table->date('date_created');
            $table->date('date_updated')->nullable();
            $table->integer('user_updated')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('program');
    }
}
