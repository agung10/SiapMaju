<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTandaTanganRtTable extends Migration
{
    public function up()
    {
        Schema::create('tanda_tangan_rt', function (Blueprint $table) {
            $table->id('tanda_tangan_rt_id');
            $table->string('tanda_tangan_rt');
            $table->unsignedBigInteger('rt_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tanda_tangan_rt');
    }
}
