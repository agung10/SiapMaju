<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemesananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id('pemesanan_id');
            $table->integer('umkm_id');
            $table->integer('umkm_produk_id');
            $table->integer('anggota_keluarga_id');
            $table->string('nama_produk', 255);
            $table->decimal('harga_produk', 20, 2)->default(0.00);
            $table->integer('jumlah');
            $table->decimal('total', 20, 2)->default(0.00);
            $table->boolean('approved')->nullable();
            $table->boolean('paid')->default(false);
            $table->string('bukti_pembayaran', 255)->nullable();
            $table->boolean('delivered')->default(false);
            $table->timestamps();

            $table->foreign('umkm_produk_id')
                  ->references('umkm_produk_id')
                  ->on('umkm_produk');

            $table->foreign('anggota_keluarga_id')
                  ->references('anggota_keluarga_id')
                  ->on('anggota_keluarga');

            $table->foreign('umkm_id')
                  ->references('umkm_id')
                  ->on('umkm');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemesanan');
    }
}
