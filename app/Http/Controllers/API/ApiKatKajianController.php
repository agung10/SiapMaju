<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kajian\Kategori;
use App\Http\Resources\KatKajianResource;

class ApiKatKajianController extends Controller
{

    public function __construct(Kategori $_Kategori)
    {
        $this->kategori = $_Kategori;
    }

    public function index()
    {
        return KatKajianResource::collection($this->kategori->select('*')->get());
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
