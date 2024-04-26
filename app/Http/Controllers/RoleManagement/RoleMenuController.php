<?php

namespace App\Http\Controllers\RoleManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\RoleManagement\{ RoleMenuRepository, RoleRepository };

class RoleMenuController extends Controller
{
    
    public function __construct(RoleMenuRepository $roleMenu, RoleRepository $role)
    {
        $this->roleMenu = $roleMenu;
        $this->role     = $role;
    }
    
    public function index()
    {        
        return view ('Master.role_management.role_menu.index');
    }

    public function show($roleId)
    {   
        $roleId = \Crypt::decryptString($roleId);
        $editableMenu = false;
        $role         = $this->role->show($roleId);
        $menu         = $this->roleMenu->menuForRole($roleId, $editableMenu);
        $roleMenu     = $this->roleMenu->constructMenu($menu);

        return view('Master.role_management.role_menu.show', compact('role', 'roleMenu'));
    }

    public function edit($roleId)
    {   
        $roleId = \Crypt::decryptString($roleId);
        $editableMenu = true;
        $role         = $this->role->show($roleId);
        $menu         = $this->roleMenu->menuForRole($roleId, $editableMenu);
        $roleMenu     = $this->roleMenu->constructMenu($menu);

        return view('Master.role_management.role_menu.edit', compact('role', 'roleMenu'));
    }

    public function update(Request $request, $roleId)
    {

        $this->roleMenu->updateRoleMenu( $request, $roleId );
        return redirect()->route('master.role-menu.index')->with('success', 'Berhasil mengubah role menu !');;
    }

    public function dataTables()
    {
        $datatableButtons = method_exists(new $this->role->model, 'datatableButtons') ? $this->role->model->datatableButtons() : ['show', 'edit', 'destroy'];

        $data = \DB::table('role')
                ->select('role_id', 'role_name')
                ->orderBy('created_at', 'desc')
                ->get();
        return \DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('menu', function($role) {
            return $this->roleMenu->appendMenuByRole($role->role_id);
        })
        ->addColumn('action', function($data) use ($datatableButtons) {
            return view('partials.buttons.cust-datatable',[
                'show'         => in_array("show", $datatableButtons ) ? route('master.role-menu'.'.show', \Crypt::encryptString($data->role_id)) : null,
                'edit'         => in_array("edit", $datatableButtons ) ? route('master.role-menu'.'.edit', \Crypt::encryptString($data->role_id)) : null,
                'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $data->role_id : null
            ]);
        })
        ->rawColumns(['action', 'menu'])
        ->make(true);
    }
}
