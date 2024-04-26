<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailAlamatToProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profile', function (Blueprint $table) {
            $table->integer('province_id')->default(9)->nullable();
            $table->integer('city_id')->default(115)->nullable();
            $table->integer('subdistrict_id')->default(1579)->nullable();
            $table->unsignedBigInteger('kelurahan_id')->default(1)->nullable();
            $table->unsignedBigInteger('rw_id')->default(1)->nullable();
            $table->unsignedBigInteger('rt_id')->default(3)->nullable();
        });
    }

    public function down()
    {
        Schema::table('profile', function (Blueprint $table) {
            $table->dropColumn('province_id');
            $table->dropColumn('city_id');
            $table->dropColumn('subdistrict_id');
            $table->dropColumn('kelurahan_id');
            $table->dropColumn('rw_id');
            $table->dropColumn('rt_id');
        });
    }
}
