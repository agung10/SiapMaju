<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table      = 'pengumuman';
    protected $primaryKey = 'pengumuman_id';
    protected $guarded    = [];
    protected $appends    = ['image1_src'];

    public function getImage1SrcAttribute()
    {
        return \helper::loadImgUpload('pengumuman', $this->image1);
    }
}
