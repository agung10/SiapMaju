<?php

namespace App\Http\Controllers\API\V2;

use App\Models\Program;
use App\Http\Resources\ProgramResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function __construct(Program $program)
    {
        $this->user    = auth('api')->user();
        $this->program = $program;
    }

    public function get()
    {
        $anggotaKeluarga = $this->user->anggotaKeluarga;
        $program = $this->program->where('rw_id', $anggotaKeluarga->rw_id)->orderBy('created_at','DESC')->get();

        return ProgramResource::collection($program);
    }

    public function searchProgram(Request $request)
    {   
        $anggotaKeluarga = $this->user->anggotaKeluarga;
        $programs = $this->program
                        ->select('program.nama_program','program.program','program.pic','program.tanggal', 'program.rw_id', 'program.image')
                        ->where('program.nama_program','ILIKE','%'.$request->nama_kegiatan.'%')
                        ->where('rw_id', $anggotaKeluarga->rw_id)
                        ->orderBy('created_at','DESC')
                        ->get();

        $result = [];
        
        foreach($programs as $key => $val){

            $program['nama_program'] = $val->nama_program;
            $program['program'] = $val->program;
            $program['pic'] = $val->pic;
            $program['tanggal'] = $val->tanggal;
            $program['image'] = asset('upload/program/'.$val->image);

            array_push($result,$program);
        }   
 
        return response()->json(compact('result'));
    }
}
