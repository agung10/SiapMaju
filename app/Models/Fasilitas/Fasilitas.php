<?php

namespace App\Models\Fasilitas;

use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    protected $table      = 'fasilitas';
    protected $primaryKey = 'fasilitas_id';
    protected $guarded    = [];
    protected $appends    = ['pict1_src', 'pict2_src'];

    public function getPict1SrcAttribute()
    {
        return \helper::imageLoad('fasilitas', $this->pict1);
    }

    public function getPict2SrcAttribute()
    {
        return \helper::imageLoad('fasilitas', $this->pict2);
    }

    public function user()
    {
        return $this->hasOne(\App\User::class, 'id_user_updated', 'user_id');
    }
}
