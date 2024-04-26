<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratPermohonanTable extends Migration
{

    public function up()
    {
        Schema::create('surat_permohonan', function (Blueprint $table) {
            $table->id('surat_permohonan_id');
            $table->unsignedBigInteger('jenis_surat_id');
            $table->unsignedBigInteger('anggota_keluarga_id');
            $table->string('nama_lengkap',100);
            $table->unsignedBigInteger('rt_id');
            $table->unsignedBigInteger('rw_id');
            $table->string('no_surat',50)->nullable();
            $table->integer('lampiran')->nullable();
            $table->string('hal',100);
            $table->string('tempat_lahir',50);
            $table->date('tgl_lahir');
            $table->string('bangsa',50);
            $table->unsignedBigInteger('agama_id');
            $table->unsignedBigInteger('status_pernikahan_id');
            $table->string('pekerjaan',50);
            $table->string('no_kk',30);
            $table->string('no_ktp',30);
            $table->string('alamat',100);
            $table->date('tgl_permohonan');
            $table->boolean('approve_draft')->nullable();
            $table->unsignedBigInteger('petugas_rt_id')->nullable();
            $table->date('tgl_approve_rt')->nullable();
            $table->unsignedBigInteger('petugas_rw_id')->nullable();
            $table->date('tgl_approve_rw')->nullable();
            $table->string('lampiran1')->nullable();
            $table->string('lampiran2')->nullable();
            $table->string('lampiran3')->nullable();
            $table->string('validasi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('surat_permohonan');
    }
}
