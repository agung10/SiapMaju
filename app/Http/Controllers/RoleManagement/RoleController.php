<?php

namespace App\Http\Controllers\RoleManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\RoleManagement\RoleRepository;
use App\Helpers\helper;

class RoleController extends Controller
{
    public function __construct(RoleRepository $role)
    {
        $this->role = $role;
    }
    
    public function index()
    {
        return view ('Master.role_management.role.index');
    }

    public function create()
    {
        return view ('Master.role_management.role.create');
    }

    public function store(Request $request)
    {
        
        $rules    = ['role_name' => 'required'];

        $validator = helper::validation($request->all(),$rules);

        if($validator->fails()){
            return response()->json(['status' => 'failed', 'errors' => $validator->getMessageBag()->toArray()]);
        }
        return $this->role->store($request->all());
    }

    public function show($roleId)
    {   
        $roleId = \Crypt::decryptString($roleId);
        $data = $this->role->show($roleId);

        return view('Master.role_management.role.show', compact('data'));
    }

    public function edit($roleId)
    {   
        $roleId = \Crypt::decryptString($roleId);
        $data = $this->role->show($roleId);

        return view('Master.role_management.role.edit', compact('data'));
    }

    public function update(Request $request, $roleId)
    {   
        $roleId = \Crypt::decryptString($roleId);
        $rules     = ['role_name' => 'required'];

        $validator = helper::validation($request->all(),$rules);

        if($validator->fails()){
            return response()->json(['status' => 'failed', 'errors' => $validator->getMessageBag()->toArray()]);
        }
        return $this->role->update( $request->all(), $roleId );
    }

    public function destroy($id)
    {
       $this->role->delete($id);
       return response()->json(['status' => 'success']);
    }

    public function dataTables()
    {
        $datatableButtons = method_exists(new $this->role->model, 'datatableButtons') ? $this->role->model->datatableButtons() : ['show', 'edit', 'destroy'];

        $data = \DB::table('role')
                ->select('role_id', 'role_name', 'description', 'is_active')
                ->orderBy('created_at', 'desc')
                ->get();
        return \DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($data) use ($datatableButtons) {
            return view('partials.buttons.cust-datatable',[
                'show'         => in_array("show", $datatableButtons ) ? route('master.role'.'.show', \Crypt::encryptString($data->role_id)) : null,
                'edit'         => in_array("edit", $datatableButtons ) ? route('master.role'.'.edit', \Crypt::encryptString($data->role_id)) : null,
                'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $data->role_id : null
            ]);
        })
        ->rawColumns(['action'])
        ->make(true);
    }
}
