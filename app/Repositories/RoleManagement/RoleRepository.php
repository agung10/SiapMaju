<?php

namespace App\Repositories\RoleManagement;

use App\Models\RoleManagement\Role;
use App\Traits\EloquentTrait;

class RoleRepository
{
	use EloquentTrait;

    public function __construct(Role $role)
    {
        $this->model = $role;
    }

}