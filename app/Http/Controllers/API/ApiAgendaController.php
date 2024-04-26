<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AgendaResource;
use App\Models\Agenda;
use Illuminate\Http\Request;

class ApiAgendaController extends Controller
{
    public function __construct(Agenda $_Agenda)
    {
        $this->agenda  = $_Agenda;
    }

    public function index()
    {   
        return AgendaResource::collection($this->agenda
                                               ->select('agenda.agenda_id','program.nama_program','agenda.nama_agenda'
                                                        ,'agenda.lokasi','agenda.tanggal','agenda.jam','agenda.agenda',
                                                        'agenda.image','agenda.user_updated','agenda.created_at','agenda.updated_at')
                                               ->join('program','program.program_id','agenda.program_id')
                                               ->orderBy('agenda.created_at','DESC')
                                               ->get()
                                         );
    }

    public function searchAgenda(Request $request)
    {   
        $agendas = $this->agenda
                       ->select('agenda.agenda_id','program.nama_program','agenda.nama_agenda'
                                ,'agenda.lokasi','agenda.tanggal','agenda.jam','agenda.agenda',
                                'agenda.image','agenda.user_updated','agenda.created_at','agenda.updated_at')
                       ->join('program','program.program_id','agenda.program_id')
                       ->where('agenda.nama_agenda','ILIKE','%'.$request->nama_agenda.'%')
                       ->orderBy('agenda.created_at','DESC')
                       ->get();

        $result = [];

        foreach($agendas as $key => $val){

            $agenda['nama_program'] = $val->nama_program;
            $agenda['nama_agenda'] = $val->nama_agenda;
            $agenda['lokasi'] = $val->lokasi;
            $agenda['tanggal'] = $val->tanggal;
            $agenda['jam'] = $val->jam;
            $agenda['agenda'] = $val->agenda;
            $agenda['image'] = asset('upload/agenda/'.$val->image);

            array_push($result,$agenda);
        }

        

        return response()->json(compact('result'));
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
