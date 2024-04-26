<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHubKeluargasTable extends Migration
{
  
    public function up()
    {
        Schema::create('hub_keluarga', function (Blueprint $table) {
            $table->bigIncrements('hub_keluarga_id');
            $table->string('hubungan_kel', 50);
            $table->integer('user_updated')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hub_keluarga');
    }
}
