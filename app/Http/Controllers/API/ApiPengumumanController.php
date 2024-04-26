<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PengumumanResource;
use App\Models\Pengumuman;

class ApiPengumumanController extends Controller
{
    public function __construct(Pengumuman $_Pengumuman)
    {
        $this->pengumuman = $_Pengumuman;
    }

    public function index(Request $request)
    {
        $pengumuman = $this->pengumuman->select('*')->orderBy('created_at','DESC');
        $pengumuman = $request->limit ? $pengumuman->limit($request->limit) : $pengumuman;

        return PengumumanResource::collection($pengumuman->get());
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
