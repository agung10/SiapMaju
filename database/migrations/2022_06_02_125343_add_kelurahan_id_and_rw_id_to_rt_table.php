<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKelurahanIdAndRwIdToRtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rt', function (Blueprint $table) {
            $table->unsignedBigInteger('kelurahan_id')->default(1)->nullable();
            $table->unsignedBigInteger('rw_id')->default(1)->nullable();
        });
    }

    public function down()
    {
        Schema::table('rt', function (Blueprint $table) {
            $table->dropColumn('kelurahan_id');
            $table->dropColumn('rw_id');
        });
    }
}
