<?php

namespace App\Http\Controllers\Gallery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\GalleryContentRepository;
use App\Models\Gallery\GalleryContent;
use App\Models\Gallery\Gallery;
use App\Models\Agenda;

class GalleryContentController extends Controller
{
    public function __construct(GalleryContentRepository $_GalleryContentRepository)
    {
        $route_name  = explode('.',\Route::currentRouteName());

        $this->route1 = ((isset($route_name[0])) ? $route_name[0] : (''));
        $this->route2 = ((isset($route_name[1])) ? $route_name[1] : (''));
        $this->route3 = ((isset($route_name[2])) ? $route_name[2] : (''));

        $this->content = $_GalleryContentRepository;
    }

    public function index()
    {
        return view($this->route1.'.'.$this->route2.'.'.$this->route3);
    }

    public function create($galeri_id)
    {
        $data = Gallery::select(
            'galeri.galeri_id',
            'galeri.detail_galeri',
            'galeri.agenda_id',
        )
            ->join('agenda', 'agenda.agenda_id', 'galeri.agenda_id')
            ->where('galeri.galeri_id', $galeri_id)
            ->first();

        $resultGaleriID = $data->galeri_id;
        $resultGaleri = $data->detail_galeri;

        $agenda = Agenda::select('agenda.agenda_id', 'agenda.nama_agenda')->where('agenda.agenda_id', $data->agenda_id)->first();
        $resultAgendaID = $data->agenda_id;
        $resultAgenda = $agenda->nama_agenda;
        
        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('resultGaleri', 'resultGaleriID', 'resultAgenda', 'resultAgendaID'));
    }

    public function store(Request $request)
    {   
        return $this->content->store($request);
    }

    public function show($galeri_id, $id)
    {   
        $id = \Crypt::decryptString($id);
        $data = GalleryContent::select('galeri_konten.galeri_konten_id',
                                        'galeri_konten.file',
                                        'galeri_konten.keterangan',
                                        'galeri_konten.kategori_file',
                                        'agenda.nama_agenda as nama_agenda',
                                        'galeri.detail_galeri as detail_galeri')
        ->join('agenda', 'agenda.agenda_id', 'galeri_konten.agenda_id')
        ->join('galeri', 'galeri.galeri_id', 'galeri_konten.galeri_id')
        ->findOrFail($id);

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('data'));
    }

    public function edit($galeri_id, $id)
    {   
        $id = \Crypt::decryptString($id);
        $data = GalleryContent::select('galeri_konten.galeri_konten_id',
                                        'galeri_konten.file',
                                        'galeri_konten.keterangan',
                                        'galeri_konten.kategori_file',
                                        'galeri_konten.agenda_id',
                                        'galeri_konten.galeri_id',
                                        'agenda.nama_agenda as nama_agenda',
                                        'galeri.detail_galeri as detail_galeri')
        ->join('agenda', 'agenda.agenda_id', 'galeri_konten.agenda_id')
        ->join('galeri', 'galeri.galeri_id', 'galeri_konten.galeri_id')
        ->where('galeri_konten.galeri_id', $galeri_id)
        ->findOrFail($id);

        $galeri = Gallery::select('galeri.galeri_id', 'galeri.detail_galeri')->where('galeri.galeri_id', $galeri_id)->get();
        $resultGaleri = '<option disabled selected>Pilih Galeri</option>';
        foreach ($galeri as $myGaleri) {
            $resultGaleri .= '<option value="'.$myGaleri->galeri_id.'"'.((!empty($myGaleri->galeri_id)) ? (($myGaleri->galeri_id == $data->galeri_id) ? ('selected') : ('')) : ('')).'>'.$myGaleri->detail_galeri.'</option>';
        }

        $agenda = Agenda::select('agenda.agenda_id', 'agenda.nama_agenda')->where('agenda.agenda_id', $data->agenda_id)->get();
        $result = '<option disabled selected>Pilih Agenda</option>';
        foreach ($agenda as $myAgenda) {
            $result .= '<option value="'.$myAgenda->agenda_id.'"'.((!empty($myAgenda->agenda_id)) ? (($myAgenda->agenda_id == $data->agenda_id) ? ('selected') : ('')) : ('')).'>'.$myAgenda->nama_agenda.'</option>';
        }

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('data', 'result', 'resultGaleri'));
    }

    public function update(Request $request, $galeri_id, $id)
    {   
        $id = \Crypt::decryptString($id);
        
        return $this->content->update($request, $id);
    }

    public function destroy($galeri_id, $id)
    {
        return $this->content->delete($id);
    }

    public function dataTables($galeri_id)
    {
        return $this->content->dataTables($galeri_id);
    }
}
