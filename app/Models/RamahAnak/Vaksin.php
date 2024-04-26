<?php

namespace App\Models\RamahAnak;
use Illuminate\Database\Eloquent\Model;

class Vaksin extends Model
{
    protected $table = 'vaksin';
    protected $primaryKey = 'id_vaksin';
    protected $guarded = [];

    public function childHealthcare() {
        return $this->belongsTo('App\Models\RamahAnak\RamahAnak', 'id_vaksin', 'id_vaksin');
    }
}