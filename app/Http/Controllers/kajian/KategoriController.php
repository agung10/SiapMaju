<?php

namespace App\Http\Controllers\kajian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\KatKajianRepository;

class KategoriController extends Controller
{
    public function __construct(KatKajianRepository $_KatKajianRepository)
    {
        $route_name = explode('.',\Route::currentRouteName());
        $this->route1 = ((isset($route_name[0])) ? $route_name[0] : (''));
        $this->route2 = ((isset($route_name[1])) ? $route_name[1] : (''));
        $this->route3 = ((isset($route_name[2])) ? $route_name[2] : (''));

        $this->kategori = $_KatKajianRepository;
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
        return $this->kategori->store($request);
    }

    public function show($id)
    {   
        $id = \Crypt::decryptString($id);

        $data = $this->kategori->show($id);

        return view($this->route1.'.'.$this->route2.'.'.$this->route3,compact('data'));
    }

    public function edit($id)
    {   
        $id = \Crypt::decryptString($id);
        
        $data = $this->kategori->show($id);

        return view($this->route1.'.'.$this->route2.'.'.$this->route3,compact('data'));
    }

    public function update(Request $request, $id)
    {   
        $id = \Crypt::decryptString($id);
        
        return $this->kategori->update($request,$id);
    }

    public function destroy($id)
    {
        return $this->kategori->delete($id);
    }

    public function dataTables()
    {
        return $this->kategori->dataTables();
    }
}
