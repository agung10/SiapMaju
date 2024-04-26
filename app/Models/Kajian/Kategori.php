<?php

namespace App\models\Kajian;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kat_kajian';
    protected $primaryKey = 'kat_kajian_id';
    protected $guarded = [];
}
