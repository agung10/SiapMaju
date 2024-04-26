<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusMutasiWargaTable extends Migration
{
    public function up()
    {
        Schema::create('status_mutasi_warga', function (Blueprint $table) {
            $table->bigIncrements('status_mutasi_warga_id');
            $table->string('nama_status', 50);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('status_mutasi_warga');
    }
}