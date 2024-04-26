<?php

namespace App\Http\Controllers\Musrenbang;

use App\Http\Controllers\Controller;
use App\Repositories\Master\RWRepository;
use App\Repositories\Musrenbang\{MenuUrusanRepository, BidangUrusanRepository, KegiatanUrusanRepository, UsulanUrusanRepository};
use Illuminate\Http\Request;

class UsulanUrusanController extends Controller
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
        return view('Musrenbang.usulan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data_jenis_usulan = $this->menuUrusanRepo->makeDropdown('nama_jenis');
        $data_bidang = $this->bidangUrusanRepo->makeDropdown('nama_bidang');
        $data_kegiatan = $this->kegiatanUrusanRepo->makeDropdown('nama_kegiatan');
        $data_rw = \DB::table('rw')->where('kelurahan_id', 1)->orderBy('rw', 'ASC')->pluck('rw', 'rw_id');
        
        $checkRole = \helper::checkUserRole('all');
        
        $data_user = \DB::table('users')
                        ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
                        ->when($checkRole['isAdmin'],function($query){
                            $query->where('users.is_admin', true);
                        })
                        ->when($checkRole['isRw'],function($query){
                            $query->where('anggota_keluarga.is_rw', true);
                        })
                        ->where('users.user_id', \Auth::user()->user_id)->get();
        
        return view('Musrenbang.usulan.create', compact('data_jenis_usulan', 'data_bidang', 'data_kegiatan', 'data_rw', 'data_user'));
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
        $input['status_usulan'] = 1; //approved rw

        \DB::beginTransaction();
        try {
            if ($request->hasFile('gambar_1')) {
                $input['gambar_1'] = 'gambar1' . rand() . '.' . $request->gambar_1->getClientOriginalExtension();
                $request->gambar_1->move(public_path('uploaded_files/musrenbang'), $input['gambar_1']);
            }
            if ($request->hasFile('gambar_2')) {
                $input['gambar_2'] = 'gambar2' . rand() . '.' . $request->gambar_2->getClientOriginalExtension();
                $request->gambar_2->move(public_path('uploaded_files/musrenbang'), $input['gambar_2']);
            }
            if ($request->hasFile('gambar_3')) {
                $input['gambar_3'] = 'gambar3' . rand() . '.' . $request->gambar_3->getClientOriginalExtension();
                $request->gambar_3->move(public_path('uploaded_files/musrenbang'), $input['gambar_3']);
            }

            $this->usulanUrusanRepo->create($input);
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

        return view('Musrenbang.usulan.show', compact('data', 'status'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->usulanUrusanRepo->show(\Crypt::decryptString($id));
        $data_jenis_usulan = $this->menuUrusanRepo->makeDropdown('nama_jenis');
        $data_bidang = $this->bidangUrusanRepo->makeDropdown('nama_bidang');
        $data_kegiatan = $this->kegiatanUrusanRepo->makeDropdown('nama_kegiatan');
        $data_rw = \DB::table('rw')->where('kelurahan_id', 1)->orderBy('rw', 'ASC')->pluck('rw', 'rw_id');
        $data_rt = \DB::table('rt')->where('rw_id', $data->rw_id)->orderBy('rt', 'ASC')->pluck('rt', 'rt_id');
        
        $checkRole = \helper::checkUserRole('all');
        $data_user = \DB::table('users')
                        ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
                        ->when($checkRole['isAdmin'],function($query){
                            $query->where('users.is_admin', true);
                        })
                        ->when($checkRole['isRw'],function($query){
                            $query->where('anggota_keluarga.is_rw', true);
                        })
                        ->where('users.user_id', \Auth::user()->user_id)->get();

        return view('Musrenbang.usulan.edit', compact('data', 'data_jenis_usulan', 'data_bidang', 'data_kegiatan', 'data_rw', 'data_rt', 'data_user'));
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

        \DB::beginTransaction();
        try {
            if ($request->hasFile('gambar_1')) {
                $input['gambar_1'] = 'gambar1' . rand() . '.' . $request->gambar_1->getClientOriginalExtension();
                $request->gambar_1->move(public_path('uploaded_files/musrenbang'), $input['gambar_1']);

                \File::delete(public_path('uploaded_files/musrenbang/'.$data->gambar_1));
            }
            if ($request->hasFile('gambar_2')) {
                $input['gambar_2'] = 'gambar2' . rand() . '.' . $request->gambar_2->getClientOriginalExtension();
                $request->gambar_2->move(public_path('uploaded_files/musrenbang'), $input['gambar_2']);

                \File::delete(public_path('uploaded_files/musrenbang/'.$data->gambar_2));
            }
            if ($request->hasFile('gambar_3')) {
                $input['gambar_3'] = 'gambar3' . rand() . '.' . $request->gambar_3->getClientOriginalExtension();
                $request->gambar_3->move(public_path('uploaded_files/musrenbang'), $input['gambar_3']);

                \File::delete(public_path('uploaded_files/musrenbang/'.$data->gambar_3));
            }

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
        $data = $this->usulanUrusanRepo->findOrFail($id);

        try {
            if($data->gambar_1){
                \File::delete(public_path('uploaded_files/musrenbang/'.$data->gambar_1));
            }
            if($data->gambar_2){
                \File::delete(public_path('uploaded_files/musrenbang/'.$data->gambar_2));
            }
            if($data->gambar_3){
                \File::delete(public_path('uploaded_files/musrenbang/'.$data->gambar_3));
            }
            $this->usulanUrusanRepo->destroy($id);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function dataTables()
    {
        return $this->usulanUrusanRepo->dataTables();
    }
}