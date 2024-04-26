<?php

namespace App\Repositories\RoleManagement;

use App\Models\RoleManagement\UserRole;
use App\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository
{
    public function __construct(User $user, UserRole $_userRole)
    {
        $this->model = $user;
        $this->userRole = $_userRole;
    }

    public function currentUser()
    {
        $data = \DB::table('user_role')->select(
            'user_role.user_role_id',
            'user_role.user_id',
            'user_role.role_id',
            'users.user_id',
            'users.is_admin',
            'role.role_name',
            'anggota_keluarga.anggota_keluarga_id',
            'anggota_keluarga.is_rt',
            'anggota_keluarga.is_rw',
            'anggota_keluarga.is_dkm',
            'anggota_keluarga.province_id',
            'anggota_keluarga.city_id',
            'anggota_keluarga.subdistrict_id',
            'anggota_keluarga.kelurahan_id',
            'anggota_keluarga.rw_id',
            'anggota_keluarga.rt_id',
        )
            ->leftJoin('role', 'role.role_id', 'user_role.role_id')
            ->leftJoin('users', 'users.user_id', 'user_role.user_id')
            ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
            ->where('users.user_id', \Auth::user()->user_id)
            ->first();

        return $data;
    }
}
