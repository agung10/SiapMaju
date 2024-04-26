<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteAgendaIdToLaporanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laporan', function (Blueprint $table) {
            $table->dropColumn('agenda_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('laporan', function (Blueprint $table) {
            $table->integer('agenda_id')->nullable();
        });
    }
}
