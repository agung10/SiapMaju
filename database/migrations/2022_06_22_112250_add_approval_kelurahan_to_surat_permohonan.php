<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovalKelurahanToSuratPermohonan extends Migration
{
    public function up() {
        Schema::table('surat_permohonan', function (Blueprint $table) {
            $table->integer('status_upload')->default(0)->nullable();
            $table->text('catatan_kelurahan')->nullable();
            $table->text('no_surat_kel')->nullable();
            $table->text('isi_surat')->nullable();
            $table->timestamp('tgl_approve_kasi')->nullable();
            $table->integer('kasi_id')->nullable();
            $table->timestamp('tgl_approve_sekel')->nullable();
            $table->integer('sekel_id')->nullable();
            $table->integer('approve_lurah')->nullable();
            $table->timestamp('tgl_approve_lurah')->nullable();
            $table->text('ttd_lurah')->nullable();
            $table->text('upload_surat_kelurahan')->nullable();
        });
    }

    public function down() {
        Schema::table('surat_permohonan', function (Blueprint $table) {
            $table->dropColumn('status_upload');
            $table->dropColumn('catatan_kelurahan');
            $table->dropColumn('no_surat_kel');
            $table->dropColumn('isi_surat');
            $table->dropColumn('tgl_approve_kasi');
            $table->dropColumn('kasi_id');
            $table->dropColumn('tgl_approve_sekel');
            $table->dropColumn('sekel_id');
            $table->dropColumn('approve_lurah');
            $table->dropColumn('tgl_approve_lurah');
            $table->dropColumn('ttd_lurah');
            $table->dropColumn('upload_surat_kelurahan');
        });
    }
}