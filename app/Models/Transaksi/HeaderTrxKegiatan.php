<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Model;
use App\Models\Master\Kegiatan\Kegiatan;
use App\Models\Master\Kegiatan\KatKegiatan;
use App\Models\Master\Keluarga\AnggotaKeluarga;
use App\Models\Master\Transaksi\Transaksi;

class HeaderTrxKegiatan extends Model
{
    protected $table      = 'header_trx_kegiatan';
    protected $primaryKey = 'header_trx_kegiatan_id';
    protected $guarded    = [];

    public function detail() 
    {
        return $this->hasMany(DetailTrxKegiatan::class, 'header_trx_kegiatan_id');
    }

    public function transaksi() 
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    public function katKegiatan() 
    {
        return $this->belongsTo(KatKegiatan::class, 'kat_kegiatan_id');
    }

    public function Kegiatan() 
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function anggotaKeluarga() 
    {
        return $this->belongsTo(AnggotaKeluarga::class, 'anggota_keluarga_id');
    }

    public function scopeNonDKM($query)
    {
        $query->whereIn('kegiatan_id', function($q){
            $q->select('kegiatan_id')
              ->from('kegiatan')
              ->where('rt_id', '!=', 7);
        });
    }
}
