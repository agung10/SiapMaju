<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\HeaderResource;
use App\Models\Header;

class ApiHeaderController extends Controller
{   
    public function __construct(Header $_Header)
    {
        $this->header =  $_Header;
    }

    public function index()
    {   
        return HeaderResource::collection($this->header->select('*')->get());
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
