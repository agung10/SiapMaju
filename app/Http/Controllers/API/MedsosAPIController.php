<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MedsosAPIController extends Controller
{
    public function index()
    {
        $query  = \DB::table('medsos')
            ->select('medsos.medsos_id', 'medsos.nama', 'medsos.icon')
            ->get();

        $result = [];

        foreach ($query as $key => $val) {
            $kategori['id'] = $val->medsos_id;
            $kategori['nama'] = $val->nama;
            $kategori['icon'] = asset('uploaded_files/medsos/' . $val->icon);

            array_push($result, $kategori);
        }
        return response()->json(compact('result'));
    }
}
