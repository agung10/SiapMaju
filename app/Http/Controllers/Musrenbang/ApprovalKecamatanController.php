<?php

namespace App\Http\Controllers\Musrenbang;

use App\Http\Controllers\Controller;
use App\Repositories\Master\RWRepository;
use App\Repositories\Musrenbang\{MenuUrusanRepository, BidangUrusanRepository, KegiatanUrusanRepository, UsulanUrusanRepository};
use Illuminate\Http\Request;

class ApprovalKecamatanController extends Controller
{
    public function __construct(UsulanUrusanRepository $usulanUrusanRepo, MenuUrusanRepository $menuUrusanRepo, BidangUrusanRepository $bidangUrusanRepo, KegiatanUrusanRepository $kegiatanUrusanRepo, RWRepository $rwRepo)
    {
        $this->usulanUrusanRepo = $usulanUrusanRepo;
        $this->menuUrusanRepo = $menuUrusanRepo;
        $this->bidangUrusanRepo = $bidangUrusanRepo;
        $this->kegiatanUrusanRepo = $kegiatanUrusanRepo;
        $this->rwRepo = $rwRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Musrenbang.kecamatan_approval.index');
    }

        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->usulanUrusanRepo->find(\Crypt::decryptString($id));

        if ($data->status_usulan == 1) {
            $status = 'Approved RW';
        } else if ($data->status_usulan == 2) {
            $status = 'Approved Kelurahan';
        } else if ($data->status_usulan == 3) { 
            $status = 'Approved Kecamatan';
        } else if ($data->status_usulan == 4) {
            $status = 'Approved Walikota';
        }

        return view('Musrenbang.kecamatan_approval.show', compact('data', 'status'));
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
        $data = $this->usulanUrusanRepo->show(\Crypt::decryptString($id));
        $input = $request->except('proengsoft_jsvalidation');
        $input['user_update'] = \Auth::user()->user_id;
        $input['updated_at'] = date('Y-m-d');
        $input['status_usulan'] = 3; //approved kecamatan

        \DB::beginTransaction();
        try {
            $data->update($input);
            \DB::commit();

            return redirect()->route('Musrenbang.Approval-Kecamatan.index')->with('success', 'Approved Kecamatan!');
        } catch (\Exception $e) {
            \DB::rollback();
            abort(403, $e->getMessage());
        }
    }

    public function dataTables()
    {
        return $this->usulanUrusanRepo->kecamatanDataTables();
    }
}
