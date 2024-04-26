<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\GaleryResource;
use App\Models\Gallery\Gallery;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiGaleryController extends Controller
{

    public function __construct(Gallery $_Gallery)
    {
        $this->galery = $_Gallery;
    }

    public function index()
    {
        return GaleryResource::collection($this->galery
                                               ->select('*')
                                               ->leftJoin('agenda','agenda.agenda_id','galeri.agenda_id')
                                               ->orderBy('galeri.created_at','DESC')
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
