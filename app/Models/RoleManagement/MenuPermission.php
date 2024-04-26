<?php

namespace App\Models\RoleManagement;

use Illuminate\Database\Eloquent\Model;

class MenuPermission extends Model
{
    protected $table      = 'menu_permission';
	protected $primaryKey = 'menu_permission_id';
	protected $guarded    = [];
	public $timestamps    = false;

	public function permission()
    {
        return $this->hasOne(Permission::class, 'permission_id', 'permission_id');
    }
}
