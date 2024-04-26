<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Model;

class DetailTrxKegiatan extends Model
{
    protected $table = 'detail_trx_kegiatan';
    protected $primaryKey = 'detail_trx_kegiatan_id';
    protected $guarded = [];
}
