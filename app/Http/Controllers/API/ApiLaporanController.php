<?php

namespace App\Http\Controllers\API;

use App\Models\Laporan\Laporan;
use App\Http\Resources\LaporanResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiLaporanController extends Controller
{
    public function __construct(Laporan $_Laporan)
    {
        $this->laporan = $_Laporan;
    }

    public function index()
    {
       return LaporanResource::collection($this->laporan
                                                ->select('laporan.laporan_id','kat_laporan.nama_kategori','laporan.laporan',
                                                         'laporan.detail_laporan','laporan.upload_materi','laporan.image',
                                                         'agenda.nama_agenda','laporan.user_updated','laporan.created_at',
                                                         'laporan.updated_at')
                                                ->join('kat_laporan','kat_laporan.kat_laporan_id','laporan.kat_laporan_id')
                                                ->leftJoin('agenda','agenda.agenda_id','laporan.agenda_id')
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
