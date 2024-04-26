<?php

namespace App\Http\Controllers\API;

use App\Models\Gallery\GalleryContent;
use App\Http\Resources\GaleryContentResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiGaleryContentController extends Controller
{
    public function __construct(GalleryContent $_GalleryContent)
    {
        $this->galeryContent = $_GalleryContent;
    }

    public function index()
    {
        return GaleryContentResource::collection($this->galeryContent
                                                      ->select('*')
                                                      ->join('galeri','galeri.galeri_id','galeri_konten.galeri_id')
                                                      ->leftJoin('agenda','agenda.agenda_id','galeri_konten.agenda_id')
                                                      ->orderBy('galeri_konten.created_at','DESC')
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
