<?php

namespace App\Models\Master\Keluarga;

use App\Models\Master\Agama;
use Illuminate\Database\Eloquent\Model;

class AnggotaKeluarga extends Model
{
    protected $table = 'anggota_keluarga';

    protected $primaryKey = 'anggota_keluarga_id';

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];
    
    public function user()
    {
        return $this->belongsTo(\App\User::class, 'anggota_keluarga_id', 'anggota_keluarga_id');
    }

    public function allAnggotaKeluargaId()
    {
        return self::where('keluarga_id', $this->keluarga_id)
                    ->where('anggota_keluarga.is_active', true)
                   ->pluck('anggota_keluarga_id');
    }

    function hubKeluarga() {
        return $this->belongsTo(HubKeluarga::class, 'hub_keluarga_id', 'hub_keluarga_id');
    }

    function keluarga() {
        return $this->belongsTo(Keluarga::class, 'keluarga_id', 'keluarga_id');
    }

    function agama() {
        return $this->belongsTo(Agama::class, 'agama_id', 'agama_id');
    }
}
