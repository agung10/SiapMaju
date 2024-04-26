<?php

namespace App\Models\UMKM;

use Illuminate\Database\Eloquent\Model;

class UmkmImage extends Model
{
    protected $table =  'umkm_image';
    protected $primaryKey = 'umkm_image_id';
    protected $guarded = [];
    protected $appends    = ['file_image_src'];

    public function getFileImageSrcAttribute()
    {
        return \helper::imageLoad('umkm/umkm_image', $this->file_image);
    }
}
