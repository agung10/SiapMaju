<?php

namespace App\Repositories;
use App\Models\Polling\{ HasilPolling, Pertanyaan, PilihJawaban };
use App\{ User };
use App\Models\Master\Keluarga\AnggotaKeluarga;
use App\Models\Master\RT;
use SebastianBergmann\CodeCoverage\Percentage;

class PollingRepository
{
    public function __construct(HasilPolling $_HasilPolling, Pertanyaan $_Pertanyaan, PilihJawaban $_PilihJawaban, User $_User) {
       $this->hasil_polling = $_HasilPolling;
       $this->pertanyaan    = $_Pertanyaan;
       $this->pilih_jawaban = $_PilihJawaban;
       $this->user          = $_User;
    }

    public function storeQuestion($request) {
        \DB::beginTransaction();
        try {
            $question['province_id'] = $request->province_id;
            $question['city_id'] = $request->city_id;
            $question['subdistrict_id'] = $request->subdistrict_id;
            $question['kelurahan_id'] = $request->kelurahan_id;
            $question['rw_id'] = $request->rw_id;
            $question['rt_id'] = $request->rt_id;
            $question['close_date'] = $request->close_date;
            $question['isi_pertanyaan'] = $request->isi_pertanyaan;
            $question['user_create'] = ((\Auth::user()->is_admin) ? (\Auth::user()->user_id) : (\Auth::user()->anggota_keluarga_id));
            $newQuestion = $this->pertanyaan->create($question);
            $this->storeAnswer($request, $newQuestion);
            \DB::commit();

            return redirect()->route('Polling.Pertanyaan.index')->with('success', 'Data telah disimpan!');
        }
        catch (\Exception $e) {
            \DB::rollback();
            abort(403, $e->getMessage());
        }
    }

    public function storeAnswer($request, $data, $id = '') {
        foreach ($request->isi_pilih_jawaban as $key => $value) {
            $newData = $this->pilih_jawaban->find($key);
            if ($newData) {
                if ($newData->id_polling == \Crypt::decrypt($id)) {
                    $newData->update(['isi_pilih_jawaban' => $request->isi_pilih_jawaban[$key]]);
                }
                else {
                    $new['id_polling'] = ((!empty($id)) ? (\Crypt::decrypt($id)) : ($data->id_polling));
                    $new['isi_pilih_jawaban'] = $value;
                    $new['user_create'] = ((\Auth::user()->is_admin) ? (\Auth::user()->user_id) : (\Auth::user()->anggota_keluarga_id));
                    $this->pilih_jawaban->create($new);
                }
            }
            else {
                $new['id_polling'] = ((!empty($id)) ? (\Crypt::decrypt($id)) : ($data->id_polling));
                $new['isi_pilih_jawaban'] = $value;
                $new['user_create'] = ((\Auth::user()->is_admin) ? (\Auth::user()->user_id) : (\Auth::user()->anggota_keluarga_id));
                $this->pilih_jawaban->create($new);
            }
        }
    }

    public function getQuestion($id) {
        return $this->pertanyaan
            ->select('pertanyaan.id_polling', 'pertanyaan.province_id', 'pertanyaan.city_id', 'pertanyaan.subdistrict_id', 'pertanyaan.isi_pertanyaan', 'pertanyaan.close_date', 'kelurahan.kelurahan_id', 'kelurahan.nama as nama_kelurahan', 'rw.rw_id', 'rw.rw', 'rt.rt_id', 'rt.rt')
            ->join('kelurahan', 'kelurahan.kelurahan_id', 'pertanyaan.kelurahan_id')
            ->join('rw', 'rw.rw_id', 'pertanyaan.rw_id')
            ->join('rt', 'rt.rt_id', 'pertanyaan.rt_id')
            ->findOrFail($id);
    }

    public function removeAnswer($request) {
        $this->pilih_jawaban->destroy($request->id);
    }

    public function updateQuestion($request, $id) {
        \DB::beginTransaction();
        try {
            $question['province_id'] = $request->province_id;
            $question['city_id'] = $request->city_id;
            $question['subdistrict_id'] = $request->subdistrict_id;
            $question['kelurahan_id'] = $request->kelurahan_id;
            $question['rw_id'] = $request->rw_id;
            $question['rt_id'] = $request->rt_id;
            $question['close_date'] = $request->close_date;
            $question['isi_pertanyaan'] = $request->isi_pertanyaan;
            $question['user_update'] = ((\Auth::user()->is_admin) ? (\Auth::user()->user_id) : (\Auth::user()->anggota_keluarga_id));

            $data = $this->pertanyaan->findOrFail(\Crypt::decrypt($id));
            $newQuestion = $data->update($question);
            $this->storeAnswer($request, $newQuestion, $id);
            \DB::commit();

            return redirect()->route('Polling.Pertanyaan.index')->with('success', 'Data telah disimpan!');
        }
        catch (\Exception $e) {
            \DB::rollback();
            abort(403, $e->getMessage());
        }
    }

