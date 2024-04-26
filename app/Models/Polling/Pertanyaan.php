<?php

namespace App\Models\Polling;
use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    protected $table = 'pertanyaan';
    protected $primaryKey = 'id_polling';
    protected $guarded = [];

    public function answer() {
        return $this->hasMany(PilihJawaban::class, 'id_polling', 'id_polling');
    }

    public function result() {
        return $this->hasMany(HasilPolling::class, 'id_polling', 'id_polling');
    }

    public function dijawab() {
        return $this->result()->where('anggota_keluarga_id','=', auth('api')->user()->anggota_keluarga_id);
    }
}