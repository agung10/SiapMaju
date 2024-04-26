<?php

namespace App\Repositories\Master;
use App\Models\Master\Keluarga\{ AnggotaKeluarga, MutasiWarga, StatusMutasiWarga };
use App\{ User };

class MutasiWargaRepository
{
    public function __construct(AnggotaKeluarga $AnggotaKeluarga, MutasiWarga $MutasiWarga, StatusMutasiWarga $StatusMutasiWarga, User $User) {
        $this->anggota_keluarga = $AnggotaKeluarga;
        $this->mutasi = $MutasiWarga;
        $this->status_mutasi = $StatusMutasiWarga;
        $this->user = $User;
    }

    public function getLoggedMember($memberID) {
        return $this->user
            ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
            ->when(!\Auth::user()->is_admin, function($query) use ($memberID) {
                $query->where(['users.anggota_keluarga_id' => $memberID]);
            })
            ->first();
    }

    public function optionSelect($data, $id = '') {
        $result = '<option></option>';
        foreach ($data as $key => $value) {
            $result .= '<option value="' . $key . '" ' . $this->getSelectedState($key, $id) . '>' . $value . '</option>';
        }
        return $result;
    }

    public function getSelectedState($key, $state) {
        return ((!empty($state)) ? (($state == $key) ? ('selected') : ('')) : (''));
    }

    public function getFamilyMember($memberID, $edit = false) {
        $officer = $this->getLoggedMember($memberID);
        $familyMember = $this->user
            ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
            ->when(!\Auth::user()->is_admin, function($query) use ($officer, $memberID, $edit) {
                $query
                    ->when(!$officer->is_rt, function($query) use ($memberID) {
                        $query->where(['anggota_keluarga.anggota_keluarga_id' => $memberID]);
                    })
                    ->when($officer->is_rt, function($query) use ($officer, $memberID, $edit) {
                        $query
                            ->when(!$edit, function($query) use ($officer) {
                                $query
                                    ->where(['anggota_keluarga.rt_id' => $officer->rt_id])
                                    ->whereNotIn('anggota_keluarga.anggota_keluarga_id', function($query) {
                                        $query->select('mutasi_warga.anggota_keluarga_id')->from('mutasi_warga')->whereIn('mutasi_warga.status_mutasi_warga_id', [2, 3]);
                                    });
                            })
                            ->when($edit, function($query) use ($memberID) {
                                $query->where(['anggota_keluarga.anggota_keluarga_id' => $memberID]);
                            });
                    });
            })
            ->when(\Auth::user()->is_admin, function($query) use ($memberID, $edit) {
                $query
                    ->when(!$edit, function($query) {
                        $query
                            ->whereNotIn('anggota_keluarga.anggota_keluarga_id', function($query) {
                                $query->select('mutasi_warga.anggota_keluarga_id')->from('mutasi_warga')->whereIn('mutasi_warga.status_mutasi_warga_id', [2, 3]);
                            });
                    })
                    ->when($edit, function($query) use ($memberID) {
                        $query->where(['anggota_keluarga.anggota_keluarga_id' => $memberID]);
                    });
            })
            ->pluck('anggota_keluarga.nama', 'anggota_keluarga.anggota_keluarga_id');
        return $this->optionSelect($familyMember, (($edit) ? ($memberID) : (null)));
    }

    public function getMovedStatus($memberID = '') {
        $movedStatus = $this->status_mutasi->where('status_mutasi_warga_id', '!=', 1)->pluck('nama_status', 'status_mutasi_warga_id');
        return $this->optionSelect($movedStatus, $memberID);
    }

    public function store($request) {
        \DB::beginTransaction();
        try {
            $moving = $request->all();
            $moving['keterangan'] = (($request->keterangan) ? (str_replace('<br />', PHP_EOL, nl2br($request->keterangan))) : (null));
            $this->mutasi->create($moving);
            $this->anggota_keluarga->findOrFail($request->anggota_keluarga_id)->update(['is_active' => false]);
            \DB::commit();

            return redirect()->route('Master.MutasiWarga.index')->with('success', 'Data telah disimpan!');
        }
        catch (\Exception $e) {
            \DB::rollback();
            abort(403, $e->getMessage());
        }
    }

    public function show($id) {
        return $this->user->select('anggota_keluarga.nama', 'anggota_keluarga.jenis_kelamin', 'anggota_keluarga.tgl_lahir', 'anggota_keluarga.email', 'anggota_keluarga.mobile', 'anggota_keluarga.alamat')
            ->join('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
            ->where(['users.anggota_keluarga_id' => $id])
            ->first();
    }

    public function getHistory($memberID) {
        return $this->mutasi->where(['anggota_keluarga_id' => $memberID])->get();
    }

    public function getMovedDatatables() {
        $datatableButtons = method_exists(new User, 'datatableButtons') ? User::datatableButtons() : ['show'];
        $data = $this->user->select('users.anggota_keluarga_id', 'anggota_keluarga.nama')
            ->join('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
            ->whereIn('users.anggota_keluarga_id', function($query) {
                $query->select('mutasi_warga.anggota_keluarga_id')->from('mutasi_warga');
            });

        return \DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('tanggal_mutasi', function($data) {
                $data = $this->mutasi->where(['anggota_keluarga_id' => $data->anggota_keluarga_id])->orderBy('mutasi_warga_id', 'desc')->first();
                return date('d M Y', strtotime($data->tanggal_mutasi));
            })
            ->addColumn('nama_status', function($data) {
                $data = $this->mutasi->where(['anggota_keluarga_id' => $data->anggota_keluarga_id])->orderBy('mutasi_warga_id', 'desc')->first();
                return $data->movingStatus->nama_status;
            })
            ->addColumn('action', function($data) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show'         => ((in_array('show', $datatableButtons)) ? (route('Master.MutasiWarga.show', \Crypt::encryptString($data->anggota_keluarga_id))) : (null)),
                    'edit'         => ((in_array('edit', $datatableButtons)) ? (route('Master.MutasiWarga.edit', \Crypt::encryptString($data->anggota_keluarga_id))) : (null)),
                    'ajax_destroy' => ((in_array('destroy', $datatableButtons)) ? ($data->anggota_keluarga_id) : (null))
                ]);
            })->rawColumns(['action'])
            ->make(true);
    }
}