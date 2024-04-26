<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNilaiToJenisTransaksiTable extends Migration
{
    public function up()
    {
        Schema::table('jenis_transaksi', function (Blueprint $table) {
            $table->string('nilai')->default('0');
        });
    }

    public function down()
    {
        Schema::table('jenis_transaksi', function (Blueprint $table) {
            $table->dropColumn('nilai');
        });
    }
}
