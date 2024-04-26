<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKatSumberBiayaIdAndNilaiToAgenda extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agenda', function (Blueprint $table) {
            $table->unsignedBigInteger('kat_sumber_biaya_id')->nullable();
            $table->bigInteger('nilai')->nullable();
        });
    }

    public function down()
    {
        Schema::table('agenda', function (Blueprint $table) {
            $table->dropColumn('kat_sumber_biaya_id');
            $table->dropColumn('nilai');
        });
    }
}
