<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKajianTable extends Migration
{

    public function up()
    {
        Schema::create('kajian', function (Blueprint $table) {
            $table->bigIncrements('kajian_id');
            $table->unsignedBigInteger('kat_kajian_id');
            $table->string('judul');
            $table->text('kajian');
            $table->string('author');
            $table->string('upload_materi_1')->nullable();
            $table->string('upload_materi_2')->nullable();
            $table->string('upload_materi_3')->nullable();
            $table->string('upload_materi_4')->nullable();
            $table->string('upload_materi_5')->nullable();
            $table->string('image');
            $table->date('date_created');
            $table->date('date_updated')->nullable();
            $table->integer('user_updated')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kajian');
    }
}
