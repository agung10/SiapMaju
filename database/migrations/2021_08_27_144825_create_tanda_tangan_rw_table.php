<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTandaTanganRWTable extends Migration
{
    
    public function up()
    {
        Schema::create('tanda_tangan_rw', function (Blueprint $table) {
            $table->id('tanda_tangan_rw_id');
            $table->string('tanda_tangan_rw');
            $table->unsignedBigInteger('rw_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tanda_tangan_rw');
    }
}
