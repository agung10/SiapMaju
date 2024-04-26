<?php

namespace App\Http\Controllers\Tentang\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tentang\KatPengurus;
use App\Helpers\helper;

class KategoriPengurusController extends Controller
{
  
    public function index()
    {
        return view('Tentang.Pengurus.KategoriPengurus.index');
    }

    public function create()
    {
        return view('Tentang.Pengurus.KategoriPengurus.create');
    }

    public function store(Request $request)
    {
        $input = $request->except('proengsoft_jsvalidation');
        $transaction = false;

        $rules = ['nama_kategori' => 'required'];

        $validator = helper::validation($request->all(),$rules);
        \DB::beginTransaction();

        if($validator->fails()){
            return response()->json(['status' => 'failed', 'errors' => $validator->getMessageBag()->toArray()]);
        }
        try{
            KatPengurus::create($input);
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
        $data = KatPengurus::findOrFail($id);

        return view('Tentang.Pengurus.KategoriPengurus.show', compact('data'));
    }

    public function edit($id)
    {   
        $id = \Crypt::decryptString($id);

        $data = KatPengurus::findOrFail($id);

        return view('Tentang.Pengurus.KategoriPengurus.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {   
        $id = \Crypt::decryptString($id);
        
        $model = KatPengurus::findOrFail($id);
        $input = $request->except('proengsoft_jsvalidation');
        $input['updated_at'] = date('Y-m-d H:i:s');
        $input['user_updated'] = \Auth::user()->user_id;

        $rules = ['nama_kategori' => 'required'];

        $validator = helper::validation($request->all(),$rules);

        if($validator->fails()){
            return response()->json(['status' => 'failed','errors' => $validator->getMessageBag()->toArray()]);
        }

        \DB::beginTransaction();

        try{
            $model->update($input);

            \DB::commit();

            return response()->json(['status' => 'success']);


        }catch(\Exception $e){
            \DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        $model = KatPengurus::findOrFail($id);
        
        try{
            KatPengurus::destroy($id);

            return response()->json(['status' => 'success']);

        }catch(\Exception $e){
            \DB::rollback();
            throw $e;
        }
    }

    public function dataTables()
    {
        $datatableButtons = method_exists(new KatPengurus, 'datatableButtons') ? KatPengurus::datatableButtons() : ['show', 'edit', 'destroy'];
        $data = \DB::table('kat_pengurus')
                ->select('kat_pengurus.kat_pengurus_id', 'kat_pengurus.nama_kategori')
                ->orderBy('created_at', 'desc')
                ->get();
        return \DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($data) use ($datatableButtons) {
            return view('partials.buttons.cust-datatable',[
                'show'         => in_array("show", $datatableButtons ) ? route('Tentang.KategoriPengurus'.'.show', \Crypt::encryptString($data->kat_pengurus_id)) : null,
                'edit'         => in_array("edit", $datatableButtons ) ? route('Tentang.KategoriPengurus'.'.edit', \Crypt::encryptString($data->kat_pengurus_id)) : null,
                'ajax_destroy' => in_array("destroy", $datatableButtons ) ? \Crypt::encryptString($data->kat_pengurus_id ): null
            ]);
        })
        ->rawColumns(['action'])
        ->make(true);
    }
}
