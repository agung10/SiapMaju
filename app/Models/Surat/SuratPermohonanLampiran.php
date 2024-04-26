<?php

namespace App\Models\Surat;

use Illuminate\Database\Eloquent\Model;

class SuratPermohonanLampiran extends Model
{
    protected $table      = "surat_permohonan_lampiran";
    protected $primaryKey = "surat_permohonan_lampiran_id";
    protected $guarded    = [];
    protected $appends    = ['upload_lampiran_src'];

    public function getUploadLampiranSrcAttribute()
    {
        return \helper::imageLoad('surat', $this->upload_lampiran);
    }

    public function lampiran() {
        return $this->belongsTo(Lampiran::class, 'lampiran_id');
    }
}
