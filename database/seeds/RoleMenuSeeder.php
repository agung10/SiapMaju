<?php

use Illuminate\Database\Seeder;
use App\Models\RoleManagement\{
	Menu,
	Role,
	RoleMenu
};

class RoleMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::first()->role_id; // admin
        $menus = Menu::all();

        foreach ($menus as $menu) {
            RoleMenu::create([
                'role_id'    => $admin,
                'menu_id'    => $menu->menu_id
            ]);
        }        

    }
}
