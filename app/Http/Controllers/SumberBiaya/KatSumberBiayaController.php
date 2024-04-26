<?php

namespace App\Http\Controllers\SumberBiaya;

use App\Http\Controllers\Controller;
use App\Models\SumberBiaya\KatSumberBiaya;
use Illuminate\Http\Request;

class KatSumberBiayaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('SumberBiaya.KatSumberBiaya.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('SumberBiaya.KatSumberBiaya.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except('proengsoft_jsvalidation');

        \DB::beginTransaction();
        try {
            KatSumberBiaya::create($input);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = \Crypt::decryptString($id);
        $data = KatSumberBiaya::findOrFail($id);

        return view('SumberBiaya.KatSumberBiaya.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = \Crypt::decryptString($id);
        $data = KatSumberBiaya::findOrFail($id);

        return view('SumberBiaya.KatSumberBiaya.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = \Crypt::decryptString($id);
        $data = KatSumberBiaya::findOrFail($id);
        $input = $request->except('proengsoft_jsvalidation');

        \DB::beginTransaction();
        try {
            $data->update($input);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = KatSumberBiaya::findOrFail($id);

        try {
            KatSumberBiaya::destroy($id);

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function dataTables()
    {
        $datatableButtons = method_exists(new KatSumberBiaya, 'datatableButtons') ? KatSumberBiaya::datatableButtons() : ['show', 'edit', 'destroy'];
    	$data = \DB::table('kat_sumber_biaya')
		    	->select('kat_sumber_biaya.kat_sumber_biaya_id', 'kat_sumber_biaya.nama_sumber')
		    	->orderBy('created_at', 'desc')
		    	->get();
    	return \DataTables::of($data)
    	->addIndexColumn()
    	->addColumn('action', function($data) use ($datatableButtons) {
            return view('partials.buttons.cust-datatable',[
                'show'         => in_array("show", $datatableButtons ) ? route('SumberBiaya.KatSumberBiaya'.'.show', \Crypt::encryptString($data->kat_sumber_biaya_id)) : null,
                'edit'         => in_array("edit", $datatableButtons ) ? route('SumberBiaya.KatSumberBiaya'.'.edit', \Crypt::encryptString($data->kat_sumber_biaya_id)) : null,
                'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $data->kat_sumber_biaya_id : null
            ]);
        })
    	->rawColumns(['action'])
    	->make(true);
    }
}
