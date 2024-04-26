<?php

namespace App\Musrenbang;

use Illuminate\Database\Eloquent\Model;

class KegiatanUrusan extends Model
{
    protected $table = 'kegiatan_urusan';
    protected $primaryKey = 'kegiatan_urusan_id';
    protected $guarded = [];
}
