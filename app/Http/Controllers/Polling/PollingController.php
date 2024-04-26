<?php

namespace App\Http\Controllers\Polling;
use App\Http\Controllers\Controller;
use App\Repositories\{ PollingRepository }; 
use Illuminate\Http\Request;

class PollingController extends Controller
{   
    public function __construct(PollingRepository $_PollingRepository)
    {
        $route_name = explode('.', \Route::currentRouteName());
        $this->route1 = ((isset($route_name[0])) ? $route_name[0] : (''));
        $this->route2 = ((isset($route_name[1])) ? $route_name[1] : (''));
        $this->route3 = ((isset($route_name[2])) ? $route_name[2] : (''));
        $this->polling =  $_PollingRepository;
    }

    public function index() {
        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3);
    }

    public function create() {}

    public function store(Request $request) {
        return $this->polling->storePollingResult($request);
    }

    public function show($id) {}

    public function edit($id) {}

    public function update(Request $request, $id) {}

    public function show_poll($id) {
        $data = $this->polling->getQuestion(\Crypt::decrypt($id));
        $familyMember = $this->polling->getFamilyMember(\Auth::user()->anggota_keluarga_id, \Crypt::decrypt($id));
        $answerQuestion = $this->polling->getPollingAnswerQuestion(\Crypt::decrypt($id));
        $getPollingResult = $this->polling->getPollingResult(\Auth::user()->anggota_keluarga_id, \Crypt::decrypt($id));
        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data', 'familyMember', 'answerQuestion', 'getPollingResult'));
    }

    public function editPolling(Request $request, $id) {
        if ($request->ajax()) {
            return $this->polling->getEditPollingResult($id);
        }
        return false;
    }

    public function dataTables() {
        return $this->polling->dataTablesPolling();
    }
}