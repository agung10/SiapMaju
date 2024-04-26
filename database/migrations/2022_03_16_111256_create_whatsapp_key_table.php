<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhatsappKeyTable extends Migration
{

    public function up()
    {
        Schema::create('whatsapp_key', function (Blueprint $table) {
            $table->id('whatsapp_key_id');
            $table->string('whatsapp_key');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('whatsapp_key');
    }
}
