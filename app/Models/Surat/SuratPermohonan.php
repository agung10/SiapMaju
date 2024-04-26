<?php

namespace App\Models\Surat;
use Illuminate\Database\Eloquent\Model;
use App\Models\Master\RT;
use App\Models\Master\RW;
use App\Models\Master\Agama;
use App\Models\Master\StatusPernikahan;
use App\Models\Master\Keluarga\AnggotaKeluarga;
use App\Models\Master\JenisSurat;

class SuratPermohonan extends Model
{
    protected $table = 'surat_permohonan';
    protected $primaryKey = 'surat_permohonan_id';
    protected $guarded = [];

    public function jenisSurat() {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    public function anggota_keluarga() {
        return $this->belongsTo(AnggotaKeluarga::class, 'anggota_keluarga_id');
    }

    public function agama() {
        return $this->belongsTo(Agama::class, 'agama_id');
    }

    public function status_pernikahan() {
        return $this->belongsTo(StatusPernikahan::class, 'status_pernikahan_id');
    }

    public function surat_permohonan_lampiran() {
        return $this->hasMany(SuratPermohonanLampiran::class, 'surat_permohonan_id');
    }

    public function letterType() {
        return $this->belongsTo('App\Models\Master\JenisSurat', 'jenis_surat_id', 'jenis_surat_id');
    }

    public function marriageStatus() {
        return $this->belongsTo('App\Models\Master\StatusPernikahan', 'status_pernikahan_id', 'status_pernikahan_id');
    }

    public function religion() {
        return $this->belongsTo('App\Models\Master\Agama', 'agama_id', 'agama_id');
    }

    public function wards() {
        return $this->belongsTo('App\Models\Master\Kelurahan', 'kelurahan_id', 'kelurahan_id');
    }

    public function rw() {
        return $this->belongsTo('App\Models\Master\RW', 'rw_id', 'rw_id');
    }

    public function rt() {
        return $this->belongsTo('App\Models\Master\RT', 'rt_id', 'rt_id');
    }
}