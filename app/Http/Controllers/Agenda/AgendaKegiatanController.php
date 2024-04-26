<?php

namespace App\Http\Controllers\Agenda;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\helper;
use App\Http\Requests\Agenda\AgendaKegiatanRequest;
use App\Models\Agenda;
use App\Models\Program;

class AgendaKegiatanController extends Controller
{
     public function __construct()
    {
        $route_name  = explode('.',\Route::currentRouteName());

        $this->route1 = ((isset($route_name[0])) ? $route_name[0] : (''));
        $this->route2 = ((isset($route_name[1])) ? $route_name[1] : (''));
        $this->route3 = ((isset($route_name[2])) ? $route_name[2] : (''));

    }

    public function index()
    {
        return view($this->route1.'.'.$this->route2.'.'.$this->route3);
    }

    public function create()
    {
        $program = Program::select('program.program_id', 'program.nama_program')->get();

        $result = '<option disabled selected></option>';
        foreach ($program as $data) {
            $result .= '<option value="'.$data->program_id.'">'.$data->nama_program.'</option>';
        }

        $katSumberBiaya = helper::select('kat_sumber_biaya','nama_sumber'); 

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('result', 'katSumberBiaya'));
    }

    public function store(Request $request)
    {
        $input = $request->except('proengsoft_jsvalidation');

        \DB::beginTransaction();

        try{

            if($request->hasFile('image')){
                $input['image'] = 'agenda'.rand().'.'.$request->image->getClientOriginalExtension();
                $request->image->move(public_path('upload/agenda'),$input['image']);
            }

            Agenda::create($input);
            \DB::commit();

            return response()->json(['status' => 'success']);

        }catch(\Exception $e){
            \DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {   
        $id = \Crypt::decryptString($id);

        $data = Agenda::select( 'agenda.agenda_id',
                                'agenda.nama_agenda',
                                'agenda.tanggal',
                                'agenda.jam',
                                'agenda.lokasi',
                                'agenda.image',
                                'agenda.agenda',
                                'program.nama_program as nama_program',
                                'agenda.nilai',
                                'kat_sumber_biaya.nama_sumber'
                                )
                ->leftjoin('program', 'program.program_id', 'agenda.program_id')
                ->leftjoin('kat_sumber_biaya', 'kat_sumber_biaya.kat_sumber_biaya_id', 'agenda.kat_sumber_biaya_id')
                ->findOrFail($id);

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('data'));
    }

    public function edit($id)
    {   
        $id = \Crypt::decryptString($id);

        $data = Agenda::select( 'agenda.agenda_id',
                                'agenda.nama_agenda',
                                'agenda.tanggal',
                                'agenda.jam',
                                'agenda.lokasi',
                                'agenda.image',
                                'agenda.agenda',
                                'agenda.nilai',
                                'agenda.program_id',
                                'agenda.kat_sumber_biaya_id')
                ->findOrFail($id);

        $program = Program::select('program.program_id', 'program.nama_program')->get();

        $result = '<option disabled selected></option>';
        foreach ($program as $myData) {
            $result .= '<option value="'. $myData->program_id .'" '. ((!empty($myData->program_id)) ? (($data->program_id == $myData->program_id) ? ('selected') : ('')) : ('')) . '>'. $myData->nama_program .'</option>';
        }

        $kategori = \DB::table('kat_sumber_biaya')->get(); 
        $katSumberBiaya = '<option disabled selected></option>';
        foreach ($kategori as $res) {
            $katSumberBiaya .= '<option value="'. $res->kat_sumber_biaya_id .'" '. ((!empty($res->kat_sumber_biaya_id)) ? (($res->kat_sumber_biaya_id == $data->kat_sumber_biaya_id) ? ('selected') : ('')) : ('')) . '>'. $res->nama_sumber .'</option>';
        }

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('data', 'result', 'katSumberBiaya'));
    }

    public function update(Request $request, $id)
    {   
        $id = \Crypt::decryptString($id);
        $data = Agenda::findOrFail($id);
        $input = $request->except('proengsoft_jsvalidation');
        $input['user_updated'] = \Auth::user()->user_id;
        $input['updated_at'] = date('Y-m-d H:i:s');

        \DB::beginTransaction();

        try {
            if($request->hasFile('image')){
                $input['image'] = 'agenda'.rand().'.'.$request->image->getClientOriginalExtension();
                $request->image->move(public_path('upload/agenda/'),$input['image']);

                \File::delete(public_path('upload/agenda/'.$data->image));
            }
            $data->update($input);

            \DB::commit();

            return response()->json(['status' => 'success']);

        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        $data = Agenda::findOrFail($id);

        try {
            if($data->imgae){
                \File::delete(public_path('upload/agenda/'.$data->image));
            }
            Agenda::destroy($id);

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function dataTables()
    {
        $datatableButtons = method_exists(new Agenda, 'datatableButtons') ? Agenda::datatableButtons() : ['show', 'edit', 'destroy'];
        $data = Agenda::select( 'agenda.agenda_id',
                                'agenda.nama_agenda',
                                'agenda.tanggal',
                                'agenda.jam',
                                'agenda.lokasi',
                                'agenda.image')
                ->orderBy('created_at','desc')
                ->get();

        return \DataTables::of($data)
        ->addIndexColumn()
        ->editColumn('image', function($row){
            return view('layouts.partials.image_column', ['src' => \helper::loadImgUpload('agenda', $row->image)]);
        })
        ->addColumn('action', function($data) use ($datatableButtons) {
            return view('partials.buttons.cust-datatable',[
                'show'         => in_array("show", $datatableButtons ) ? route('Agenda.AgendaKegiatan'.'.show', \Crypt::encryptString($data->agenda_id)) : null,
                'edit'         => in_array("edit", $datatableButtons ) ? route('Agenda.AgendaKegiatan'.'.edit', \Crypt::encryptString($data->agenda_id)) : null,
                'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $data->agenda_id : null
            ]);
        })
        ->editColumn('tanggal',function($row){
            return [
                'display' => \helper::tglIndo($row->tanggal),
                'raw' => $row->tanggal
            ];
        })
        ->editColumn('jam', function($data){
            return \Carbon\Carbon::createFromFormat('H:i:s',$data->jam)->format('H:i');
        })
        ->rawColumns(['image', 'action'])
        ->make('true');
    }
}
