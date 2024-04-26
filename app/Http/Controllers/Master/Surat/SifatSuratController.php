<?php

namespace App\Http\Controllers\Master\Surat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Master\SifatSuratRepository;

class SifatSuratController extends Controller
{
    public function __construct(SifatSuratRepository $_SifatSuratRepository)
    {
        $route_name = explode('.',\Route::currentRouteName());
        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

        $this->sifatSurat = $_SifatSuratRepository;
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
        return $this->sifatSurat->store($request);
    }

    public function show($id)
    {   
        $id = \Crypt::decryptString($id);

        $data = $this->sifatSurat->show($id);

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('data'));
    }

    public function edit($id)
    {   
        $id = \Crypt::decryptString($id);
        
        $data = $this->sifatSurat->show($id);

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('data'));
    }

    public function update(Request $request, $id)
    {   
        $id = \Crypt::decryptString($id);

        return $this->sifatSurat->update($request,$id);
    }

    public function destroy($id)
    {
        return $this->sifatSurat->delete($id);
    }

    public function dataTables()
    {
        return $this->sifatSurat->dataTables();
    }
}
