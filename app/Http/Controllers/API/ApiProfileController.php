<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ProfileResource;
use App\Models\Tentang\Profile;

class ApiProfileController extends Controller
{
    public function __construct(Profile $_Profile)
    {
        $this->profile = $_Profile;
    }   

    public function index()
    {
       return ProfileResource::collection($this->profile->select('*')->get());
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
