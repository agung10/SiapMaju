<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laporan\KatLaporan;
use App\Helpers\helper;

class KategoriLaporanController extends Controller
{
    public function index()
    {
    	return view('Laporan.KategoriLaporan.index');
    }

    public function create()
    {
    	return view('Laporan.KategoriLaporan.create');
    }

    public function store(Request $request)
    {
    	$input = $request->except('proengsoft_jsvalidation');
       
        \DB::beginTransaction();

        try{
            KatLaporan::create($input);
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
    	$data = KatLaporan::findOrFail($id);

    	return view('Laporan.KategoriLaporan.show', compact('data'));
    }

    public function edit($id)
    {   
        $id = \Crypt::decryptString($id);
    	$data = KatLaporan::findOrFail($id);

    	return view('Laporan.KategoriLaporan.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {   
        $id = \Crypt::decryptString($id);
        
    	$data = KatLaporan::findOrFail($id);
    	$input = $request->except('proengsoft_jsvalidation');
    	$input['updated_at'] = date('Y-m-d H:i:s');
    	$input['user_updated'] = \Auth::user()->user_id;

        \DB::beginTransaction();

        try{
            $data->update($input);
            \DB::commit();

            return response()->json(['status' => 'success']);

        }catch(\Exception $e){
            \DB::rollback();
            throw $e;
        }
   	}

   	public function destroy($id)
   	{
   		$data = KatLaporan::findOrFail($id);

   		try {
   			KatLaporan::destroy($id);

   			return response()->json(['status' => 'success']);
   		} catch (Exception $e) {
   			\DB::rollback();
   			throw $e;
   		}
   	}

    public function dataTables()
    {
        $datatableButtons = method_exists(new KatLaporan, 'datatableButtons') ? KatLaporan::datatableButtons() : ['show', 'edit', 'destroy'];
    	$data = \DB::table('kat_laporan')
		    	->select('kat_laporan.kat_laporan_id', 'kat_laporan.nama_kategori')
		    	->orderBy('created_at', 'desc')
		    	->get();
    	return \DataTables::of($data)
    	->addIndexColumn()
    	->addColumn('action', function($data) use ($datatableButtons) {
            return view('partials.buttons.cust-datatable',[
                'show'         => in_array("show", $datatableButtons ) ? route('Laporan.KategoriLaporan'.'.show', \Crypt::encryptString($data->kat_laporan_id)) : null,
                'edit'         => in_array("edit", $datatableButtons ) ? route('Laporan.KategoriLaporan'.'.edit', \Crypt::encryptString($data->kat_laporan_id)) : null,
                'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $data->kat_laporan_id : null
            ]);
        })
    	->rawColumns(['action'])
    	->make(true);
    }
}
