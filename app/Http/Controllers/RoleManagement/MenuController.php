<?php

namespace App\Http\Controllers\RoleManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\RoleManagement\MenuRepository;
use App\Helpers\helper;

class MenuController extends Controller
{
    public function __construct(MenuRepository $menu)
    {
        $this->menu = $menu;
    }
    
    public function index()
    {
        return view ('Master.role_management.menu.index');
    }

    public function create()
    {
        $menuList   = $this->menu->menuList();
        $statusList = $this->menu->model->statusList();

        return view ('Master.role_management.menu.create', compact('menuList', 'statusList'));
    }

    public function store(Request $request)
    {
        
        $rules     = ['name' => 'required', 'id_parent' => 'required', 'is_active' => 'required', 'order' => 'required'];

        $validator = helper::validation($request->all(),$rules);

        if($validator->fails()){
            return response()->json(['status' => 'failed', 'errors' => $validator->getMessageBag()->toArray()]);
        }

        $request['id_parent'] = $request->id_parent == 0 ? null : $request->id_parent;
        
        return $this->menu->store($request->all());

    }

    public function show($menuId)
    {   
        $menuId = \Crypt::decryptString($menuId);
        $data       = $this->menu->show($menuId);
        $menuList   = $this->menu->menuList();
        $statusList = $this->menu->model->statusList();

        return view('Master.role_management.menu.show', compact('data', 'menuList', 'statusList'));
    }

    public function edit($menuId)
    {   
        $menuId = \Crypt::decryptString($menuId);
        $data       = $this->menu->show($menuId);
        $menuList   = $this->menu->menuList();
        $statusList = $this->menu->model->statusList();

        return view('Master.role_management.menu.edit', compact('data', 'menuList', 'statusList'));
    }

    public function update(Request $request, $menuId)
    {   
        $menuId = \Crypt::decryptString($menuId);
        $rules     = ['name' => 'required', 'id_parent' => 'required', 'is_active' => 'required', 'order' => 'required'];

        $validator = helper::validation($request->all(),$rules);

        if($validator->fails()){
            return response()->json(['status' => 'failed', 'errors' => $validator->getMessageBag()->toArray()]);
        }
        return $this->menu->updateMenu( $request->all(), $menuId );
    }

    public function destroy($id)
    {
       $this->menu->delete($id);
       return response()->json(['status' => 'success']);
    }

    public function dataTables()
    {
        $datatableButtons = method_exists(new $this->menu->model, 'datatableButtons') ? $this->menu->model->datatableButtons() : ['show', 'edit', 'destroy'];

        $data = \DB::table('menu')
                ->select('menu.menu_id', 'menu.name', 'menu.route', 'menu.id_parent', 'menu.is_active', 'parent.name as nama_parent')
                ->leftJoin('menu as parent', 'parent.menu_id', 'menu.id_parent');

        return \DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($data) use ($datatableButtons) {
            return view('partials.buttons.cust-datatable',[
                'show'         => in_array("show", $datatableButtons ) ? route('master.menu'.'.show', \Crypt::encryptString($data->menu_id)) : null,
                'edit'         => in_array("edit", $datatableButtons ) ? route('master.menu'.'.edit', \Crypt::encryptString($data->menu_id)) : null,
                'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $data->menu_id : null
            ]);
        })
        ->addColumn('cust_nama_parent', function($data){
            return $data->nama_parent ?? '-';
        })
        ->addColumn('cust_route', function($data){
            return $data->route ?? '-';
        })
        ->addColumn('cust_is_active', function($data){
            return $data->is_active ? '<span class="badge badge-primary">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>';
        })
        ->rawColumns(['action', 'cust_nama_parent', 'cust_route', 'cust_is_active'])
        ->make(true);
    }
}
