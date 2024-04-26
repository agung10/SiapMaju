<?php

use App\Models\RoleManagement\Menu;
use App\Models\RoleManagement\MenuPermission;
use App\Models\RoleManagement\Permission;
use App\Models\RoleManagement\Role;
use App\Models\RoleManagement\RoleMenu;
use Illuminate\Database\Seeder;

class MenuLaporanAuditPollingSeeder extends Seeder
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

        $laporanAudit = Menu::create([
            "name"       => "Laporan Audit",
            "route"      => "Polling.LaporanAudit",
            "id_parent"  => $polling->menu_id,
            "order"      => 5,
            "created_at" => $now,
            "updated_at" => $now,
        ]);

        $roleAdmin = Role::where('role_name', 'Admin')->first();

        $menuPermissionLaporanAudit = RoleMenu::create([
            'role_id'    => $roleAdmin->role_id,
            'menu_id'    => $laporanAudit->menu_id
        ]);

        $permissions = Permission::all();

        foreach ($permissions as $permission) {
            MenuPermission::create([
                'role_menu_id'  => $menuPermissionLaporanAudit->role_menu_id,
                'permission_id' => $permission->permission_id
            ]);
        }
    }
}
