<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJenisTransaksisTable extends Migration
{
   
    public function up()
    {
        Schema::create('jenis_transaksi', function (Blueprint $table) {
            $table->bigIncrements('jenis_transaksi_id');
            $table->integer('kegiatan_id');
            $table->text('nama_jenis_transaksi');
            $table->string('satuan');
            $table->integer('user_updated')->nullable();
            $table->timestamps();
        });
    }

 
    public function down()
    {
        Schema::dropIfExists('jenis_transaksi');
    }
}
