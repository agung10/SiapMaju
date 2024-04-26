<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalasKeluhKesanTable extends Migration
{

    public function up()
    {
        Schema::create('balas_keluh_kesan', function (Blueprint $table) {
            $table->id('balas_keluh_kesan_id');
            $table->text('balas_keluh_kesan');
            $table->unsignedBigInteger('keluh_kesan_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('balas_keluh_kesan');
    }
}
