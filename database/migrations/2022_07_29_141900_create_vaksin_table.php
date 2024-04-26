<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaksinTable extends Migration
{
    public function up() {
        Schema::create('vaksin', function (Blueprint $table) {
            $table->bigIncrements('id_vaksin');
            $table->string('nama_vaksin', 150);
            $table->text('keterangan');
            $table->integer('user_created');
            $table->boolean('wajib')->default(false);
            $table->boolean('status_aktif')->default(true);
            $table->boolean('is_delete')->default(false);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('vaksin');
    }
}