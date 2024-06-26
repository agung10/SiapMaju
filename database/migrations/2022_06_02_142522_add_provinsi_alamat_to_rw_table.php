<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProvinsiAlamatToRwTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rw', function (Blueprint $table) {
            $table->integer('province_id')->default(9)->nullable();
            $table->integer('city_id')->default(115)->nullable();
            $table->integer('subdistrict_id')->default(1579)->nullable();
        });
    }

    public function down()
    {
        Schema::table('rw', function (Blueprint $table) {
            $table->dropColumn('province_id');
            $table->dropColumn('city_id');
            $table->dropColumn('subdistrict_id');
        });
    }
}
