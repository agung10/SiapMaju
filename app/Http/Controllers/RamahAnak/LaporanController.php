<?php

namespace App\Http\Controllers\RamahAnak;
use App\Http\Controllers\Controller;
use App\Repositories\{ RamahAnakRepository }; 
use Illuminate\Http\Request;

class LaporanController extends Controller
{   
    public function __construct(RamahAnakRepository $RamahAnakRepository)
    {
        $route_name = explode('.', \Route::currentRouteName());
        $this->route1 = ((isset($route_name[0])) ? $route_name[0] : (''));
        $this->route2 = ((isset($route_name[1])) ? $route_name[1] : (''));
        $this->route3 = ((isset($route_name[2])) ? $route_name[2] : (''));
        $this->ramah_anak = $RamahAnakRepository;
    }

    public function index() {
        $rw = $this->ramah_anak->getRWOption();

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('rw'));
    }

    public function create() {}

    public function store(Request $request) {}

    public function show($id) {
        $data = $this->ramah_anak->getReport(\Crypt::decrypt($id));
        $graphData = $this->ramah_anak->collectGraphData($data);

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data', 'graphData'));
    }

    public function edit($id) {}

    public function update(Request $request, $id) {}

    public function destroy($id) {}

    public function dataTables(Request $request) {
        return $this->ramah_anak->dataTablesReport($request->id);
    }
}