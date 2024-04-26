<?php

namespace App\Models\Polling;
use Illuminate\Database\Eloquent\Model;

class HasilPolling extends Model
{
    protected $table = 'hasil_polling';
    protected $primaryKey = 'id_hasil_polling';
    protected $guarded = [];

    public function citizen() {
        return $this->belongsTo('App\Models\Master\Keluarga\AnggotaKeluarga', 'anggota_keluarga_id', 'anggota_keluarga_id');
    }

    public function jawaban() {
        return $this->belongsTo(PilihJawaban::class, 'id_pilih_jawaban', 'id_pilih_jawaban');
    }
}