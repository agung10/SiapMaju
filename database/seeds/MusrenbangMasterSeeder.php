<?php

use Illuminate\Database\Seeder;
use App\Models\RoleManagement\{ Menu, Role, RoleMenu, Permission, MenuPermission };

class MusrenbangMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = date("Y-m-d H:i:s");

        $Musrenbang = Menu::create([
            "name"       => "Musrenbang",
            "icon"       => "fa fa-bookmark",
            "order"      => 44,
            "created_at" => $now,
            "updated_at" => $now,
        ]);

        $MasterM = Menu::create([
            "name"       => "Master",
            "id_parent"  => $Musrenbang->menu_id,
            "order"      => 1,
            "created_at" => $now,
            "updated_at" => $now,
        ]);
        $MenuUrusan = Menu::create([
            "name"       => "Menu Urusan",
            "route"      => "Musrenbang.Menu-Urusan",
            "id_parent"  => $MasterM->menu_id,
            "order"      => 1,
            "created_at" => $now,
            "updated_at" => $now,
        ]);
        $BidangUrusan = Menu::create([
            "name"       => "Bidang Urusan",
            "route"      => "Musrenbang.Bidang-Urusan",
            "id_parent"  => $MasterM->menu_id,
            "order"      => 2,
            "created_at" => $now,
            "updated_at" => $now,
        ]);
        $KegiatanUrusan = Menu::create([
            "name"       => "Kegiatan Urusan",
            "route"      => "Musrenbang.Kegiatan-Urusan",
            "id_parent"  => $MasterM->menu_id,
            "order"      => 3,
            "created_at" => $now,
            "updated_at" => $now,
        ]);

        $roleAdmin = Role::where('role_name', 'Admin')->first();

        $menuPermissionMusrenbang = RoleMenu::create([
            'role_id'    => $roleAdmin->role_id,
            'menu_id'    => $Musrenbang->menu_id
        ]);
        $menuPermissionMasterM = RoleMenu::create([
            'role_id'    => $roleAdmin->role_id,
            'menu_id'    => $MasterM->menu_id
        ]);
        $menuPermissionMenuUrusan = RoleMenu::create([
            'role_id'    => $roleAdmin->role_id,
            'menu_id'    => $MenuUrusan->menu_id
        ]);
        $menuPermissionBidangUrusan = RoleMenu::create([
            'role_id'    => $roleAdmin->role_id,
            'menu_id'    => $BidangUrusan->menu_id
        ]);
        $menuPermissionKegiatanUrusan = RoleMenu::create([
            'role_id'    => $roleAdmin->role_id,
            'menu_id'    => $KegiatanUrusan->menu_id
        ]);


        $permissions = Permission::all();

        foreach ($permissions as $permission) {
            MenuPermission::create([
                'role_menu_id'  => $menuPermissionMusrenbang->role_menu_id,
                'permission_id' => $permission->permission_id
            ]);
        }
        foreach ($permissions as $permission) {
            MenuPermission::create([
                'role_menu_id'  => $menuPermissionMasterM->role_menu_id,
                'permission_id' => $permission->permission_id
            ]);
        }
        foreach ($permissions as $permission) {
            MenuPermission::create([
                'role_menu_id'  => $menuPermissionMenuUrusan->role_menu_id,
                'permission_id' => $permission->permission_id
            ]);
        }
        foreach ($permissions as $permission) {
            MenuPermission::create([
                'role_menu_id'  => $menuPermissionBidangUrusan->role_menu_id,
                'permission_id' => $permission->permission_id
            ]);
        }
        foreach ($permissions as $permission) {
            MenuPermission::create([
                'role_menu_id'  => $menuPermissionKegiatanUrusan->role_menu_id,
                'permission_id' => $permission->permission_id
            ]);
        }
    }
}