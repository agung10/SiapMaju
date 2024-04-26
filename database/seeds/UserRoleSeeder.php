<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Models\RoleManagement\{	
	Role,
	UserRole
};

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserRole::create([
			'user_id'    => User::first()->user_id,
			'role_id'    => Role::first()->role_id
        ]);

        UserRole::create([
            'user_id'    => User::first()->user_id + 1,
            'role_id'    => Role::first()->role_id + 1
        ]);

    }
}
