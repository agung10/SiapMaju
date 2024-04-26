<?php

namespace App\Models\Laporan;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table      = 'laporan';
    protected $primaryKey = 'laporan_id';
    protected $guarded    = [];
    protected $appends    = ['image_src', 'upload_materi_src'];

    public function getImageSrcAttribute()
    {
        return \helper::loadImgUpload('laporan', $this->image);
    }

    public function getUploadMateriSrcAttribute()
    {
        return \helper::loadImgUpload('laporan/materi', $this->upload_materi);
    }

    public function kategori()
    {
        return $this->belongsTo(KatLaporan::class, 'kat_laporan_id');
    }

    public function kelurahan()
    {
        return $this->belongsTo(\App\Models\Master\Kelurahan::class, 'kelurahan_id');
    }

    public function rw()
    {
        return $this->belongsTo(\App\Models\Master\RW::class, 'rw_id');
    }

    public function rt()
    {
        return $this->belongsTo(\App\Models\Master\RT::class, 'rt_id');
    }
}
