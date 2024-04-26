<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRtAndRwIdToKegiatanTable extends Migration
{
    public function up()
    {
        Schema::table('kegiatan', function (Blueprint $table) {
            $table->unsignedBigInteger('rt_id')->default(3)->nullable();
            $table->unsignedBigInteger('rw_id')->default(1)->nullable();
        });
    }

    public function down()
    {
        Schema::table('kegiatan', function (Blueprint $table) {
            $table->dropColumn('rt_id');
            $table->dropColumn('rw_id');
        });
    }
}
