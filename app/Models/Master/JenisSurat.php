<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    protected $table      = 'jenis_surat';
    protected $primaryKey = 'jenis_surat_id';
    protected $guarded    = [];
    protected $appends    = ['icon_src'];

    public function getIconSrcAttribute()
    {
        return asset("assets/icon/jenisSurat/$this->kd_surat.png");
    }
}
