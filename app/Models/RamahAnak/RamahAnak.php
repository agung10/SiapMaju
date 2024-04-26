<?php

namespace App\Models\RamahAnak;
use Illuminate\Database\Eloquent\Model;

class RamahAnak extends Model
{
    protected $table = 'ramah_anak';
    protected $primaryKey = 'id_ramah_anak';
    protected $guarded = [];

    public function vaccine() {
        return $this->belongsTo('App\Models\RamahAnak\Vaksin', 'id_vaksin', 'id_vaksin');
    }

    public function familyMember() {
        return $this->belongsTo('App\Models\Master\Keluarga\AnggotaKeluarga', 'anggota_keluarga_id', 'anggota_keluarga_id');
    }

    public function vaksin() 
    {
        return $this->belongsTo(Vaksin::class, 'id_vaksin', 'id_vaksin');
    }
}