<?php

namespace App\Http\Controllers\Master\JenisSurat;

use App\Http\Controllers\Controller;
use App\Repositories\Master\JenisSuratRepository;
use App\Http\Requests\Master\JenisSuratRequest;
use Illuminate\Http\Request;

class JenisSuratController extends Controller
{
    public function __construct(JenisSuratRepository $_JenisSuratRepository)
    {
        $route_name = explode('.',\Route::currentRouteName());
        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

        $this->jenisSurat = $_JenisSuratRepository;
    }

    public function index()
    {
        return view($this->route1.'.'.$this->route2.'.'.$this->route3);
    }

    public function create()
    {
        return view($this->route1.'.'.$this->route2.'.'.$this->route3);
    }

    public function store(JenisSuratRequest $request)
    {
        return $this->jenisSurat->store($request);
    }

    public function show($id)
    {   
        $id = \Crypt::decryptString($id);

        $data = $this->jenisSurat->show($id);

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('data'));
    }

    public function edit($id)
    {   
        $id = \Crypt::decryptString($id);
        
        $data = $this->jenisSurat->show($id);

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('data'));
    }

    public function update(JenisSuratRequest $request, $id)
    {   
        $id = \Crypt::decryptString($id);

        return $this->jenisSurat->update($request,$id);
    }

    public function destroy($id)
    {
        return $this->jenisSurat->delete($id);
    }

    public function dataTables()
    {
        return $this->jenisSurat->dataTables();
    }
}
