<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgamasTable extends Migration
{
    
    public function up()
    {
        Schema::create('agama', function (Blueprint $table) {
            $table->id('agama_id');
            $table->string('nama_agama', 30);
            $table->string('user_created');
            $table->string('user_updated')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('agama');
    }
}
