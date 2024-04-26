<?php

namespace App\Models\RoleManagement;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table      = 'user_role';
	protected $primaryKey = 'user_role_id';
	protected $guarded    = [];
	public $timestamps    = false;
}
