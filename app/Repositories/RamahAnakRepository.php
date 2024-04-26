<?php

namespace App\Repositories;
use App\Models\Master\{ RW };
use App\Models\RamahAnak\{ RamahAnak, Vaksin };
use App\{ User };

class RamahAnakRepository
{
    public function __construct(RamahAnak $RamahAnak, RW $RW, User $User, Vaksin $Vaccine) {
       $this->healthcare = $RamahAnak;
       $this->rw = $RW;
       $this->user = $User;
       $this->vaccine = $Vaccine;
    }

    public function getRWOption($RWID = '') {
        $rw = $this->rw->pluck('rw', 'rw_id');

        return $this->optionSelect($rw, $RWID);
    }

    public function storeVaccine($request) {
        \DB::beginTransaction();
        try {
            $vaccine['nama_vaksin'] = $request->nama_vaksin;
            $vaccine['keterangan'] = (($request->keterangan) ? ($request->keterangan) : (null));
            $vaccine['user_created'] = \Auth::user()->user_id;
            $vaccine['wajib'] = (($request->wajib == 'on') ? (true) : (false));
            $vaccine['status_aktif'] = (($request->status_aktif == 'on') ? (true) : (false));
            $this->vaccine->create($vaccine);
            \DB::commit();

            return redirect()->route('RamahAnak.Vaksin.index')->with('success', 'Data telah disimpan!');
        }
        catch (\Exception $e) {
            \DB::rollback();
            abort(403, $e->getMessage());
        }
    }

    public function getVaccine($id) {
        return $this->vaccine
            ->select('id_vaksin', 'nama_vaksin', 'keterangan', 'wajib', 'status_aktif')
            ->findOrFail($id);
    }

    public function updateVaccine($request, $id) {
        \DB::beginTransaction();
        try {
            $vaccine['nama_vaksin'] = $request->nama_vaksin;
            $vaccine['keterangan'] = (($request->keterangan) ? ($request->keterangan) : (null));
            $vaccine['user_created'] = \Auth::user()->user_id;
            $vaccine['wajib'] = (($request->wajib == 'on') ? (true) : (false));
            $vaccine['status_aktif'] = (($request->status_aktif == 'on') ? (true) : (false));

            $data = $this->vaccine->findOrFail(\Crypt::decrypt($id));
            $data->update($vaccine);
            \DB::commit();

            return redirect()->route('RamahAnak.Vaksin.index')->with('success', 'Data telah disimpan!');
        }
        catch (\Exception $e) {
            \DB::rollback();
            abort(403, $e->getMessage());
        }
    }

