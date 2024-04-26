<?php

use App\Models\RoleManagement\Menu;
use App\Models\RoleManagement\MenuPermission;
use App\Models\RoleManagement\Permission;
use App\Models\RoleManagement\Role;
use App\Models\RoleManagement\RoleMenu;
use Illuminate\Database\Seeder;

class MenuLaporanPollingRT extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = date("Y-m-d H:i:s");

        $polling = Menu::where('name', 'Polling')->whereNull('route')->first();

        $laporanRT = Menu::create([
            "name"       => "Laporan RT",
            "route"      => "Polling.LaporanRT",
            "id_parent"  => $polling->menu_id,
            "order"      => 6,
            "created_at" => $now,
            "updated_at" => $now,
        ]);

        $roleAdmin = Role::where('role_name', 'Admin')->first();

        $menuPermissionLaporanRT = RoleMenu::create([
            'role_id'    => $roleAdmin->role_id,
            'menu_id'    => $laporanRT->menu_id
        ]);

        $permissions = Permission::all();

        foreach ($permissions as $permission) {
            MenuPermission::create([
                'role_menu_id'  => $menuPermissionLaporanRT->role_menu_id,
                'permission_id' => $permission->permission_id
            ]);
        }
    }
}
