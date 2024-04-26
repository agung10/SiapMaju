<?php

namespace App\Http\Controllers\RoleManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\RoleManagement\{ 
    MenuPermissionRepository, 
    RoleRepository, 
    RoleMenuRepository,
    PermissionRepository 
};

class MenuPermissionController extends Controller
{

    public function __construct(
        MenuPermissionRepository $menuPermission, 
        RoleRepository $role,
        RoleMenuRepository $roleMenu,
        PermissionRepository $permission
    )
    {
        $this->menuPermission = $menuPermission;
        $this->role           = $role;
        $this->permission     = $permission;
        $this->roleMenu       = $roleMenu;
    }
 
    public function index()
    {
        return view ('Master.role_management.menu_permission.index');
    }

    public function show($roleId)
    {   
        $roleId = \Crypt::decryptString($roleId);
        $editableMenu   = false;
        $withPermission = true;
        $role           = $this->role->show($roleId);
        $permissions    = $this->permission->model->all();
        $permissionIds  = $this->permission->model->pluck('permission_id');
        $menu           = $this->roleMenu->menuForRole($roleId, $editableMenu, $withPermission);
        $menuPermission = $this->roleMenu->constructMenu($menu);
        
        return view ('Master.role_management.menu_permission.show', compact('role', 'menuPermission', 'permissions', 'permissionIds'));
    }

    public function edit($roleId)
    {   
        $roleId = \Crypt::decryptString($roleId);
        $editableMenu   = false;
        $withPermission = true;
        $role           = $this->role->show($roleId);
        $permissions    = $this->permission->model->all();
        $permissionIds  = $this->permission->model->pluck('permission_id');
        $menu           = $this->roleMenu->menuForRole($roleId, $editableMenu, $withPermission);
        $menuPermission = $this->roleMenu->constructMenu($menu);

        return view('Master.role_management.menu_permission.edit', compact('role', 'menuPermission', 'permissions', 'permissionIds'));
    }

    public function update(Request $request, $roleId)
    {
        $this->menuPermission->updateMenuPermission( $request, $roleId );        
        return redirect()->route('master.menu-permission.index')->with('success', 'Berhasil mengubah menu permission !');
    }

    public function dataTables(Request $request)
    {
        return $this->menuPermission->datatableMenuPermission($request);
    }
}
