<?php

namespace App\Repositories\Musrenbang;

use App\Musrenbang\MenuUrusan;
use App\Repositories\BaseRepository;

class MenuUrusanRepository extends BaseRepository
{
    public function __construct(MenuUrusan $menuUrusan)
    {
        $this->model = $menuUrusan;
    }

    public function dataTables()
    {
        $model = $this->model->select([
            'menu_urusan.menu_urusan_id',
            'menu_urusan.nama_jenis',
            'menu_urusan.created_at',
            'menu_urusan.updated_at',
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
                    'show2' => ['name' => 'Detail', 'route' => route('Musrenbang.Menu-Urusan' . '.show', \Crypt::encryptString($row->menu_urusan_id))],
                    'edit2' => ['name' => 'Edit', 'route' => route('Musrenbang.Menu-Urusan' . '.edit', \Crypt::encryptString($row->menu_urusan_id))],
                    'ajaxDestroy2' => ['name' => 'Delete', 'id' => $row->menu_urusan_id],
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
