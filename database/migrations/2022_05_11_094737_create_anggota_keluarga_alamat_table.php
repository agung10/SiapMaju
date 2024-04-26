<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnggotaKeluargaAlamatTable extends Migration
{
    public function up()
    {
        Schema::create('anggota_keluarga_alamat', function (Blueprint $table) {
            $table->increments('anggota_keluarga_alamat_id');
            $table->integer('anggota_keluarga_id');
            $table->integer('province_id');
            $table->integer('city_id');
            $table->integer('subdistrict_id');
            $table->text('alamat');
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

            $table->foreign('anggota_keluarga_id')
                  ->references('anggota_keluarga_id')
                  ->on('anggota_keluarga')
                  ->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('anggota_keluarga_alamat');
    }
}
