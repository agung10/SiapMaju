<?php

namespace App\Models\Master\Keluarga;
use Illuminate\Database\Eloquent\Model;

class MutasiWarga extends Model
{
    protected $table = 'mutasi_warga';
    protected $primaryKey = 'mutasi_warga_id';
    protected $guarded = [];

    public function familyMember() {
        return $this->belongsTo('App\Models\Master\Keluarga\AnggotaKeluarga', 'anggota_keluarga_id', 'anggota_keluarga_id');
    }

    public function movingStatus() {
        return $this->belongsTo('App\Models\Master\Keluarga\StatusMutasiWarga', 'status_mutasi_warga_id', 'status_mutasi_warga_id');
    }
}