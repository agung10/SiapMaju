<?php

namespace App\Http\Controllers\RoleManagement;

use App\Http\Controllers\Controller;
use App\Repositories\RoleManagement\ListRtRwRepository;
use Illuminate\Http\Request;

class ListRtRwController extends Controller
{   
    public function __construct(ListRtRwRepository $_ListRtRwRepository)
    {
        $route_name = explode('.',\Route::currentRouteName());
        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

        $this->list = $_ListRtRwRepository;
    }

    public function index()
    {
        return view('Master.'.$this->route1.'.'.$this->route2.'.'.$this->route3);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {   
        return $this->list->update($request,$id);
    }

    public function destroy($id)
    {
        //
    }

    public function dataTables()
    {
        return $this->list->dataTables();
    }
}
