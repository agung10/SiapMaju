<?php

namespace App\Http\Controllers\RoleManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\RoleManagement\PermissionRepository;
use App\Helpers\Helper;

class PermissionController extends Controller
{

    public function __construct(PermissionRepository $permission)
    {
        $this->permission = $permission;
    }
    
    public function index()
    {
        return view ('Master.role_management.permission.index');
    }

    public function create()
    {
        return view ('Master.role_management.permission.create');
    }

    public function store(Request $request)
    {
        $rules     = ['permission_name' => 'required', 'permission_action' => 'required', 'description' => 'required'];

        $validator = helper::validation($request->all(),$rules);

        if($validator->fails()){
            return response()->json(['status' => 'failed', 'errors' => $validator->getMessageBag()->toArray()]);
        }
        return $this->permission->store($request->all());
    }

    public function show($permissionId)
    {   
        $permissionId = \Crypt::decryptString($permissionId);
        $data = $this->permission->show($permissionId);

        return view('Master.role_management.permission.show', compact('data'));
    }

    public function edit($permissionId)
    {   
        $permissionId = \Crypt::decryptString($permissionId);
        $data = $this->permission->show($permissionId);

        return view('Master.role_management.permission.edit', compact('data'));
    }

    public function update(Request $request, $permissionId)
    {   
        $permissionId = \Crypt::decryptString($permissionId);
        
        $rules = ['permission_name' => 'required', 'permission_action' => 'required', 'description' => 'required'];

        $validator = helper::validation($request->all(),$rules);

        if($validator->fails()){
            return response()->json(['status' => 'failed', 'errors' => $validator->getMessageBag()->toArray()]);
        }
        return $this->permission->update( $request->all(), $permissionId );
    }

    public function destroy($id)
    {
       $this->permission->delete($id);
       return response()->json(['status' => 'success']);
    }

    public function dataTables()
    {
        $datatableButtons = method_exists(new $this->permission->model, 'datatableButtons') ? $this->permission->model->datatableButtons() : ['show', 'edit', 'destroy'];

        $data = \DB::table('permission')
                ->select('permission_id', 'permission_name', 'description', 'permission_action')
                ->orderBy('created_at', 'desc')
                ->get();
        return \DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($data) use ($datatableButtons) {
            return view('partials.buttons.cust-datatable',[
                'show'         => in_array("show", $datatableButtons ) ? route('master.permission'.'.show', \Crypt::encryptString($data->permission_id)) : null,
                'edit'         => in_array("edit", $datatableButtons ) ? route('master.permission'.'.edit', \Crypt::encryptString($data->permission_id)) : null,
                'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $data->permission_id : null
            ]);
        })
        ->rawColumns(['action'])
        ->make(true);
    }
}
