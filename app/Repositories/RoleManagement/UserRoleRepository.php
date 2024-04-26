<?php

namespace App\Repositories\RoleManagement;

use App\Models\RoleManagement\UserRole;
use App\Repositories\BaseRepository;
use App\Traits\EloquentTrait;

class UserRoleRepository
{
	use EloquentTrait;
    public function __construct(UserRole $userRole)
    {
        $this->model = $userRole;
    }

}