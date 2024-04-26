<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJenisSuratRwTable extends Migration
{
    public function up()
    {
        Schema::create('jenis_surat_rw', function (Blueprint $table) {
            $table->id('jenis_surat_rw_id');
            $table->string('jenis_surat');
            $table->unsignedBigInteger('user_created');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jenis_surat_rw');
    }
}
