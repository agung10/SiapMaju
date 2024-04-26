<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlamatKodePosToKelurahan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kelurahan', function (Blueprint $table) {
            $table->text('alamat')->default('Jl. Raya Jakarta-Bogor No.KM.36')->nullable();
            $table->string('kode_pos', 10)->default('16415')->nullable();
        });
    }

    public function down()
    {
        Schema::table('kelurahan', function (Blueprint $table) {
            $table->dropColumn('alamat');
            $table->dropColumn('kode_pos');
        });
    }
}
