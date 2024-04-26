<?php

namespace App\Models\Master\Keluarga;

use Illuminate\Database\Eloquent\Model;

class Keluarga extends Model
{
    protected $table = 'keluarga';

    protected $primaryKey = 'keluarga_id';

    protected $guarded = [];

    public function anggotaKeluarga() 
    {
        return $this->hasMany(AnggotaKeluarga::class, 'keluarga_id');
    }
}
