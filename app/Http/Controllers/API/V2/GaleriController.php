<?php

namespace App\Http\Controllers\API\V2;

use App\Models\Gallery\Gallery;
use App\Models\Gallery\GalleryContent;
use App\Models\Program;
use App\Http\Resources\GaleryContentResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GaleriController extends Controller
{
    public function __construct(Gallery $gallery, GalleryContent $galleryContent, Program $program)
    {
        $this->gallery        = $gallery;
        $this->galleryContent = $galleryContent;
        $this->program        = $program;
    }

    public function get()
    {
        $wargaLoggedIn = auth('api')->user()->anggotaKeluarga;
        $programWarga  =  $this->program->where('rw_id', $wargaLoggedIn->rw_id)->pluck('program_id');
        $gallery = $this->gallery
                      ->whereHas('agenda', function ($q) use ($programWarga) {
                        $q->whereIn('program_id', $programWarga);
                      })
                      ->orderBy('created_at','DESC')
                      ->get();

        return response()->json(['data' => $gallery]);
    }

    public function content(Request $request)
    {
        $galleryId = $request->galeri_id;
        $galleryContent = $this->galleryContent
                               ->where('galeri_id', $galleryId)
                               ->orderBy('created_at','DESC')
                               ->get();

        return response()->json(['data' => $galleryContent]);
    }
}
