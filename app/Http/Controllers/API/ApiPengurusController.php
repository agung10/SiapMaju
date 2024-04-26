<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tentang\Pengurus;
use App\Http\Resources\PengurusResource;

class ApiPengurusController extends Controller
{   
    public function __construct(Pengurus $_Pengurus)
    {
        $this->pengurus = $_Pengurus;
    }

    public function index()
    {
        return PengurusResource::collection($this->pengurus->select('*')
                                                           ->join('kat_pengurus','kat_pengurus.kat_pengurus_id','pengurus.kat_pengurus_id')
                                                           ->get()
                                           );
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
