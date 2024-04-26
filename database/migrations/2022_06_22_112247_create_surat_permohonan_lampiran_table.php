<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratPermohonanLampiranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_permohonan_lampiran', function (Blueprint $table) {
            $table->id('surat_permohonan_lampiran_id');
            $table->unsignedBigInteger('surat_permohonan_id');
            $table->unsignedBigInteger('lampiran_id');
            $table->string('upload_lampiran', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surat_permohonan_lampiran');
    }
}
