<?php

namespace App\Http\Controllers\Gallery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\GalleryRepository;
use App\Models\Gallery\Gallery;
use App\Models\Agenda;
use App\Helpers\helper;
use DB;

class GalleryController extends Controller
{
    public function __construct(GalleryRepository $_GalleryRepository)
    {
        $route_name  = explode('.', \Route::currentRouteName());

        $this->route1 = ((isset($route_name[0])) ? $route_name[0] : (''));
        $this->route2 = ((isset($route_name[1])) ? $route_name[1] : (''));
        $this->route3 = ((isset($route_name[2])) ? $route_name[2] : (''));

        $this->gallery = $_GalleryRepository;
    }

    public function index()
    {
        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3);
    }

    public function create()
    {
        $agenda = Agenda::select('agenda.agenda_id', 'agenda.nama_agenda')->get();

        $result = '<option disabled selected>Pilih Agenda</option>';
        foreach ($agenda as $myAgenda) {
            $result .= '<option value="' . $myAgenda->agenda_id . '">' . $myAgenda->nama_agenda . '</option>';
        }
        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('result'));
    }

    public function store(Request $request)
    {
        return $this->gallery->store($request);
    }

    public function show($id)
    {
        $id = \Crypt::decryptString($id);
        $data = Gallery::select(
            'galeri.detail_galeri',
            'galeri.image_cover',
            'agenda.nama_agenda as nama_agenda'
        )
            ->join('agenda', 'agenda.agenda_id', 'galeri.agenda_id')
            ->findOrFail($id);

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data'));
    }

    public function edit($id)
    {
        $id = \Crypt::decryptString($id);
        $data = Gallery::select(
            'galeri.galeri_id',
            'galeri.detail_galeri',
            'galeri.image_cover',
            'galeri.agenda_id',
            'agenda.nama_agenda as nama_agenda'
        )
            ->join('agenda', 'agenda.agenda_id', 'galeri.agenda_id')
            ->findOrFail($id);

        $agenda = Agenda::select('agenda.agenda_id', 'agenda.nama_agenda')->get();

        $result = '<option disabled selected>Pilih Agenda</option>';
        foreach ($agenda as $myAgenda) {
            $result .= '<option value="' . $myAgenda->agenda_id . '" ' . ((!empty($myAgenda->agenda_id)) ? (($data->agenda_id == $myAgenda->agenda_id) ? ('selected') : ('')) : ('')) . '>' . $myAgenda->nama_agenda . '</option>';
        }

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('data', 'result'));
    }

    public function update(Request $request, $id)
    {
        $id = \Crypt::decryptString($id);

        return $this->gallery->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->gallery->delete($id);
    }

    public function dataTables()
    {
        return $this->gallery->dataTables();
    }

    public function storeGalleryTemp(Request $request)
    {
    }

    public function deleteGalleryTemp(Request $request)
    {
    }
}
