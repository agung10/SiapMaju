<?php

namespace App\Models\Gallery;

use Illuminate\Database\Eloquent\Model;
use App\Models\Agenda;

class Gallery extends Model
{
	protected $table      = 'galeri';
	protected $primaryKey = 'galeri_id';
	protected $guarded    = [];
	protected $appends    = ['image_cover_src'];

    public function getImageCoverSrcAttribute()
    {
        return \helper::loadImgUpload('galeri', $this->image_cover);
    }

	public function agenda() 
	{
        return $this->belongsTo(Agenda::class, 'agenda_id');
    }
}
