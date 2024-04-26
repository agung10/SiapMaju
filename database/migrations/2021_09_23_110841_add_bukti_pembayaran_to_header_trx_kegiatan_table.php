<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBuktiPembayaranToHeaderTrxKegiatanTable extends Migration
{

    public function up()
    {
        Schema::table('header_trx_kegiatan', function (Blueprint $table) {
            $table->string('bukti_pembayaran')->nullable();
        });
    }

    public function down()
    {
        Schema::table('header_trx_kegiatan', function (Blueprint $table) {
            $table->dropColumn('bukti_pembayaran');
        });
    }
}
