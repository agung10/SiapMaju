<?php

namespace App\Models\Master\Transaksi;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $primaryKey = 'transaksi_id';

    protected $guarded = [];
}
