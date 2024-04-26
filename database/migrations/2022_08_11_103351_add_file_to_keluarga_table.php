<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileToKeluargaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('keluarga', function (Blueprint $table) {
            $table->string('file', 255)->nullable();
        });
    }

    public function down()
    {
        Schema::table('keluarga', function (Blueprint $table) {
            $table->dropColumn('file');
        });
    }
}
