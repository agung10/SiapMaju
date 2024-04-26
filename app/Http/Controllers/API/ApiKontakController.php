<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\KontakResource;
use App\Models\Kontak;

class ApiKontakController extends Controller
{
    public function __construct(Kontak $_Kontak)
    {
        $this->kontak = $_Kontak;
    } 

    public function index()
    {
        return KontakResource::collection($this->kontak->select('*')->get());
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
