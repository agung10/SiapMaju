<?php

namespace App\Models\UMKM;

use App\Models\Master\Keluarga\AnggotaKeluarga;
use Illuminate\Database\Eloquent\Model;

class Umkm extends Model
{
    protected $table      = 'umkm';
    protected $primaryKey = 'umkm_id';
    protected $guarded    = [];
    protected $appends    = ['image_src'];

    public function getImageSrcAttribute()
    {
        return \helper::imageLoad('umkm', $this->image);
    }

    public function anggota_keluarga(){
        return $this->belongsTo(AnggotaKeluarga::class, 'anggota_keluarga_id');
    }

    public function umkmMedsos()
    {
        return $this->hasMany(UmkmMedsos::class, 'umkm_id');
    }
}
