<?php

namespace App\Http\Controllers\RamahAnak;
use App\Http\Controllers\Controller;
use App\Http\Requests\RamahAnak\{ VaksinRequest };
use App\Repositories\{ RamahAnakRepository }; 
use Illuminate\Http\Request;

class VaksinController extends Controller
{   
    public function __construct(RamahAnakRepository $RamahAnakRepository)
    {
        $route_name = explode('.', \Route::currentRouteName());
        $this->route1 = ((isset($route_name[0])) ? $route_name[0] : (''));
        $this->route2 = ((isset($route_name[1])) ? $route_name[1] : (''));
        $this->route3 = ((isset($route_name[2])) ? $route_name[2] : (''));
        $this->ramah_anak =  $RamahAnakRepository;
    }

    public function index() {
        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3);
    }

    public function create() {
        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3);
    }

    public function store(VaksinRequest $request) {
        return $this->ramah_anak->storeVaccine($request);
    }

    public function show($id) {
        $data = $this->ramah_anak->getVaccine(\Crypt::decrypt($id));
        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data'));
    }

    public function edit($id) {
        $data = $this->ramah_anak->getVaccine(\Crypt::decrypt($id));
        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data'));
    }

    public function update(VaksinRequest $request, $id) {
        return $this->ramah_anak->updateVaccine($request, $id);
    }

    public function destroy($id) {
        return $this->ramah_anak->destroyVaccine($id);
    }

    public function dataTables() {
        return $this->ramah_anak->dataTablesVaccine();
    }
}