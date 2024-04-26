<?php

namespace App\Repositories\RoleManagement;

use App\Models\RoleManagement\Permission;
use App\Traits\EloquentTrait;

class PermissionRepository
{
	use EloquentTrait;
    public function __construct(Permission $permission)
    {
        $this->model = $permission;
    }

}