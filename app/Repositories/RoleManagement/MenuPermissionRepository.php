<?php

namespace App\Repositories\RoleManagement;

use App\Models\RoleManagement\MenuPermission;
use App\Repositories\BaseRepository;
use App\Repositories\RoleManagement\{ 
	RoleRepository, 
	RoleMenuRepository, 
	PermissionRepository
};
use App\Traits\MenuPermissionTrait;
use App\Traits\EloquentTrait;

class MenuPermissionRepository
{
    use MenuPermissionTrait, EloquentTrait;

    public function __construct(MenuPermission $menuPermission, RoleRepository $role, RoleMenuRepository $roleMenu, PermissionRepository $permission)
    {
		$this->model      = $menuPermission;
		$this->role       = $role;
		$this->roleMenu   = $roleMenu;
		$this->permission = $permission;
    	
    }

    public function appendPermissionByRole($roleId) {
		$role             = $this->role->show($roleId);
		$menuPermissions  = $role->menuPermissions;
		$uniquePermission = $menuPermissions->unique('permission_id')->pluck('permission_id');
		$permissions      = $this->permission->model->whereIn('permission_id', $uniquePermission)->get();

        $stringPermission = '';

        foreach ($permissions as $permission) {
            $stringPermission .= '<code>'. $permission->permission_name .'</code>';
        }
        
        // return \Helper::limitText($stringPermission, 10);
        return $stringPermission;
    }

    public function updateMenuPermission($request, $roleId) {
        $menuPermissions = json_decode($request->menu_permission);
        $update          = false;
        $deletedRecords  = [];
        $newRecords      = [];

    	foreach ($menuPermissions as $menuPermission) {
    		$roleMenu = $this->roleMenu->model->where([
    						'role_id' => $roleId,
    						'menu_id' => $menuPermission->menu_id
    					])
    					->first();
    		
            array_push($deletedRecords, $roleMenu->role_menu_id);

    		foreach ($menuPermission->permissions as $permission) {
    			$newRecord['role_menu_id'] = $roleMenu->role_menu_id;
                $newRecord['permission_id'] = $permission;

                array_push($newRecords, $newRecord);
    		}
   
    	}

        $delete = $this->deleteBatch('role_menu_id', $deletedRecords);
        
        return $this->storeBatch($newRecords);
    }

    public function datatableMenuPermission($request)
    {
        $datatableButtons = method_exists(new $this->role->model, 'datatableButtons') ? $this->role->model->datatableButtons() : ['show', 'edit', 'destroy'];

        $data = \DB::table('role')
                ->select('role_id', 'role_name')
                ->orderBy('created_at', 'desc')
                ->get();
        return \DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('menu', function($role){
            return $this->roleMenu->appendMenuByRole($role->role_id);
        })
        ->addColumn('permission', function($role){
            return $this->appendPermissionByRole($role->role_id);
        })
        ->addColumn('action', function($data) use ($datatableButtons) {
            return view('partials.buttons.cust-datatable',[
                'show'    => in_array("show", $datatableButtons ) ? route('master.menu-permission'.'.show', \Crypt::encryptString($data->role_id)) : null,
                'edit'    => in_array("edit", $datatableButtons ) ? route('master.menu-permission'.'.edit', \Crypt::encryptString($data->role_id)) : null,
                'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $data->role_id : null
            ]);
        })
        ->rawColumns(['action', 'menu', 'permission'])
        ->make(true);
    }
}