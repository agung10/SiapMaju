<?php

use App\Models\RoleManagement\{Menu, MenuPermission, Permission, Role, RoleMenu};
use Illuminate\Database\Seeder;

class MusrenbangApprovalSeeder extends Seeder
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

        $ApprovalKelurahan = Menu::create([
            "name"       => "Approval Kelurahan",
            "route"      => "Musrenbang.Approval-Kelurahan",
            "id_parent"  => $Musrenbang->menu_id,
            "order"      => 3,
            "created_at" => $now,
            "updated_at" => $now,
        ]);
        $ApprovalKecamatan = Menu::create([
            "name"       => "Approval Kecamatan",
            "route"      => "Musrenbang.Approval-Kecamatan",
            "id_parent"  => $Musrenbang->menu_id,
            "order"      => 4,
            "created_at" => $now,
            "updated_at" => $now,
        ]);
        $ApprovalWalikota = Menu::create([
            "name"       => "Approval Walikota",
            "route"      => "Musrenbang.Approval-Walikota",
            "id_parent"  => $Musrenbang->menu_id,
            "order"      => 5,
            "created_at" => $now,
            "updated_at" => $now,
        ]);

        $roleAdmin = Role::where('role_name', 'Admin')->first();

        $menuPermissionApprovalKelurahan = RoleMenu::create([
            'role_id'    => $roleAdmin->role_id,
            'menu_id'    => $ApprovalKelurahan->menu_id
        ]);
        $menuPermissionApprovalKecamatan = RoleMenu::create([
            'role_id'    => $roleAdmin->role_id,
            'menu_id'    => $ApprovalKecamatan->menu_id
        ]);
        $menuPermissionApprovalWalikota = RoleMenu::create([
            'role_id'    => $roleAdmin->role_id,
            'menu_id'    => $ApprovalWalikota->menu_id
        ]);

        $permissions = Permission::all();

        foreach ($permissions as $permission) {
            MenuPermission::create([
                'role_menu_id'  => $menuPermissionApprovalKelurahan->role_menu_id,
                'permission_id' => $permission->permission_id
            ]);
            MenuPermission::create([
                'role_menu_id'  => $menuPermissionApprovalKecamatan->role_menu_id,
                'permission_id' => $permission->permission_id
            ]);
            MenuPermission::create([
                'role_menu_id'  => $menuPermissionApprovalWalikota->role_menu_id,
                'permission_id' => $permission->permission_id
            ]);
        }
    }
}
