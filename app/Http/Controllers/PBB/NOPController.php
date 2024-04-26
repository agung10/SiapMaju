<?php

namespace App\Http\Controllers\PBB;

use App\Http\Controllers\Controller;
use App\Models\Master\Blok;
use Illuminate\Http\Request;

class NOPController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pbb.nop.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $data = Blok::findOrFail($id);
        $isAdmin = \helper::checkUserRole('admin');

        $currentUser = \DB::table('users')
            ->select('keluarga.blok_id')
            ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
            ->leftJoin('keluarga', 'keluarga.keluarga_id', 'anggota_keluarga.keluarga_id')
            ->where('users.user_id', \Auth::user()->user_id)
            ->first();

        $blok = \DB::table('blok')->select('blok_id', 'nama_blok')
            ->when($isAdmin == false, function ($query) use ($currentUser) {
                $query->where('blok.blok_id', $currentUser->blok_id);
            })
            ->pluck('nama_blok', 'blok_id');
        $resultBlok = '<option disabled selected></option>';
        foreach ($blok as $blok_id => $nama_blok) {
            $resultBlok .= '<option value="' . $blok_id . '"' . ((!empty($blok_id)) ? ((!empty($blok_id == $data->blok_id)) ? ('selected') : ('')) : ('')) . '>' . $nama_blok . '</option>';
        }
        return view('pbb.nop.edit', compact('data', 'resultBlok'));
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
        $data = Blok::findOrFail($id);

        $input = $request->except('proengsoft_jsvalidation');
        $input['nop'] = $request->nop;
        
        \DB::beginTransaction();
        try {
            $data->update($input);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
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
        //
    }

    public function dataTables()
    {
        $currentUser = \DB::table('users')
            ->select('keluarga.blok_id')
            ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
            ->leftJoin('keluarga', 'keluarga.keluarga_id', 'anggota_keluarga.keluarga_id')
            ->where('users.user_id', \Auth::user()->user_id)
            ->first();

        $isAdmin = \helper::checkUserRole('admin');

        $blok = \DB::table('blok')->select('blok.blok_id', 'blok.nama_blok', 'blok.nop', 'blok.updated_at')
            ->join('keluarga', 'keluarga.blok_id', 'blok.blok_id')
            ->when($isAdmin == false, function ($query) use ($currentUser) {
                $query->where('blok.blok_id', $currentUser->blok_id);
            });

        $datatableButtons = method_exists(new Blok, 'datatableButtons') ? Blok::datatableButtons() : ['show', 'edit', 'destroy'];
        return \DataTables::of($blok)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'edit' => in_array("edit", $datatableButtons) ? route('pbb.nop' . '.edit', \Crypt::encryptString($data->blok_id)) : null,
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
