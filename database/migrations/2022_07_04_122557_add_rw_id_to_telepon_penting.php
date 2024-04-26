<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRwIdToTeleponPenting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('telepon_penting', function (Blueprint $table) {
            $table->unsignedBigInteger('rw_id')->default(1)->nullable();
        });
    }

    public function down()
    {
        Schema::table('telepon_penting', function (Blueprint $table) {
            $table->dropColumn('rw_id');
        });
    }
}
