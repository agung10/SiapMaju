<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kajian\Kajian;
use App\Http\Resources\KajianResource;
use Illuminate\Http\Request;

class ApiKajianController extends Controller
{   
    public function __construct(Kajian $_Kajian)
    {
        $this->kajian = $_Kajian;
    }

    public function index()
    {
        return KajianResource::collection($this->kajian
                                               ->select('kajian.kajian_id',
                                                        'kajian.kajian',
                                                        'kajian.judul',
                                                        'kajian.author',
                                                        'kajian.upload_materi_1',
                                                        'kajian.upload_materi_2',
                                                        'kajian.upload_materi_3',
                                                        'kajian.upload_materi_4',
                                                        'kajian.upload_materi_5',
                                                        'kajian.image',
                                                        'kajian.date_updated',
                                                        'kajian.user_updated',
                                                        'kajian.created_at',
                                                        'kajian.updated_at',
                                                        'kat_kajian.kategori')
                                               ->join('kat_kajian','kat_kajian.kat_kajian_id','kajian.kat_kajian_id')
                                               ->orderBy('kajian.created_at','DESC')
                                               ->get());
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
