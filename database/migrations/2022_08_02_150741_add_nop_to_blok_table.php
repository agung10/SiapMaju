<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNopToBlokTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blok', function (Blueprint $table) {
            $table->string('nop', 100)->nullable();
        });
    }

    public function down()
    {
        Schema::table('blok', function (Blueprint $table) {
            $table->dropColumn('nop');
        });
    }
}
