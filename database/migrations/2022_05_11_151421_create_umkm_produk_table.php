<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUmkmProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('umkm_produk', function (Blueprint $table) {
            $table->id('umkm_produk_id');
            $table->unsignedBigInteger('umkm_id');
            $table->unsignedBigInteger('umkm_kategori_id');
            $table->string('image', 255)->nullable();
            $table->string('nama', 150);
            $table->text('deskripsi')->nullable();
            $table->string('url')->nullable();
            $table->decimal('harga', 20, 2)->default(0.00);
            $table->integer('stok')->default(0);
            $table->integer('berat')->default(1000);
            $table->boolean('aktif');
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
        Schema::dropIfExists('umkm_produk');
    }
}
