<?php

namespace App\Models\PBB;

use Illuminate\Database\Eloquent\Model;
use App\Models\Master\Blok;
use App\Models\Master\Keluarga\AnggotaKeluarga;

class Pbb extends Model
{
    protected $table      = 'pbb';
    protected $primaryKey = 'pbb_id';
    protected $guarded    = [];
    protected $appends    = ['encryptedId', 'foto_terima_src', 'bukti_bayar_src'];


    public function getFotoTerimaSrcAttribute()
    {
        return \helper::imageLoad('pbb', $this->foto_terima);
    }

    public function getBuktiBayarSrcAttribute()
    {
        return \helper::imageLoad('pbb', $this->bukti_bayar);
    }

    public function getEncryptedIdAttribute()
    {
        return \Crypt::encryptString($this->attributes['pbb_id']);
    }

    public function findByEncrypted($encryptedId)
    {
        return $this->find( \Crypt::decryptString($encryptedId) );
    }

    public function blok()
    {
        return $this->belongsTo(Blok::class, 'blok_id');
    }

    public function anggotaKeluarga()
    {
        return $this->belongsTo(AnggotaKeluarga::class, 'anggota_keluarga_id');
    }
}
