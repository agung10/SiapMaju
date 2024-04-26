<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHasilPollingTable extends Migration
{
    public function up()
    {
        Schema::create('hasil_polling', function (Blueprint $table) {
            $table->bigIncrements('id_hasil_polling');
            $table->unsignedBigInteger('id_polling');
            $table->unsignedBigInteger('id_pilih_jawaban');
            $table->unsignedBigInteger('anggota_keluarga_id');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('hasil_polling');
    }
}