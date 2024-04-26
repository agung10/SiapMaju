<?php

use App\Models\RoleManagement\{Menu, MenuPermission, Permission, Role, RoleMenu};
use Illuminate\Database\Seeder;

class UsulanUrusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = date("Y-m-d H:i:s");

        $Musrenbang = Menu::where('name', 'Musrenbang')->whereNull('route')->first();

        $UsulanUrusan = Menu::create([
            "name"       => "Usulan Urusan",
            "route"      => "Musrenbang.Usulan-Urusan",
            "id_parent"  => $Musrenbang->menu_id,
            "order"      => 2,
            "created_at" => $now,
            "updated_at" => $now,
        ]);

        $roleAdmin = Role::where('role_name', 'Admin')->first();

        $menuPermission = RoleMenu::create([
            'role_id'    => $roleAdmin->role_id,
            'menu_id'    => $UsulanUrusan->menu_id
        ]);

        $permissions = Permission::all();

        foreach ($permissions as $permission) {
            MenuPermission::create([
                'role_menu_id'  => $menuPermission->role_menu_id,
                'permission_id' => $permission->permission_id
            ]);
        }
    }
}
