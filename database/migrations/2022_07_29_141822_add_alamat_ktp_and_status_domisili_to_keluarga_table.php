<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlamatKtpAndStatusDomisiliToKeluargaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('keluarga', function (Blueprint $table) {
            $table->integer('status_domisili')->default(1)->nullable();
            $table->text('alamat_ktp')->nullable();
        });
    }

    public function down()
    {
        Schema::table('keluarga', function (Blueprint $table) {
            $table->dropColumn('status_domisili');
            $table->dropColumn('alamat_ktp');
        });
    }
}
