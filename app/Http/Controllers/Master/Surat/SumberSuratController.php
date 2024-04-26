<?php

namespace App\Http\Controllers\Master\Surat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Surat\SumberSuratRepository;

class SumberSuratController extends Controller
{   
    public function __construct(SumberSuratRepository $_SumberSuratRepository)
    {
        $route_name = explode('.',\Route::currentRouteName());
        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

        $this->sumberSurat = $_SumberSuratRepository;
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
        return $this->sumberSurat->store($request);
    }

    public function show($id)
    {   
        $id = \Crypt::decryptString($id);

        $data = $this->sumberSurat->show($id);

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('data'));
    }

    public function edit($id)
    {   
        $id = \Crypt::decryptString($id);
        
        $data = $this->sumberSurat->show($id);

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('data'));
    }

    public function update(Request $request, $id)
    {   
        $id = \Crypt::decryptString($id);

        return $this->sumberSurat->update($request,$id);
    }

    public function destroy($id)
    {
        return $this->sumberSurat->delete($id);
    }

    public function dataTables()
    {
        return $this->sumberSurat->dataTables();
    }
}
