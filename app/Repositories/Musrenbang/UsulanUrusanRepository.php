<?php

namespace App\Repositories\Musrenbang;

use App\Musrenbang\UsulanUrusan;
use App\Repositories\BaseRepository;

class UsulanUrusanRepository extends BaseRepository
{
    public function __construct(UsulanUrusan $usulanUrusan)
    {
        $this->model = $usulanUrusan;
    }

    public function dataTables()
    {
        $getData = \DB::table('user_role')->select(
            'user_role.user_role_id',
            'user_role.user_id',
            'user_role.role_id',
            'users.user_id',
            'anggota_keluarga.is_rw',
            'anggota_keluarga.rw_id',
        )
            ->leftJoin('users', 'users.user_id', 'user_role.user_id')
            ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
            ->where('users.user_id', \Auth::user()->user_id)
            ->first();

        $checkRW = \helper::checkUserRole('rw');
        
        $model = $this->model->select([
            'usulan_urusan.usulan_urusan_id',
            'usulan_urusan.menu_urusan_id',
            'usulan_urusan.bidang_urusan_id',
            'usulan_urusan.kegiatan_urusan_id',
            'usulan_urusan.rt_id',
            'usulan_urusan.rw_id',
            'usulan_urusan.user_id',
            'usulan_urusan.alamat',
            'usulan_urusan.jumlah',
            'usulan_urusan.tahun',
            'usulan_urusan.status_usulan',
            'usulan_urusan.keterangan',
            'usulan_urusan.updated_at',
        ])
        ->when($checkRW, function($query) use ($getData) {
            $query->where('rw_id', $getData->rw_id);
        });

        return \DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('jenis_usulan', function ($row) {
                return $row->JenisUsulan->nama_jenis ?? '-';
            })
            ->editColumn('bidang', function ($row) {
                return $row->Bidang->nama_bidang ?? '-';
            })
            ->editColumn('kegiatan', function ($row) {
                return $row->Kegiatan->nama_kegiatan ?? '-';
            })
            ->editColumn('rt', function ($row) {
                return $row->RT->rt ?? '-';
            })
            ->editColumn('rw', function ($row) {
                return $row->RW->rw ?? '-';
            })
            ->editColumn('ketua_rw', function ($row) {
                return ($row->User->anggotaKeluarga != null) ? $row->User->anggotaKeluarga->nama : $row->User->username;
            })
            ->editColumn('status_usulan', function ($row) {
                if ($row->status_usulan == 1) {
                    return '<code> Approved RW </code>';
                } else if ($row->status_usulan == 2) {
                    return '<code> Approved Kelurahan </code>';
                } else if ($row->status_usulan == 3) {
                    return '<code> Approved Kecamatan </code>';
                } else if ($row->status_usulan == 4) {
                    return '<code> Approved Walikota </code>';
                }
            })
            ->addColumn('action', function ($row) {
                $show = view('partials.buttons.cust-datatable', [
                    'show2' => ['name' => 'Detail', 'route' => route('Musrenbang.Usulan-Urusan' . '.show', \Crypt::encryptString($row->usulan_urusan_id))],
                ]);
                $edit = view('partials.buttons.cust-datatable', [
                    'edit2' => ['name' => 'Edit', 'route' => route('Musrenbang.Usulan-Urusan' . '.edit', \Crypt::encryptString($row->usulan_urusan_id))],
                ]);
                $destroy = view('partials.buttons.cust-datatable', [
                    'ajaxDestroy2' => ['name' => 'Delete', 'id' => $row->usulan_urusan_id],
                ]);

                if ($row->status_usulan == 1 || \helper::checkUserRole('admin')) {
                    return $show . $edit . $destroy;
                } else {
                    return $show;
                }
            })
            ->rawColumns(['action', 'status_usulan'])
            ->make(true);
    }

    public function kelurahanDataTables()
    {
        $model = $this->model->select([
            'usulan_urusan.usulan_urusan_id',
            'usulan_urusan.menu_urusan_id',
            'usulan_urusan.bidang_urusan_id',
            'usulan_urusan.kegiatan_urusan_id',
            'usulan_urusan.rt_id',
            'usulan_urusan.rw_id',
            'usulan_urusan.user_id',
            'usulan_urusan.alamat',
            'usulan_urusan.jumlah',
            'usulan_urusan.tahun',
            'usulan_urusan.status_usulan',
            'usulan_urusan.keterangan',
            'usulan_urusan.updated_at',
        ])
        ->where('status_usulan', 1);

        return \DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('jenis_usulan', function ($row) {
                return $row->JenisUsulan->nama_jenis ?? '-';
            })
            ->editColumn('bidang', function ($row) {
                return $row->Bidang->nama_bidang ?? '-';
            })
            ->editColumn('kegiatan', function ($row) {
                return $row->Kegiatan->nama_kegiatan ?? '-';
            })
            ->editColumn('rt', function ($row) {
                return $row->RT->rt ?? '-';
            })
            ->editColumn('rw', function ($row) {
                return $row->RW->rw ?? '-';
            })
            ->editColumn('ketua_rw', function ($row) {
                return ($row->User->anggotaKeluarga != null) ? $row->User->anggotaKeluarga->nama : $row->User->username;
            })
            ->editColumn('status_usulan', function ($row) {
                if ($row->status_usulan == 1) {
                    return '<code> Approved RW </code>';
                } else if ($row->status_usulan == 2) {
                    return '<code> Approved Kelurahan </code>';
                } else if ($row->status_usulan == 3) {
                    return '<code> Approved Kecamatan </code>';
                } else if ($row->status_usulan == 4) {
                    return '<code> Approved Walikota </code>';
                }
            })
            ->addColumn('action', function ($row) {
                return view('partials.buttons.cust-datatable', [
                    'show2' => ['name' => 'Detail', 'route' => route('Musrenbang.Approval-Kelurahan' . '.show', \Crypt::encryptString($row->usulan_urusan_id))],
                ]);
            })
            ->rawColumns(['action', 'status_usulan'])
            ->make(true);
    }

