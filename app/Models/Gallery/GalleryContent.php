<?php

namespace App\Models\Gallery;

use Illuminate\Database\Eloquent\Model;

class GalleryContent extends Model
{
    protected $table      = 'galeri_konten';
    protected $primaryKey = 'galeri_konten_id';
    protected $guarded    = [];
    protected $appends    = ['file_src'];

    public function getFileSrcAttribute()
    {
        return \helper::loadImgUpload('galeri/konten', $this->file);
    }
}
