<?php

namespace App\Models\KeluhKesan;

use Illuminate\Database\Eloquent\Model;

class BalasKeluhKesan extends Model
{
    protected $table      = 'balas_keluh_kesan';
    protected $primaryKey = 'balas_keluh_kesan_id';
    protected $guarded    = [];
    protected $appends    = ['file_image_src'];

    public function getFileImageSrcAttribute()
    {
        return \helper::imageLoad('balas_keluh_kesan', $this->file_image);
    }

    public function user()
    {
        return $this->hasOne(\App\User::class, 'user_id', 'user_id');
    }
}
