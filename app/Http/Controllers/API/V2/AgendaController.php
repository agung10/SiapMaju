<?php

namespace App\Http\Controllers\API\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\AgendaResource;
use App\Models\Agenda;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function __construct(Agenda $agenda)
    {
        $this->user   = auth('api')->user();
        $this->agenda = $agenda;
    }

    public function get()
    {   
        $userLoggedIn = $this->user->anggotaKeluarga;
        $agenda = $this->agenda
                       ->select('agenda.agenda_id','program.nama_program','program.rw_id', 'agenda.nama_agenda'
                                ,'agenda.lokasi','agenda.tanggal','agenda.jam','agenda.agenda',
                                'agenda.image','agenda.user_updated','agenda.created_at','agenda.updated_at')
                       ->join('program','program.program_id','agenda.program_id')
                       ->where('program.rw_id', $userLoggedIn->rw_id)
                       ->orderBy('agenda.created_at','DESC')
                       ->get();

        return AgendaResource::collection($agenda);
    }

    public function searchAgenda(Request $request)
    {   
        $userLoggedIn = $this->user->anggotaKeluarga;
        $agendas = $this->agenda
                       ->select('agenda.agenda_id','program.nama_program','program.rw_id', 'agenda.nama_agenda'
                                ,'agenda.lokasi','agenda.tanggal','agenda.jam','agenda.agenda',
                                'agenda.image','agenda.user_updated','agenda.created_at','agenda.updated_at')
                       ->join('program','program.program_id','agenda.program_id')
                       ->where('agenda.nama_agenda','ILIKE','%'.$request->nama_agenda.'%')
                       ->where('program.rw_id', $userLoggedIn->rw_id)
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

}
