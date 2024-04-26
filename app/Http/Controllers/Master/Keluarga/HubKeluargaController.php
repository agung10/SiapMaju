<?php

namespace App\Http\Controllers\Master\Keluarga;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Keluarga\HubKeluarga;
use App\Repositories\Master\MasterRepository;
use App\Helpers\helper;

class HubKeluargaController extends Controller
{
    public function __construct(MasterRepository $_Master)
    {
        $route_name  = explode('.',\Route::currentRouteName());
        $this->route1 = ((isset($route_name[0])) ? $route_name[0] : (''));
        $this->route2 = ((isset($route_name[1])) ? $route_name[1] : (''));
        $this->route3 = ((isset($route_name[2])) ? $route_name[2] : (''));

        $this->master = $_Master;
    }

    public function index()
    {
        return view($this->route1.'.'.'Keluarga'.'.'.$this->route2.'.'.$this->route3);
    }

    public function create()
    {
        return view($this->route1.'.'.'Keluarga'.'.'.$this->route2.'.'.$this->route3);
    }

    public function store(Request $request, HubKeluarga $model)
    {

        return $this->master->store($request, $model);
    }

    public function show($id)
    {   
        $id = \Crypt::decryptString($id);
        $data = HubKeluarga::findOrFail($id);

        return view($this->route1.'.'.'Keluarga'.'.'.$this->route2.'.'.$this->route3, compact('data'));
    }

    public function edit($id)
    {   
        $id = \Crypt::decryptString($id);
        $data = HubKeluarga::findOrFail($id);

        return view($this->route1.'.'.'Keluarga'.'.'.$this->route2.'.'.$this->route3, compact('data'));
    }

    public function update(Request $request, $id, HubKeluarga $model)
    {   
        $id = \Crypt::decryptString($id);
        
        $rules = ['hubungan_kel' => 'required'];

        return $this->master->update($id, $request, $rules, $model);
    }

    public function destroy($id, HubKeluarga $model)
    {
        return $this->master->destroy($id, $model);
    }

    public function dataTables()
    {
        $datatableButtons = method_exists(new HubKeluarga, 'datatableButtons') ? HubKeluarga::datatableButtons() : ['show', 'edit', 'destroy'];
        $data = HubKeluarga::select('hub_keluarga.hub_keluarga_id', 'hub_keluarga.hubungan_kel')
                ->orderBy('hub_keluarga_id','desc')
                ->get();
        return \DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($data) use ($datatableButtons) {
            return view('partials.buttons.cust-datatable',[
                'show'         => in_array("show", $datatableButtons ) ? route('Master.HubKeluarga'.'.show', \Crypt::encryptString($data->hub_keluarga_id)) : null,
                'edit'         => in_array("edit", $datatableButtons ) ? route('Master.HubKeluarga'.'.edit', \Crypt::encryptString($data->hub_keluarga_id)) : null,
                'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $data->hub_keluarga_id : null
            ]);
        })->rawColumns(['action'])
        ->make(true);
    }
}
