<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKelurahanIdToRwTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rw', function (Blueprint $table) {
            $table->unsignedBigInteger('kelurahan_id')->default(1)->nullable();
        });
    }

    public function down()
    {
        Schema::table('rw', function (Blueprint $table) {
            $table->dropColumn('kelurahan_id');
        });
    }
}
