<?php

namespace App\Repositories\KeluhKesan;

use App\Models\KeluhKesan\KeluhKesan;
use App\Repositories\BaseRepository;

class KeluhKesanRepository extends BaseRepository
{
    public function __construct(KeluhKesan $keluhKesan)
    {
        $this->model = $keluhKesan;
    }

    public function dataTablesKeluhKesan($request)
    {
         $model = $this->model->select([
                                'keluh_kesan.keluh_kesan_id',
                                'keluh_kesan.keluh_kesan',
                                'users.username',
                                'users.picture',
                                'keluh_kesan.updated_at'
                            ])
                            ->join('users', 'users.user_id', 'keluh_kesan.user_id');

        return \DataTables::of($model)
                            ->addIndexColumn()
                            ->editColumn('username', function($row){
                                return view('keluh_kesan.keluh_kesan.row_user', compact('row'));
                            })
                            ->editColumn('keluh_kesan', function($row){
                                return \Str::words($row->keluh_kesan, 10);
                            })
                            ->addColumn('balas_keluh_kesan', function($row){
                                return !$row->balasan->isEmpty()
                                    ? '<span class="badge badge-pill badge-success">Telah dibalas</span>'
                                    : '<span class="badge badge-pill badge-danger">Belum dibalas</span>';
                            })
                            ->addColumn('action',function($row){
                               return view('partials.buttons.cust-datatable',[
                                   'show'         => route('keluhKesan.keluhKesan.show',$row->keluh_kesan_id),
                                   'edit'         => route('keluhKesan.keluhKesan.edit',$row->keluh_kesan_id),
                                   'ajax_destroy' => $row->keluh_kesan_id
                               ]); 
                            })
                            ->rawColumns(['username', 'balas_keluh_kesan', 'action'])
                            ->make(true);
    }

}