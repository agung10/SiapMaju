<?php

namespace App\Http\Controllers\RamahAnak;
use App\Http\Controllers\Controller;
use App\Http\Requests\RamahAnak\{ PosyanduRequest };
use App\Repositories\{ RamahAnakRepository }; 
use Illuminate\Http\Request;

class PosyanduController extends Controller
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
        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3);
    }

    public function create() {
        $vaccine = $this->ramah_anak->getVaccineOption();
        $familyMember = $this->ramah_anak->getFamilyMember(\Auth::user()->user_id);

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('vaccine', 'familyMember'));
    }

    public function store(PosyanduRequest $request) {
        return $this->ramah_anak->storeHealthcare($request);
    }

    public function show($id) {
        $data = $this->ramah_anak->getHealthcare(\Crypt::decrypt($id));

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data'));
    }

    public function edit($id) {
        $data = $this->ramah_anak->getHealthcare(\Crypt::decrypt($id));
        $vaccine = $this->ramah_anak->getVaccineOption($data->id_vaksin);
        $familyMember = $this->ramah_anak->getFamilyMember(\Auth::user()->user_id, $data->anggota_keluarga_id, true);

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data', 'vaccine', 'familyMember'));
    }

    public function update(PosyanduRequest $request, $id) {
        return $this->ramah_anak->updateHealthcare($request, $id);
    }

    public function destroy($id) {
        return $this->ramah_anak->destroyHealthcare($id);
    }

    public function dataTables() {
        return $this->ramah_anak->dataTablesHealthcare();
    }
}