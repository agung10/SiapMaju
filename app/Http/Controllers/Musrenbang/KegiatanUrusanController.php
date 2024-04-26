<?php

namespace App\Http\Controllers\Musrenbang;

use App\Http\Controllers\Controller;
use App\Repositories\Musrenbang\KegiatanUrusanRepository;
use Illuminate\Http\Request;

class KegiatanUrusanController extends Controller
{
    public function __construct(KegiatanUrusanRepository $kegiatanUrusanRepo)
    {
        $this->kegiatanUrusanRepo = $kegiatanUrusanRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Musrenbang.master.kegiatan_urusan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Musrenbang.master.kegiatan_urusan.create');
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
        $input['user_create'] = \Auth::user()->user_id;
        $input['user_update'] = \Auth::user()->user_id;
        $input['created_at'] = date('Y-m-d');
        $input['updated_at'] = date('Y-m-d');

        \DB::beginTransaction();
        try {
            $this->kegiatanUrusanRepo->create($input);
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
        $data = $this->kegiatanUrusanRepo->show(\Crypt::decryptString($id));
        return view('Musrenbang.master.kegiatan_urusan.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->kegiatanUrusanRepo->show(\Crypt::decryptString($id));
        return view('Musrenbang.master.kegiatan_urusan.edit', compact('data'));
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
        $data = $this->kegiatanUrusanRepo->show(\Crypt::decryptString($id));
        $input = $request->except('proengsoft_jsvalidation');
        $input['user_update'] = \Auth::user()->user_id;
        $input['updated_at'] = date('Y-m-d');

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
        try {
            $this->kegiatanUrusanRepo->destroy($id);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function dataTables()
    {
        return $this->kegiatanUrusanRepo->dataTables();
    }
}