<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->bigIncrements('laporan_id');
            $table->integer('kat_laporan_id');
            $table->integer('agenda_id')->nullable();
            $table->text('laporan');
            $table->text('detail_laporan');
            $table->string('upload_materi')->nullable();
            $table->string('image')->nullable();
            $table->integer('user_updated')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laporan');
    }
}