    public function kecamatanDataTables()
    {
        $model = $this->model->select([
            'usulan_urusan.usulan_urusan_id',
            'usulan_urusan.menu_urusan_id',
            'usulan_urusan.bidang_urusan_id',
            'usulan_urusan.kegiatan_urusan_id',
            'usulan_urusan.rt_id',
            'usulan_urusan.rw_id',
            'usulan_urusan.user_id',
            'usulan_urusan.alamat',
            'usulan_urusan.jumlah',
            'usulan_urusan.tahun',
            'usulan_urusan.status_usulan',
            'usulan_urusan.keterangan',
            'usulan_urusan.updated_at',
        ])
        ->where('status_usulan', 2);

        return \DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('jenis_usulan', function ($row) {
                return $row->JenisUsulan->nama_jenis ?? '-';
            })
            ->editColumn('bidang', function ($row) {
                return $row->Bidang->nama_bidang ?? '-';
            })
            ->editColumn('kegiatan', function ($row) {
                return $row->Kegiatan->nama_kegiatan ?? '-';
            })
            ->editColumn('rt', function ($row) {
                return $row->RT->rt ?? '-';
            })
            ->editColumn('rw', function ($row) {
                return $row->RW->rw ?? '-';
            })
            ->editColumn('ketua_rw', function ($row) {
                return ($row->User->anggotaKeluarga != null) ? $row->User->anggotaKeluarga->nama : $row->User->username;
            })
            ->editColumn('status_usulan', function ($row) {
                if ($row->status_usulan == 1) {
                    return '<code> Approved RW </code>';
                } else if ($row->status_usulan == 2) {
                    return '<code> Approved Kelurahan </code>';
                } else if ($row->status_usulan == 3) {
                    return '<code> Approved Kecamatan </code>';
                } else if ($row->status_usulan == 4) {
                    return '<code> Approved Walikota </code>';
                }
            })
            ->addColumn('action', function ($row) {
                return view('partials.buttons.cust-datatable', [
                    'show2' => ['name' => 'Detail', 'route' => route('Musrenbang.Approval-Kecamatan' . '.show', \Crypt::encryptString($row->usulan_urusan_id))],
                ]);
            })
            ->rawColumns(['action', 'status_usulan'])
            ->make(true);
    }

    public function walikotaDataTables()
    {
        $model = $this->model->select([
            'usulan_urusan.usulan_urusan_id',
            'usulan_urusan.menu_urusan_id',
            'usulan_urusan.bidang_urusan_id',
            'usulan_urusan.kegiatan_urusan_id',
            'usulan_urusan.rt_id',
            'usulan_urusan.rw_id',
            'usulan_urusan.user_id',
            'usulan_urusan.alamat',
            'usulan_urusan.jumlah',
            'usulan_urusan.tahun',
            'usulan_urusan.status_usulan',
            'usulan_urusan.keterangan',
            'usulan_urusan.updated_at',
        ])
        ->where('status_usulan', 3);

        return \DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('jenis_usulan', function ($row) {
                return $row->JenisUsulan->nama_jenis ?? '-';
            })
            ->editColumn('bidang', function ($row) {
                return $row->Bidang->nama_bidang ?? '-';
            })
            ->editColumn('kegiatan', function ($row) {
                return $row->Kegiatan->nama_kegiatan ?? '-';
            })
            ->editColumn('rt', function ($row) {
                return $row->RT->rt ?? '-';
            })
            ->editColumn('rw', function ($row) {
                return $row->RW->rw ?? '-';
            })
            ->editColumn('ketua_rw', function ($row) {
                return ($row->User->anggotaKeluarga != null) ? $row->User->anggotaKeluarga->nama : $row->User->username;
            })
            ->editColumn('status_usulan', function ($row) {
                if ($row->status_usulan == 1) {
                    return '<code> Approved RW </code>';
                } else if ($row->status_usulan == 2) {
                    return '<code> Approved Kelurahan </code>';
                } else if ($row->status_usulan == 3) {
                    return '<code> Approved Kecamatan </code>';
                } else if ($row->status_usulan == 4) {
                    return '<code> Approved Walikota </code>';
                }
            })
            ->addColumn('action', function ($row) {
                return view('partials.buttons.cust-datatable', [
                    'show2' => ['name' => 'Detail', 'route' => route('Musrenbang.Approval-Walikota' . '.show', \Crypt::encryptString($row->usulan_urusan_id))],
                ]);
            })
            ->rawColumns(['action', 'status_usulan'])
            ->make(true);
    }
}
