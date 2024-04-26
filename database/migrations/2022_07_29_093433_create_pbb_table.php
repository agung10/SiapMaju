<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePbbTable extends Migration
{
   
    public function up()
    {
        Schema::create('pbb', function (Blueprint $table) {
            $table->bigIncrements('pbb_id');
            $table->integer('blok_id');
            $table->integer('anggota_keluarga_id');
            $table->string('nop', 100);
            $table->date('tgl_terima');
            $table->string('foto_terima', 255)->nullable();
            $table->decimal('nilai', 20, 2)->default(0.00);
            $table->date('tgl_bayar')->nullable();
            $table->string('bukti_bayar', 255)->nullable();
            $table->integer('user_updated')->nullable();
            $table->string('tahun_pajak', 5)->nullable();
            $table->timestamps();

            $table->foreign('blok_id')
                  ->references('blok_id')
                  ->on('blok');

            $table->foreign('anggota_keluarga_id')
                  ->references('anggota_keluarga_id')
                  ->on('anggota_keluarga');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pbb');
    }
}
