<?php

namespace App\Repositories\Musrenbang;

use App\Musrenbang\KegiatanUrusan;
use App\Repositories\BaseRepository;

class KegiatanUrusanRepository extends BaseRepository
{
    public function __construct(KegiatanUrusan $kegiatanUrusan)
    {
        $this->model = $kegiatanUrusan;
    }

    public function dataTables()
    {
        $model = $this->model->select([
            'kegiatan_urusan.kegiatan_urusan_id',
            'kegiatan_urusan.nama_kegiatan',
            'kegiatan_urusan.created_at',
            'kegiatan_urusan.updated_at',
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
                    'show2' => ['name' => 'Detail', 'route' => route('Musrenbang.Kegiatan-Urusan' . '.show', \Crypt::encryptString($row->kegiatan_urusan_id))],
                    'edit2' => ['name' => 'Edit', 'route' => route('Musrenbang.Kegiatan-Urusan' . '.edit', \Crypt::encryptString($row->kegiatan_urusan_id))],
                    'ajaxDestroy2' => ['name' => 'Delete', 'id' => $row->kegiatan_urusan_id],
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
