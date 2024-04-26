<?php

namespace App\Models\Master\Transaksi;

use Illuminate\Database\Eloquent\Model;

class JenisTransaksi extends Model
{
    protected $table = 'jenis_transaksi';

    protected $primaryKey = 'jenis_transaksi_id';

    protected $guarded = [];
}
