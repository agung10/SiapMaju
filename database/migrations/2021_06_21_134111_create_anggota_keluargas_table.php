<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnggotaKeluargasTable extends Migration
{
    public function up()
    {
        Schema::create('anggota_keluarga', function (Blueprint $table) {
            $table->bigIncrements('anggota_keluarga_id');
            $table->integer('keluarga_id');
            $table->integer('hub_keluarga_id');
            $table->string('nama');
            $table->text('alamat')->nullable();
            $table->string('mobile', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('password')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_rt')->default(false);
            $table->boolean('is_rw')->default(false);
            $table->integer('user_updated')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('anggota_keluarga');
    }
}
