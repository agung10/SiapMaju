<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSiapDipesanToUmkmProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('umkm_produk', function (Blueprint $table) {
            $table->boolean('siap_dipesan')->default(false);
        });
    }

    public function down()
    {
        Schema::table('umkm_produk', function (Blueprint $table) {
            $table->dropColumn('siap_dipesan');
        });
    }
}
