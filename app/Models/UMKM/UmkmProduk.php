<?php

namespace App\Models\UMKM;

use Illuminate\Database\Eloquent\Model;

class UmkmProduk extends Model
{
    protected $table      = 'umkm_produk';
    protected $primaryKey = 'umkm_produk_id';
    protected $guarded    = [];
    protected $appends    = ['image_src'];

    public function getImageSrcAttribute()
    {
        return \helper::imageLoad('umkm', $this->image);
    }
    
    public function images()
    {
        return $this->hasMany(UmkmImage::class, 'umkm_produk_id');
    }

    public function umkm()
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }
}
