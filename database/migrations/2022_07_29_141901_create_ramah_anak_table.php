<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRamahAnakTable extends Migration
{
    public function up() {
        Schema::create('ramah_anak', function (Blueprint $table) {
            $table->bigIncrements('id_ramah_anak');
            $table->unsignedBigInteger('anggota_keluarga_id');
            $table->unsignedBigInteger('id_vaksin');
            $table->date('tgl_input');
            $table->decimal('berat', 20, 2);
            $table->decimal('tinggi', 20, 2);
            $table->decimal('lingkar_kepala', 20, 2);
            $table->decimal('nilai_stunting', 20, 2);
            $table->text('keluhan');
            $table->text('ket_vaksinasi')->nullable();
            $table->integer('user_created');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('ramah_anak');
    }
}