    public function destroyVaccine($id) {
        \DB::beginTransaction();
        try {
            $data = $this->vaccine->findOrFail($id);
            $data->update(['is_delete' => true]);
            \DB::commit();

            return response()->json(['status' => 'success']);
        }
        catch(\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function dataTablesVaccine() {
        $datatableButtons = method_exists(new $this->vaccine, 'datatableButtons') ? $this->vaccine->datatableButtons() : ['show', 'edit', 'destroy'];
        $data = $this->vaccine->select('id_vaksin', 'nama_vaksin', 'wajib', 'status_aktif')
            ->where(['is_delete' => false])
            ->orderBy('id_vaksin', 'desc');

        return \DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('wajib', function($data) use ($datatableButtons) {
                return (($data->wajib) ? ('Wajib') : ('Tidak Wajib'));
            })
            ->addColumn('status_aktif', function($data) use ($datatableButtons) {
                return (($data->status_aktif) ? ('Aktif') : ('Tidak Aktif'));
            })
            ->addColumn('action', function($data) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show' => in_array('show', $datatableButtons) ? route('RamahAnak.Vaksin.show', \Crypt::encrypt($data->id_vaksin)) : null,
                    'edit' => in_array('edit', $datatableButtons) ? route('RamahAnak.Vaksin.edit', \Crypt::encrypt($data->id_vaksin)) : null,
                    'ajax_destroy' => in_array('destroy', $datatableButtons) ? $data->id_vaksin : null,
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getVaccineOption($vaccineID = '') {
        $vaccine = $this->vaccine->where(['status_aktif' => true, 'is_delete' => false])->pluck('nama_vaksin', 'id_vaksin');

        return $this->optionSelect($vaccine, $vaccineID);
    }

    public function getFamilyMember($officerID, $familyMemberID = '', $edit = false) {
        $officer = $this->getLoggedOfficer($officerID);
        $familyMember = $this->user
            ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
            ->when(!$edit, function($query) use ($officer, $familyMemberID) {
                $query
                    ->where(['anggota_keluarga.hub_keluarga_id' => 5])
                    ->when(!$officer->is_admin, function($query) use ($officer) {
                        $query->where(['anggota_keluarga.rw_id' => $officer->anggotaKeluarga->rw_id]);
                    });
            })
            ->when($edit, function($query) use ($familyMemberID) {
                $query->where(['anggota_keluarga.anggota_keluarga_id' => $familyMemberID]);
            })
            ->pluck('anggota_keluarga.nama', 'anggota_keluarga.anggota_keluarga_id');

        return $this->optionSelect($familyMember, $familyMemberID);
    }

    public function getLoggedOfficer($officerID) {
        return $this->user->findOrFail($officerID);
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

    public function storeHealthcare($request) {
        \DB::beginTransaction();
        try {
            $healthcare['id_vaksin'] = $request->id_vaksin;
            $healthcare['anggota_keluarga_id'] = $request->anggota_keluarga_id;
            $healthcare['tgl_input'] = $request->tgl_input;
            $healthcare['ket_vaksinasi'] = (($request->ket_vaksinasi) ? ($request->ket_vaksinasi) : (null));
            $healthcare['berat'] = number_format($request->berat, 2, '.', '');
            $healthcare['tinggi'] = number_format($request->tinggi, 2, '.', '');
            $healthcare['lingkar_kepala'] = number_format($request->lingkar_kepala, 2, '.', '');
            $healthcare['nilai_stunting'] = number_format($request->nilai_stunting, 2, '.', '');
            $healthcare['keluhan'] = (($request->keluhan) ? ($request->keluhan) : (null));
            $healthcare['user_created'] = \Auth::user()->user_id;
            $this->healthcare->create($healthcare);
            \DB::commit();

            return redirect()->route('RamahAnak.Posyandu.index')->with('success', 'Data telah disimpan!');
        }
        catch (\Exception $e) {
            \DB::rollback();
            abort(403, $e->getMessage());
        }
    }

    public function getHealthcare($id) {
        return $this->healthcare->findOrFail($id);
    }

    public function updateHealthcare($request, $id) {
        \DB::beginTransaction();
        try {
            $healthcare['id_vaksin'] = $request->id_vaksin;
            $healthcare['anggota_keluarga_id'] = $request->anggota_keluarga_id;
            $healthcare['tgl_input'] = $request->tgl_input;
            $healthcare['ket_vaksinasi'] = (($request->ket_vaksinasi) ? ($request->ket_vaksinasi) : (null));
            $healthcare['berat'] = number_format($request->berat, 2, '.', '');
            $healthcare['tinggi'] = number_format($request->tinggi, 2, '.', '');
            $healthcare['lingkar_kepala'] = number_format($request->lingkar_kepala, 2, '.', '');
            $healthcare['nilai_stunting'] = number_format($request->nilai_stunting, 2, '.', '');
            $healthcare['keluhan'] = (($request->keluhan) ? ($request->keluhan) : (null));
            $healthcare['user_created'] = \Auth::user()->user_id;
            $data = $this->healthcare->findOrFail(\Crypt::decrypt($id));
            $data->update($healthcare);
            \DB::commit();

            return redirect()->route('RamahAnak.Posyandu.index')->with('success', 'Data telah disimpan!');
        }
        catch (\Exception $e) {
            \DB::rollback();
            abort(403, $e->getMessage());
        }
    }

    public function destroyHealthcare($id) {
        \DB::beginTransaction();
        try {
            $this->healthcare->destroy($id);
            \DB::commit();

            return response()->json(['status' => 'success']);
        }
        catch(\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function dataTablesHealthcare() {
        $datatableButtons = method_exists(new $this->healthcare, 'datatableButtons') ? $this->healthcare->datatableButtons() : ['show', 'edit', 'destroy'];
        $data = $this->healthcare->select('ramah_anak.id_ramah_anak', 'ramah_anak.tgl_input', 'ramah_anak.ket_vaksinasi', 'anggota_keluarga.nama', 'vaksin.nama_vaksin')
            ->join('vaksin', 'vaksin.id_vaksin', 'ramah_anak.id_vaksin')
            ->join('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'ramah_anak.anggota_keluarga_id')
            ->when(!\Auth::user()->is_admin, function($query) {
                $query->where(['anggota_keluarga.rw_id' => \Auth::user()->anggotaKeluarga->rw_id]);
            })
            ->where(['anggota_keluarga.hub_keluarga_id' => 5])
            ->orderBy('ramah_anak.id_ramah_anak', 'desc');

        return \DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('tgl_input', function($data) use ($datatableButtons) {
                return date('d M Y', strtotime($data->tgl_input));
            })
            ->addColumn('action', function($data) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show' => ((in_array('show', $datatableButtons)) ? (route('RamahAnak.Posyandu.show', \Crypt::encrypt($data->id_ramah_anak))) : (null)),
                    'edit' => ((in_array('edit', $datatableButtons)) ? (route('RamahAnak.Posyandu.edit', \Crypt::encrypt($data->id_ramah_anak))) : (null)),
                    'ajax_destroy' => ((in_array('destroy', $datatableButtons)) ? ($data->id_ramah_anak) : (null)),
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getReport($id) {
        return $this->user
            ->with(['childHealthcare' => function($query) { $query->orderBy('id_ramah_anak', 'desc'); }])
            ->where(['anggota_keluarga_id' => $id])
            ->first();
    }

    public function collectGraphData($data) {
        $result = array();
        for ($i = 0; $i <= 60; $i++) {
            $check = $this->healthcare
                ->where(['anggota_keluarga_id' => $data->anggota_keluarga_id])
                ->whereMonth('tgl_input', date('m', strtotime('+' . $i . ' month', strtotime($data->anggotaKeluarga->tgl_lahir))))
                ->whereYear('tgl_input', date('Y', strtotime('+' . $i . ' month', strtotime($data->anggotaKeluarga->tgl_lahir))))
                ->first();

            $arrData = array(
                'month' => $i,
                'weight' => (($check) ? (($check->berat) ? ($check->berat) : (null)) : (null)),
                'height' => (($check) ? (($check->tinggi) ? ($check->tinggi) : (null)) : (null))
            );

            array_push($result, $arrData);
        }

        return json_encode($result);
    }

    public function dataTablesReport($id = '') {
        $datatableButtons = method_exists(new $this->user, 'datatableButtons') ? $this->user->datatableButtons() : ['show'];
        $data = $this->user->select('users.anggota_keluarga_id', 'anggota_keluarga.nama', 'anggota_keluarga.jenis_kelamin', 'anggota_keluarga.tgl_lahir')
            ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
            ->when(\Auth::user()->is_admin, function($query) use ($id) {
                $query->when($id, function($query) use ($id) {
                    $query->where(['anggota_keluarga.rw_id' => $id]);
                });
            })
            ->when(!\Auth::user()->is_admin, function($query) {
                $query->where(['anggota_keluarga.rw_id' => \Auth::user()->anggotaKeluarga->rw_id]);
            })
            ->where(['anggota_keluarga.hub_keluarga_id' => 5])
            ->orderBy('anggota_keluarga.anggota_keluarga_id', 'desc');

        return \DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('jenis_kelamin', function($data) use ($datatableButtons) {
                return (($data->jenis_kelamin == 'L') ? ('Laki-laki') : ('Perempuan'));
            })
            ->addColumn('tgl_lahir', function($data) use ($datatableButtons) {
                return date('d M Y', strtotime($data->tgl_lahir));
            })
            ->addColumn('action', function($data) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show' => ((in_array('show', $datatableButtons)) ? (route('RamahAnak.Laporan.show', \Crypt::encrypt($data->anggota_keluarga_id))) : (null)),
                    'edit' => ((in_array('edit', $datatableButtons)) ? (route('RamahAnak.Laporan.edit', \Crypt::encrypt($data->anggota_keluarga_id))) : (null)),
                    'ajax_destroy' => ((in_array('destroy', $datatableButtons)) ? ($data->anggota_keluarga_id) : (null)),
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}