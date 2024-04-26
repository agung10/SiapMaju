<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailTrxKegiatanTable extends Migration
{

    public function up()
    {
        Schema::create('detail_trx_kegiatan', function (Blueprint $table) {
            $table->bigIncrements('detail_trx_kegiatan_id');
            $table->unsignedBigInteger('header_trx_kegiatan_id');
            $table->unsignedBigInteger('jenis_transaksi_id');
            $table->string('nama_detail_trx',50);
            $table->float('nilai');
            $table->float('jumlah');
            $table->float('total');
            $table->date('tgl_approval')->nullable();
            $table->unsignedBigInteger('user_approval')->nullable();
            $table->unsignedBigInteger('user_updated_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_trx_kegiatan');
    }
}
