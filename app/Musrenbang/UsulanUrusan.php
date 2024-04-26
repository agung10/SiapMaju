<?php

namespace App\Musrenbang;

use App\Models\Master\{RT, RW};
use App\User;
use Illuminate\Database\Eloquent\Model;

class UsulanUrusan extends Model
{
    protected $table = 'usulan_urusan';
    protected $primaryKey = 'usulan_urusan_id';
    protected $guarded = [];

    public function JenisUsulan() {
        return $this->belongsTo(MenuUrusan::class, 'menu_urusan_id');
    }

    public function Bidang() {
        return $this->belongsTo(BidangUrusan::class, 'bidang_urusan_id');
    }

    public function Kegiatan() {
        return $this->belongsTo(KegiatanUrusan::class, 'kegiatan_urusan_id');
    }

    public function RT() {
        return $this->belongsTo(RT::class, 'rt_id');
    }

    public function RW() {
        return $this->belongsTo(RW::class, 'rw_id');
    }

    public function User() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
