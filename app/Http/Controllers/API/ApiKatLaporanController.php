<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\KatLaporanResource;
use App\Models\Laporan\KatLaporan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiKatLaporanController extends Controller
{   
    public function __construct(KatLaporan $_KatLaporan)
    {
        $this->katLaporan = $_KatLaporan;
    }

    public function index()
    {
        return KatLaporanResource::collection($this->katLaporan->select('*')->get());
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
