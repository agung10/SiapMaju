<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas\Fasilitas;
use App\Http\Resources\FasilitasResource;
use Illuminate\Http\Request;

class ApiFasilitasController extends Controller
{   
    public function __construct(Fasilitas $_Fasilitas)
    {
        $this->fasilitas = $_Fasilitas;
    }

    public function index()
    {
        return FasilitasResource::collection($this->fasilitas->select('*')->orderBy('created_at','DESC')->get());
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
