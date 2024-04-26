<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\helper;
use App\Models\Master\Agama;

class AgamaController extends Controller
{
    public function __construct()
    {
        $route_name  = explode('.',\Route::currentRouteName());
        $this->route1 = ((isset($route_name[0])) ? $route_name[0] : (''));
        $this->route2 = ((isset($route_name[1])) ? $route_name[1] : (''));
        $this->route3 = ((isset($route_name[2])) ? $route_name[2] : (''));
    }

    public function index()
    {
        return view($this->route1.'.'.$this->route2.'.'.$this->route3);
    }

    public function create()
    {
        return view($this->route1.'.'.$this->route2.'.'.$this->route3);
    }

    public function store(Request $request)
    {
        $input = $request->except('proengsoft_jsvalidation');
        $input['user_created'] = \Auth::user()->user_id;

        \DB::beginTransaction();

        try {
            Agama::create($input);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {   
        $id = \Crypt::decryptString($id);
        $data = Agama::findOrFail($id);

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('data'));
    }

    public function edit($id)
    {   
        $id = \Crypt::decryptString($id);
        $data = Agama::findOrFail($id);

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('data'));
    }

    public function update(Request $request, $id)
    { 
        $data = Agama::findOrFail(\Crypt::decryptString($id));

        $input = $request->except('proengsoft_jsvalidation');
        $input['updated_at'] = date('Y-m-d H:i:s');
        $input['user_updated'] = \Auth::user()->user_id;

        \DB::beginTransaction();

        try {
            $data->update($input);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        $data = Agama::findOrFail($id);

        try {
            $data->delete();
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function dataTables()
    {
        $datatableButtons = method_exists(new Agama, 'datatableButtons') ? Agama::datatableButtons() : ['show', 'edit', 'destroy'];
        $data = Agama::select('agama_id','nama_agama')->orderBy('agama_id', 'desc')->get();
        return \DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($data) use ($datatableButtons) {
            return view('partials.buttons.cust-datatable',[
                'show'         => in_array("show", $datatableButtons ) ? route('Master.Agama'.'.show', \Crypt::encryptString($data->agama_id)) : null,
                'edit'         => in_array("edit", $datatableButtons ) ? route('Master.Agama'.'.edit', \Crypt::encryptString($data->agama_id)) : null,
                'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $data->agama_id : null
            ]);
        })->rawColumns(['action'])
        ->make(true);
    }
}
