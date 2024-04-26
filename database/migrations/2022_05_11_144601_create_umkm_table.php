<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUmkmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('umkm', function (Blueprint $table) {
            $table->id('umkm_id');
            $table->unsignedBigInteger('anggota_keluarga_id');
            $table->string('image', 255)->nullable();
            $table->string('nama', 150);
            $table->text('deskripsi')->nullable();
            $table->boolean('aktif');
            $table->boolean('disetujui')->nullable();
            $table->boolean('promosi')->default(false);
            $table->boolean('has_website')->nullable();
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
        Schema::dropIfExists('umkm');
    }
}
