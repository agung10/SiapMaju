<?php

namespace App\Repositories\Musrenbang;

use App\Musrenbang\BidangUrusan;
use App\Repositories\BaseRepository;

class BidangUrusanRepository extends BaseRepository
{
    public function __construct(BidangUrusan $bidangUrusan)
    {
        $this->model = $bidangUrusan;
    }

    public function dataTables()
    {
        $model = $this->model->select([
            'bidang_urusan.bidang_urusan_id',
            'bidang_urusan.nama_bidang',
            'bidang_urusan.created_at',
            'bidang_urusan.updated_at',
        ]);

        return \DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return \helper::tglIndo(date('Y-m-d', strtotime($row->created_at)));
            })
            ->editColumn('updated_at', function ($row) {
                return \helper::tglIndo(date('Y-m-d', strtotime($row->updated_at)));
            })
            ->addColumn('action', function ($row) {
                return view('partials.buttons.cust-datatable', [
                    'show2' => ['name' => 'Detail', 'route' => route('Musrenbang.Bidang-Urusan' . '.show', \Crypt::encryptString($row->bidang_urusan_id))],
                    'edit2' => ['name' => 'Edit', 'route' => route('Musrenbang.Bidang-Urusan' . '.edit', \Crypt::encryptString($row->bidang_urusan_id))],
                    'ajaxDestroy2' => ['name' => 'Delete', 'id' => $row->bidang_urusan_id],
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
