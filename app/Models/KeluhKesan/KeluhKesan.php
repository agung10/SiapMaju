<?php

namespace App\Models\KeluhKesan;

use Illuminate\Database\Eloquent\Model;

class KeluhKesan extends Model
{
    protected $table      = 'keluh_kesan';
    protected $primaryKey = 'keluh_kesan_id';
    protected $guarded    = [];
    protected $appends    = ['file_image_src'];

    public function getFileImageSrcAttribute()
    {
        return \helper::imageLoad('keluh_kesan', $this->file_image);
    }

    public function user()
    {
        return $this->hasOne(\App\User::class, 'user_id', 'user_id');
    }

    public function balasan()
    {
        return $this->hasMany(BalasKeluhKesan::class, 'keluh_kesan_id', 'keluh_kesan_id')->orderBy('created_at', 'asc');
    }
}
