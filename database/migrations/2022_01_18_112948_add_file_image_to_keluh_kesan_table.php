<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileImageToKeluhKesanTable extends Migration
{
    public function up()
    {
        Schema::table('keluh_kesan', function (Blueprint $table) {
            $table->string('file_image')->nullable();
        });
    }

    public function down()
    {
        Schema::table('keluh_kesan', function (Blueprint $table) {
            $table->dropColumn('file_image');
        });
    }
}
