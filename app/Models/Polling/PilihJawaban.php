<?php

namespace App\Models\Polling;
use Illuminate\Database\Eloquent\Model;

class PilihJawaban extends Model
{
    protected $table = 'pilih_jawaban';
    protected $primaryKey = 'id_pilih_jawaban';
    protected $guarded = [];

    public function pollingResult() {
        return $this->hasMany(HasilPolling::class, 'id_pilih_jawaban', 'id_pilih_jawaban');
    }

    public function pollingDKM() {
        return $this->hasMany(HasilPolling::class, 'id_pilih_jawaban', 'id_pilih_jawaban')->join('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'hasil_polling.anggota_keluarga_id')->where('agama_id', 1);
    }
}