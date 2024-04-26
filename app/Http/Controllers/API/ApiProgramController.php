<?php

namespace App\Http\Controllers\API;

use App\Models\Program;
use App\Http\Resources\ProgramResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiProgramController extends Controller
{
    public function __construct(Program $_Program)
    {
        $this->program = $_Program;
    }

    public function index()
    {
        return ProgramResource::collection($this->program
                                                ->select('*')
                                                ->orderBy('created_at','DESC')
                                                ->get());
    }

    public function searchProgram(Request $request)
    {   
       
        $programs = $this->program
                        ->select('program.nama_program','program.program','program.pic','program.tanggal','program.image')
                        ->where('program.nama_program','ILIKE','%'.$request->nama_kegiatan.'%')
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
