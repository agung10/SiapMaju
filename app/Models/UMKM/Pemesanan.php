<?php

namespace App\Models\UMKM;

use App\Models\Master\Keluarga\AnggotaKeluarga;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $table      = 'pemesanan';
    protected $primaryKey = 'pemesanan_id';
    protected $guarded    = [];

    public function anggota_keluarga(){
        return $this->belongsTo(AnggotaKeluarga::class, 'anggota_keluarga_id');
    }

    public function umkm()
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }

    public function produk()
    {
        return $this->belongsTo(UmkmProduk::class, 'umkm_produk_id');
    }
}