    public function destroyQuestion($id) {
        \DB::beginTransaction();
        try {
            $this->pilih_jawaban->where(['id_polling' => $id])->delete();

            $data = $this->pertanyaan->findOrFail($id);
            $data->destroy($id);
            \DB::commit();

            return response()->json(['status' => 'success']);
        }
        catch(\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function dataTablesQuestion() {
        $datatableButtons = method_exists(new $this->pertanyaan, 'datatableButtons') ? $this->pertanyaan->datatableButtons() : ['show', 'edit', 'destroy'];

        $data = $this->pertanyaan
            ->select('pertanyaan.id_polling', 'pertanyaan.isi_pertanyaan', 'rt.rt', 'rw.rw')
            ->join('rw', 'rw.rw_id', 'pertanyaan.rw_id')
            ->join('rt', 'rt.rt_id', 'pertanyaan.rt_id')
            ->when(!\Auth::user()->is_admin, function($query) {
                $query->whereIn('rt.rt_id', function($query) {
                    $query->select('anggota_keluarga.rt_id')->from('anggota_keluarga')
                        ->where(['anggota_keluarga.anggota_keluarga_id' => \Auth::user()->anggota_keluarga_id, 'is_active' => true, 'is_rt' => true]);
                });
            })
            ->orderBy('pertanyaan.id_polling', 'desc')
            ->get();

        return \DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($data) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show' => in_array('show', $datatableButtons) ? route('Polling.Pertanyaan.show', \Crypt::encrypt($data->id_polling)) : null,
                    'edit' => in_array('edit', $datatableButtons) ? route('Polling.Pertanyaan.edit', \Crypt::encrypt($data->id_polling)) : null,
                    'ajax_destroy' => in_array('destroy', $datatableButtons) ? $data->id_polling : null,
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function storePollingResult($request) {
        \DB::beginTransaction();
        try {
            $check = $this->hasil_polling->where(['id_polling' => \Crypt::decrypt($request->id_polling), 'anggota_keluarga_id' => $request->anggota_keluarga_id])->first();
            if ($check) {
                $polling['id_pilih_jawaban'] = $request->id_pilih_jawaban;
                $polling['keterangan'] = $request->keterangan;
                $check->update($polling);
            }
            else {
                $polling['id_polling'] = \Crypt::decrypt($request->id_polling);
                $polling['id_pilih_jawaban'] = $request->id_pilih_jawaban;
                $polling['anggota_keluarga_id'] = $request->anggota_keluarga_id;
                $polling['keterangan'] = $request->keterangan;
                $this->hasil_polling->create($polling);
            }

            \DB::commit();
            return array(
                'familyMember' => $this->getFamilyMember(\Auth::user()->anggota_keluarga_id, \Crypt::decrypt($request->id_polling)),
                'getPollingResult' => $this->getPollingResult($request->anggota_keluarga_id, \Crypt::decrypt($request->id_polling))
            );
        }
        catch (\Exception $e) {
            \DB::rollback();
            abort(403, $e->getMessage());
        }
    }

    public function getLoggedFamilyMember($familyMemberID) {
        return $this->user
            ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
            ->when(!\Auth::user()->is_admin, function($query) use ($familyMemberID) {
                $query->where(['users.anggota_keluarga_id' => $familyMemberID]);
            })
            ->first();
    }

    public function getPollingResult($familyMemberID, $pollingID) {
        $getDKM = RT::where('rt', 'DKM')->pluck('rt_id');

        $loggedFamilyMember = $this->getLoggedFamilyMember($familyMemberID);
        $pollingResult = $this->hasil_polling
            ->select('hasil_polling.id_hasil_polling', 'anggota_keluarga.nama', 'pilih_jawaban.isi_pilih_jawaban')
            ->join('pilih_jawaban', 'pilih_jawaban.id_pilih_jawaban', 'hasil_polling.id_pilih_jawaban')
            ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'hasil_polling.anggota_keluarga_id')
            ->where(['hasil_polling.id_polling' => $pollingID])
            ->when(!\Auth::user()->is_admin, function($query) use ($familyMemberID, $loggedFamilyMember, $pollingID, $getDKM) {
                if (\DB::table('pertanyaan')->where('id_polling', $pollingID)->whereIn('rt_id', $getDKM)->first()) {
                    $query->where(['anggota_keluarga.keluarga_id' => $loggedFamilyMember->keluarga_id]);
                    $query->whereIn('anggota_keluarga.hub_keluarga_id', [1, 3, 4]);
                } else {
                    $query->when(($loggedFamilyMember->hub_keluarga_id == 1), function($query) use ($loggedFamilyMember) {
                        $query->where(['anggota_keluarga.keluarga_id' => $loggedFamilyMember->keluarga_id]);
                    })
                    ->when(($loggedFamilyMember->hub_keluarga_id != 1), function($query) use ($familyMemberID) {
                        $query->where(['anggota_keluarga.anggota_keluarga_id' => $familyMemberID]);
                    });
                }
            })
            ->get();
        return view('Polling.Polling.polling-result', compact('pollingResult'))->render();
    }

    public function optionFamilyMember($data, $id = '') {
        $result = '<option></option>';
        foreach ($data as $key => $value) {
            $result .= '<option value="' . $key . '" ' . $this->getSelectedState($key, $id) . '>' . $value . '</option>';
        }
        return $result;
    }

    public function getSelectedState($key, $state) {
        return ((!empty($state)) ? (($state == $key) ? ('selected') : ('')) : (''));
    }

    public function getFamilyMember($familyMemberID, $pollingID, $edit = false) {
        $getDKM = RT::where('rt', 'DKM')->pluck('rt_id');
        
        $loggedFamilyMember = $this->getLoggedFamilyMember($familyMemberID);
        $familyMember = $this->user
            ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
            ->when(!\Auth::user()->is_admin, function($query) use ($loggedFamilyMember, $familyMemberID, $pollingID, $edit, $getDKM) {
                $query->when(!$edit, function($query) use ($loggedFamilyMember, $familyMemberID, $pollingID, $getDKM) {
                    if (\DB::table('pertanyaan')->where('id_polling', $pollingID)->whereIn('rt_id', $getDKM)->first()) {
                        $query->where(['anggota_keluarga.keluarga_id' => $loggedFamilyMember->keluarga_id]);
                        $query->whereIn('anggota_keluarga.hub_keluarga_id', [1, 3, 4]);
                    } else {
                        $query->when(($loggedFamilyMember->hub_keluarga_id == 1), function($query) use ($loggedFamilyMember) {
                            $query->where(['anggota_keluarga.keluarga_id' => $loggedFamilyMember->keluarga_id]);
                        })
                        ->when(($loggedFamilyMember->hub_keluarga_id != 1), function($query) use ($familyMemberID) {
                            $query->where(['anggota_keluarga.anggota_keluarga_id' => $familyMemberID]);
                        });
                        // ->whereNotIn('anggota_keluarga.anggota_keluarga_id', function($query) use ($pollingID) {
                        //     $query->select('hasil_polling.anggota_keluarga_id')->from('hasil_polling')->where(['hasil_polling.id_polling' => $pollingID]);
                        // });
                    }
                })
                    ->when($edit, function($query) use ($familyMemberID) {
                        $query->where(['anggota_keluarga.anggota_keluarga_id' => $familyMemberID]);
                    });
            })
            ->when(\Auth::user()->is_admin, function($query) use ($familyMemberID, $pollingID, $edit) {
                $query->when(!$edit, function($query) use ($pollingID) {
                    $query->whereNotIn('anggota_keluarga.anggota_keluarga_id', function($query) use ($pollingID) {
                        $query->select('hasil_polling.anggota_keluarga_id')->from('hasil_polling')->where(['hasil_polling.id_polling' => $pollingID]);
                    });
                })
                    ->when($edit, function($query) use ($familyMemberID) {
                        $query->where(['anggota_keluarga.anggota_keluarga_id' => $familyMemberID]);
                    });
                    // ->where(['anggota_keluarga.anggota_keluarga_id' => $familyMemberID]);
            })
            ->pluck('anggota_keluarga.nama', 'anggota_keluarga.anggota_keluarga_id');
        return $this->optionFamilyMember($familyMember, $familyMemberID);
    }

    public function getPollingAnswerQuestion($id, $answerID = '') {
        $question = $this->getQuestion($id);
        return view('Polling.Polling.polling-question', compact('question', 'answerID'))->render();
    }

    public function getEditPollingResult($id) {
        $pollingResult = $this->hasil_polling->findOrFail(\Crypt::decrypt($id));
        return array(
            'familyMember' => $this->getFamilyMember($pollingResult->anggota_keluarga_id, $pollingResult->id_polling, true),
            'description' => $pollingResult->keterangan,
            'answerOption' => $this->getPollingAnswerQuestion($pollingResult->id_polling, $pollingResult->id_pilih_jawaban)
        );
    }

    public function dataTablesPolling() {
        $getDKM = RT::where('rt', 'DKM')->pluck('rt_id');
        
        $getData = \DB::table('user_role')->select(
            'users.user_id',
            'anggota_keluarga.rw_id',
            'anggota_keluarga.rt_id',
            'anggota_keluarga.hub_keluarga_id',
        )
            ->leftJoin('users', 'users.user_id', 'user_role.user_id')
            ->leftJoin('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')
            ->where(['users.user_id' => \Auth::user()->user_id, 'anggota_keluarga.is_active' => true])
            ->first();

        $datatableButtons = method_exists(new $this->pertanyaan, 'datatableButtons') ? $this->pertanyaan->datatableButtons() : ['show', 'edit', 'destroy'];

        $data = $this->pertanyaan
            ->select('pertanyaan.id_polling', 'pertanyaan.isi_pertanyaan', 'pertanyaan.close_date', 'rt.rt')
            ->join('rt', 'rt.rt_id', 'pertanyaan.rt_id')
            ->when(!\Auth::user()->is_admin, function($query) use ($getData, $getDKM) {
                if ($getData->hub_keluarga_id == 1) {
                    if (\DB::table('pertanyaan')->whereIn('rt_id', $getDKM)->exists()) {
                        $query->whereIn('pertanyaan.rt_id', $getDKM->push($getData->rt_id));
                        $query->where('pertanyaan.rw_id',$getData->rw_id);
                    } else {
                        $query->whereIn('rt.rt_id', function($query) {
                            $query->select('anggota_keluarga.rt_id')->from('anggota_keluarga')
                                ->where(['anggota_keluarga.anggota_keluarga_id' => \Auth::user()->anggota_keluarga_id, 'is_active' => true]);
                        });
                    }
                } else {
                    $query->whereIn('rt.rt_id', function($query) {
                        $query->select('anggota_keluarga.rt_id')->from('anggota_keluarga')
                            ->where(['anggota_keluarga.anggota_keluarga_id' => \Auth::user()->anggota_keluarga_id, 'is_active' => true]);
                    });
                }
            })
            ->orderBy('pertanyaan.id_polling', 'desc')
            ->get();

        return \DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('close', function($data) { return date('d M Y', strtotime($data->close_date)); })
            ->addColumn('action', function($data) {
                // if (time() > strtotime($data->close_date. ' + 1 days')) {
                if (time() > strtotime($data->close_date)) {
                    return '<a title="Ditutup" class="btn btn-danger font-weight-bold mr-2 mt-2 btn-custom">Ditutup</a>';
                }
                else {
                    return view('partials.buttons.cust-datatable', [
                        'customButton' => ['route' => route('Polling.Polling.show_poll', \Crypt::encrypt($data->id_polling)), 'name' => 'Isi Polling']
                    ]);
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function collectPollingData($data) {
        $result = array();
        if ($data->answer->count() > 0) {
            foreach ($data->answer as $key => $value) {
                if ($data->rt === 'DKM') {
                    $arrData = array(
                        'jawaban' => $value->isi_pilih_jawaban . ' (' . (($data->result->count() > 0) ? (number_format((($value->pollingDKM->count() / $data->result->count()) * 100), 1, ',', '.')) : (0)) . '%)',
                        'jumlah' => $value->pollingDKM->count()
                    );
                } else {
                    $arrData = array(
                        'jawaban' => $value->isi_pilih_jawaban . ' (' . (($data->result->count() > 0) ? (number_format((($value->pollingResult->count() / $data->result->count()) * 100), 1, ',', '.')) : (0)) . '%)',
                        'jumlah' => $value->pollingResult->count()
                    );
                }
                array_push($result, $arrData);
            }
        }
        return json_encode($result);
    }

    public function collectPollingDataRT($data) {
        $listRT = RT::select('rt_id', 'rt')->where('rw_id', $data->rw_id)->where('rt', '!=', 'DKM')->orderBy('rt_id', 'asc')->get();
        
        if ($data->rt === 'DKM') {
            $hasilPolling = HasilPolling::select('rt_id', 'id_hasil_polling')->join('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'hasil_polling.anggota_keluarga_id')->where(['id_polling' => $data->id_polling, 'agama_id' => 1])->get();
        } else {
            $hasilPolling = HasilPolling::select('rt_id', 'id_hasil_polling')->join('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'hasil_polling.anggota_keluarga_id')->where(['id_polling' => $data->id_polling])->get();
        }

        $totalCount = 0;
        foreach ($listRT as $key => $rt) { $listRT[$key]->setAttribute('count', 0); }
        foreach ($hasilPolling as $hasil) {
            foreach ($listRT as $rt) {
                if ($rt->rt_id == $hasil->rt_id) {
                    $rt->count += 1 ;
                    $totalCount += 1;
                }
            }
        }

        foreach ($listRT as $key => $rt) { 
            $listRT[$key]->setAttribute('percentage', ((float)number_format((($rt->count / $totalCount) * 100), 1, '.', '.'))); 
        }
        
        $result = ['label' => $listRT->pluck('rt')->toArray(), 'data' => $listRT->pluck('count')->toArray(), 'percentage' => $listRT->pluck('percentage')->toArray()];
        return $result;
    }

    public function dataTablesLaporan() {
        $datatableButtons = method_exists(new $this->pertanyaan, 'datatableButtons') ? $this->pertanyaan->datatableButtons() : ['show', 'edit', 'destroy'];

        $data = $this->pertanyaan
            ->select('pertanyaan.id_polling', 'pertanyaan.isi_pertanyaan', 'pertanyaan.close_date', 'rt.rt')
            ->join('rt', 'rt.rt_id', 'pertanyaan.rt_id')
            ->when(!\Auth::user()->is_admin, function($query) {
                $query->whereIn('rt.rt_id', function($query) {
                    $query->select('anggota_keluarga.rt_id')->from('anggota_keluarga')
                        ->where(['anggota_keluarga.anggota_keluarga_id' => \Auth::user()->anggota_keluarga_id, 'is_active' => true]);
                });
            })
            ->orderBy('pertanyaan.id_polling', 'desc')
            ->get();

        return \DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('close', function($data) { return date('d M Y', strtotime($data->close_date)); })
            ->addColumn('action', function($data) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show' => in_array('show', $datatableButtons) ? route('Polling.Laporan.show', \Crypt::encrypt($data->id_polling)) : null
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function dataTablesLaporanAudit() {
        $datatableButtons = method_exists(new $this->pertanyaan, 'datatableButtons') ? $this->pertanyaan->datatableButtons() : ['show', 'edit', 'destroy'];

        $data = $this->pertanyaan
            ->select('pertanyaan.id_polling', 'pertanyaan.isi_pertanyaan', 'pertanyaan.close_date', 'rt.rt')
            ->join('rt', 'rt.rt_id', 'pertanyaan.rt_id')
            ->when(!\Auth::user()->is_admin, function($query) {
                $query->whereIn('rt.rt_id', function($query) {
                    $query->select('anggota_keluarga.rt_id')->from('anggota_keluarga')
                        ->where(['anggota_keluarga.anggota_keluarga_id' => \Auth::user()->anggota_keluarga_id, 'is_active' => true]);
                });
            })
            ->orderBy('pertanyaan.id_polling', 'desc')
            ->get();

        return \DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('close', function($data) { return date('d M Y', strtotime($data->close_date)); })
            ->addColumn('action', function($data) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show' => in_array('show', $datatableButtons) ? route('Polling.LaporanAudit.show', \Crypt::encrypt($data->id_polling)) : null
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function dataTablesLaporanRT() {
        $datatableButtons = method_exists(new $this->pertanyaan, 'datatableButtons') ? $this->pertanyaan->datatableButtons() : ['show', 'edit', 'destroy'];

        $data = $this->pertanyaan
            ->select('pertanyaan.id_polling', 'pertanyaan.isi_pertanyaan', 'pertanyaan.close_date', 'rt.rt')
            ->join('rt', 'rt.rt_id', 'pertanyaan.rt_id')
            ->when(!\Auth::user()->is_admin, function($query) {
                $query->whereIn('rt.rt_id', function($query) {
                    $query->select('anggota_keluarga.rt_id')->from('anggota_keluarga')
                        ->where(['anggota_keluarga.anggota_keluarga_id' => \Auth::user()->anggota_keluarga_id, 'is_active' => true]);
                });
            })
            ->orderBy('pertanyaan.id_polling', 'desc')
            ->get();

        return \DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('close', function($data) { return date('d M Y', strtotime($data->close_date)); })
            ->addColumn('action', function($data) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show' => in_array('show', $datatableButtons) ? route('Polling.LaporanRT.show', \Crypt::encrypt($data->id_polling)) : null
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}