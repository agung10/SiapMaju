<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Repositories\Master\StatusPernikahanRepository;
use Illuminate\Http\Request;

class StatusPernikahanController extends Controller
{
    public function __construct(StatusPernikahanRepository $_StatusPernikahanRepository)
    {
        $route_name = explode('.',\Route::currentRouteName());
        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

        $this->StatusPernikahan = $_StatusPernikahanRepository;
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
        return $this->StatusPernikahan->store($request);
    }

    public function show($id)
    {   
        $id = \Crypt::decryptString($id);
        $data = $this->StatusPernikahan->show($id);

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('data'));
    }

    public function edit($id)
    {   
        $id = \Crypt::decryptString($id);
        $data = $this->StatusPernikahan->show($id);

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('data'));
    }

    public function update(Request $request, $id)
    {   
        $id = \Crypt::decryptString($id);
        
        return $this->StatusPernikahan->update($request,$id);
    }

    public function destroy($id)
    {
        return $this->StatusPernikahan->delete($id);
    }

    public function dataTables()
    {
        return $this->StatusPernikahan->dataTables();
    }
}
