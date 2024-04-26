<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCovid19Table extends Migration
{
    public function up()
    {
        Schema::create('covid19', function (Blueprint $table) {
            $table->id('covid19_id');
            $table->date('tgl_input');
            $table->unsignedBigInteger('rt_id');
            $table->integer('jml_positif')->nullable();
            $table->integer('jml_sembuh')->nullable();
            $table->integer('jml_meninggal')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('covid19');
    }
}
