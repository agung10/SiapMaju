<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSumberSuratTable extends Migration
{
    public function up()
    {
        Schema::create('sumber_surat', function (Blueprint $table) {
            $table->id('sumber_surat_id');
            $table->string('asal_surat');
            $table->unsignedBigInteger('user_created');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sumber_surat');
    }
}
