<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKatKajianTable extends Migration
{

    public function up()
    {
        Schema::create('kat_kajian', function (Blueprint $table) {
            $table->bigIncrements('kat_kajian_id');
            $table->text('kategori');
            $table->date('date_created');
            $table->date('date_updated')->nullable();
            $table->integer('user_updated')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kat_kajian');
    }
}
