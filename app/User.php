<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\RoleManagement\{
    UserRole, Role
};
use App\Models\Master\Keluarga\AnggotaKeluarga;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function getEmailAttribute($value) {
        return strtolower($value);
    }

    public function userRole()
    {
        return $this->hasOne(UserRole::class, 'user_id', 'user_id');
    }

    public function anggotaKeluarga()
    {
        return $this->hasOne(AnggotaKeluarga::class, 'anggota_keluarga_id', 'anggota_keluarga_id');
    }

    public function role()
    {
        return $this->hasOneThrough(
            Role::class,
            UserRole::class,
            'user_id', // Foreign key on user_role table...
            'role_id', // Foreign key on role table...
            'user_id', // Local key on user table...
            'role_id' // Local key on user_role table...
        );
    }

    public function childHealthcare() {
        return $this->hasMany('App\Models\RamahAnak\RamahAnak', 'anggota_keluarga_id', 'anggota_keluarga_id');
    }
}
