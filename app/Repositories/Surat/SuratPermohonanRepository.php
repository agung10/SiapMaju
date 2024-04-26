<?php

namespace App\Repositories\Surat;
use App\Models\Surat\SuratPermohonan;
use App\Models\Master\Keluarga\AnggotaKeluarga;
use App\Models\Surat\SuratPermohonanLampiran;

class SuratPermohonanRepository
{
    public function __construct(SuratPermohonan $_SuratPermohonan, AnggotaKeluarga $_AnggotaKeluarga, SuratPermohonanLampiran $suratPermohonanLampiran)
    {
        $this->surat = $_SuratPermohonan;
        $this->warga = $_AnggotaKeluarga;
        $this->suratPermohonanLampiran = $suratPermohonanLampiran;
    }

    public function dataTables()
    {   
        $logedUSer = \DB::table('users')
                         ->select('anggota_keluarga.is_rt','anggota_keluarga.is_rw','anggota_keluarga.keluarga_id','anggota_keluarga.hub_keluarga_id','users.is_admin','keluarga.rt_id','keluarga.rw_id')
                         ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                         ->leftJoin('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                         ->where('user_id',\Auth::user()->user_id)
                         ->first();

        $isKepalaKeluarga = \helper::checkUserRole('kepalaKeluarga');
        
        $isRt = $logedUSer->is_rt == true;
        $isRw = $logedUSer->is_rw == true;
        $isAdmin = $logedUSer->is_admin == true;
        $isPetugas = $isRt == true || $isRw == true || $isAdmin == true || $isKepalaKeluarga == true;
        $isWarga = $isRt != true && $isRw != true && $isAdmin != true;

        $model = $this->surat
                      ->select('surat_permohonan.no_surat',
                               'surat_permohonan.surat_permohonan_id',
                               'surat_permohonan.hal',
                               'surat_permohonan.nama_lengkap',
                               'surat_permohonan.tgl_permohonan',
                               'surat_permohonan.tgl_approve_rt',
                               'surat_permohonan.tgl_approve_rw',
                               'surat_permohonan.tgl_approve_kelurahan',
                               'surat_permohonan.tgl_approve_kasi',
                               'surat_permohonan.tgl_approve_sekel',
                               'surat_permohonan.tgl_approve_lurah',
                               'surat_permohonan.approve_draft',
                               'surat_permohonan.petugas_rt_id',
                               'surat_permohonan.petugas_rw_id',
                               'surat_permohonan.petugas_kelurahan_id',
                               'surat_permohonan.kasi_id',
                               'surat_permohonan.sekel_id',
                               'surat_permohonan.updated_at',
                               'surat_permohonan.approve_lurah',
                               'surat_permohonan.status_upload',
                               'surat_permohonan.upload_surat_kelurahan',
                               'anggota_keluarga.keluarga_id',
                               'rt.rt')
                      ->join('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','surat_permohonan.anggota_keluarga_id')
                      ->join('rt','rt.rt_id','surat_permohonan.rt_id')
                      ->join('rw','rw.rw_id','surat_permohonan.rw_id')
                      ->when($isPetugas !== true, function($query){
                          $query->where('surat_permohonan.anggota_keluarga_id',\Auth::user()->anggota_keluarga_id);
                      })
                        ->when($isKepalaKeluarga && !$isRt && !$isRw, function($query) use ($logedUSer){
                            $query->where('anggota_keluarga.keluarga_id', $logedUSer->keluarga_id);
                        })  
                      ->when($isRt == true,function($query)use($logedUSer){
                          $query->where('surat_permohonan.approve_draft',true)
                                ->where('surat_permohonan.rt_id',$logedUSer->rt_id)
                                ->orWhere('anggota_keluarga.keluarga_id', $logedUSer->keluarga_id)
                                ->orWhere('surat_permohonan.anggota_keluarga_id',\Auth::user()->anggota_keluarga_id);
                      })
                      ->when($isRw== true,function($query)use($logedUSer){
                          $query->whereNotNull('surat_permohonan.petugas_rt_id')
                                ->where('surat_permohonan.rw_id',$logedUSer->rw_id)
                                ->orWhere('anggota_keluarga.keluarga_id', $logedUSer->keluarga_id)
                                ->orWhere('surat_permohonan.anggota_keluarga_id',\Auth::user()->anggota_keluarga_id);
                      });
                      

        $datatableButtons = method_exists(new $this->surat, 'datatableButtons') ? $this->surat->datatableButtons() : ['show', 'edit', 'destroy'];


        return \DataTables::of($model)
                          ->addIndexColumn()
                          ->addColumn('action',function($model)use($datatableButtons, $isWarga, $isRt, $isRw, $isAdmin){
                            $previewBtn = '';
                            if (($model->status_upload == 5) && ($model->upload_surat_kelurahan)) {
                                $previewBtn = '<a href="' . asset('uploaded_files/surat_kelurahan/' . $model->upload_surat_kelurahan) . '" class="btn btn-light-primary font-weight-bold mr-2 mt-1" target="_blank">Hasil</a>';
                            }
                            //warga
                              if ($isWarga === true) {
                                if ($model->approve_draft !== null) {
                                  if ($model->status_upload == 1) {
                                    return view('partials.buttons.cust-datatable', [
                                      'edit' => in_array('edit', $datatableButtons ) ? route('Surat.SuratPermohonan.edit', \Crypt::encryptString($model->surat_permohonan_id)) : null,
                                      'show' => in_array('show', $datatableButtons ) ? route('Surat.SuratPermohonan.show', \Crypt::encryptString($model->surat_permohonan_id)) : null,
                                    ]);
                                  }
                                  return view('partials.buttons.cust-datatable',[
                                      'show'         => in_array("show", $datatableButtons ) ? route('Surat.SuratPermohonan'.'.show', \Crypt::encryptString($model->surat_permohonan_id)) : null,
                                  ]) . $previewBtn;
                                }else{
                                  return view('partials.buttons.cust-datatable',[
                                      'show'         => in_array("show", $datatableButtons ) ? route('Surat.SuratPermohonan'.'.show', \Crypt::encryptString($model->surat_permohonan_id)) : null,
                                      'edit'         => in_array("edit", $datatableButtons ) ? route('Surat.SuratPermohonan'.'.edit', \Crypt::encryptString($model->surat_permohonan_id)) : null,
                                      'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $model->surat_permohonan_id : null
                                  ]);
                                }
                              }
                              //RT
                              if ($isRt === true) {
                                if ($model->approve_draft !== null && $model->petugas_rt_id !== null) {
                                  return view('partials.buttons.cust-datatable',[
                                      'show'         => in_array("show", $datatableButtons ) ? route('Surat.SuratPermohonan'.'.show', \Crypt::encryptString($model->surat_permohonan_id)) : null,
                                  ]);
                                }else{
                                  return view('partials.buttons.cust-datatable',[
                                      'show'         => in_array("show", $datatableButtons ) ? route('Surat.SuratPermohonan'.'.show', \Crypt::encryptString($model->surat_permohonan_id)) : null,
                                      'edit'         => in_array("edit", $datatableButtons ) ? route('Surat.SuratPermohonan'.'.edit', \Crypt::encryptString($model->surat_permohonan_id)) : null,
                                      'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $model->surat_permohonan_id : null
                                  ]);
                                }
                              }
                              //RW
                              if ($isRw === true) {
                                if ($model->approve_draft !== null && $model->petugas_rt_id !== null  && $model->petugas_rw_id !== null) {
                                  return view('partials.buttons.cust-datatable',[
                                      'show'         => in_array("show", $datatableButtons ) ? route('Surat.SuratPermohonan'.'.show', \Crypt::encryptString($model->surat_permohonan_id)) : null,
                                  ]);
                                }else{
                                  return view('partials.buttons.cust-datatable',[
                                      'show'         => in_array("show", $datatableButtons ) ? route('Surat.SuratPermohonan'.'.show', \Crypt::encryptString($model->surat_permohonan_id)) : null,
                                    //   'edit'         => in_array("edit", $datatableButtons ) ? route('Surat.SuratPermohonan'.'.edit', \Crypt::encryptString($model->surat_permohonan_id)) : null,
                                      'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $model->surat_permohonan_id : null
                                  ]);
                                }
                              }
                              //admin
                              if ($isAdmin === true) {
                                return view('partials.buttons.cust-datatable',[
                                    'show'         => in_array("show", $datatableButtons ) ? route('Surat.SuratPermohonan'.'.show', \Crypt::encryptString($model->surat_permohonan_id)) : null,
                                    'edit'         => in_array("edit", $datatableButtons ) ? route('Surat.SuratPermohonan'.'.edit', \Crypt::encryptString($model->surat_permohonan_id)) : null,
                                    'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $model->surat_permohonan_id : null
                                ]) . $previewBtn;
                              }
                          })
                          ->addColumn('status', function ($model) {
                                if ($model->petugas_rt_id == '' && !$model->tgl_approve_rt) {
                                    $approved = $model->tgl_permohonan;
                                    return '<span class="badge badge-lg badge-secondary text-left">Menunggu Approve RT <br/><br/> Dari tanggal: ' . date('d M Y', strtotime($approved)) . '</span>';
                                } else if ($model->petugas_rw_id == '' && !$model->tgl_approve_rw) {
                                    $approved = $model->tgl_approve_rt;
                                    return '<span class="badge badge-lg badge-secondary text-left">Menunggu Approve RW <br/><br/> Dari tanggal: ' . date('d M Y', strtotime($approved)) . '</span>';
                                } else if (($model->petugas_kelurahan_id == '' && !$model->tgl_approve_kelurahan) || ($model->kasi_id == '' && !$model->tgl_approve_kasi) || ($model->sekel_id == '' && !$model->tgl_approve_sekel) || ($model->approve_lurah == '' && !$model->tgl_approve_lurah)) {
                                    $approved = $model->tgl_approve_rw;
                                    return '<span class="badge badge-lg badge-secondary text-left">Menunggu Proses Kelurahan <br/><br/> Dari tanggal: ' . date('d M Y', strtotime($approved)) . '</span>';
                                } else if ($model->approve_lurah && $model->tgl_approve_lurah) {
                                    return '<span class="badge badge-lg badge-secondary text-left">Selesai</span>';
                                }
                            })
                          ->rawColumns(['action', 'status'])
                          ->make(true);
    }

    public function store($request)
    {   
        $input = $request->except('proengsoft_jsvalidation', 'upload_lampiran', 'lampiran_id');
        $input['approve_draft'] = true;

        if($request->hasFile('lampiran1')){
            $input['lampiran1'] = 'lampiran1_'.rand().'.'.$request->lampiran1->getClientOriginalExtension();
            $request->lampiran1->move(public_path('uploaded_files/surat'),$input['lampiran1']);
        }

        if($request->hasFile('lampiran2')){
            $input['lampiran2'] = 'lampiran2_'.rand().'.'.$request->lampiran2->getClientOriginalExtension();
            $request->lampiran2->move(public_path('uploaded_files/surat'),$input['lampiran2']);
        }

        if($request->hasFile('lampiran3')){
            $input['lampiran3'] = 'lampiran3_'.rand().'.'.$request->lampiran3->getClientOriginalExtension();
            $request->lampiran3->move(public_path('uploaded_files/surat'),$input['lampiran3']);
        }

        $url = 'https://dev2.kamarkerja.com:3333/message/text';
        $response = @get_headers($url);

        // if cant reach url
        if (!$response) return redirect()->back()->with('error', 'Maaf terjadi kesalahan'); 

        $whatsappKey = \DB::table('whatsapp_key')
            ->select('whatsapp_key')
            ->first()
            ->whatsapp_key ?? null;

        if (!$whatsappKey) return redirect()->back()->with('error','No Whatsapp belum disandingkan'); 
        
        try {
            $surat = $this->surat->create($input);

            if ($request->lampiran_id && $request->upload_lampiran) {
                foreach ($request->upload_lampiran as $key => $val) {
                    $imageName = 'lampiran'.rand().'.'.$val->getClientOriginalExtension();
                    $val->move(public_path('/uploaded_files/surat'), $imageName);
                    $upload_lampiran[] = $imageName;

                    if ($val && $request->lampiran_id[$key]) {
                        $lampiranSurat = [
                            'surat_permohonan_id' => $surat->surat_permohonan_id,
                            'lampiran_id' => $request->lampiran_id[$key],
                            'upload_lampiran' => $imageName,
                        ];
                        $suratPermohonan = SuratPermohonanLampiran::create($lampiranSurat);
                    }
                }
            }

            $data = SuratPermohonan::select('surat_permohonan.tgl_permohonan', 'anggota_keluarga.nama', 'anggota_keluarga.rt_id', 'anggota_keluarga.rw_id', 'jenis_surat.jenis_permohonan')
            ->join('jenis_surat', 'jenis_surat.jenis_surat_id', 'surat_permohonan.jenis_surat_id')
            ->join('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','surat_permohonan.anggota_keluarga_id')
            ->where('surat_permohonan.surat_permohonan_id', $surat->surat_permohonan_id)
            ->first();
        
            $tgl_permohonan = date('d M Y', strtotime($data->tgl_permohonan));
            
            $ketuaRT = \DB::table('anggota_keluarga')
                ->select('anggota_keluarga.mobile')
                ->where('anggota_keluarga.rt_id', $data->rt_id)
                ->where('anggota_keluarga.is_rt', true)
                ->first();
            
            $mobileRT = '62' . substr($ketuaRT->mobile, 1);

            $whatsapp_msgRT = "[INFORMASI SIAPMAJU], Terdapat permohonan suket/surat keterangan [$data->jenis_permohonan] atas nama $data->nama pada tanggal $tgl_permohonan. Mohon untuk memeriksa pada aplikasi";
            
            \Http::post("$url?key=$whatsappKey",[
                'id' => $mobileRT,
                'message' => $whatsapp_msgRT
            ]);

            \DB::commit();
            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function update($request, $id)
    {
      $data = $this->surat->findOrFail($id);
      $input = $request->except('proengsoft_jsvalidation', 'upload_lampiran', 'lampiran_id');
      $input['status_upload'] = 0;

      $updateLampiran = $this->suratPermohonanLampiran->where('surat_permohonan_id', $data->surat_permohonan_id)->get();

        if($request->hasFile('lampiran1')){
            $input['lampiran1'] = 'lampiran1_'.rand().'.'.$request->lampiran1->getClientOriginalExtension();
            $request->lampiran1->move(public_path('uploaded_files/surat'),$input['lampiran1']);
            
            \File::delete(public_path('uploaded_files/surat/'.$data->lampiran1));
        }

        if($request->hasFile('lampiran2')){
            $input['lampiran2'] = 'lampiran2_'.rand().'.'.$request->lampiran2->getClientOriginalExtension();
            $request->lampiran2->move(public_path('uploaded_files/surat'),$input['lampiran2']);

            \File::delete(public_path('uploaded_files/surat/'.$data->lampiran2));
        }

        if($request->hasFile('lampiran3')){
            $input['lampiran3'] = 'lampiran3_'.rand().'.'.$request->lampiran3->getClientOriginalExtension();
            $request->lampiran3->move(public_path('uploaded_files/surat'),$input['lampiran3']);

            \File::delete(public_path('uploaded_files/surat/'.$data->lampiran3));
        }

        if($request->catatan_kelurahan){
          $input['catatan_kelurahan'] = $data->catatan_kelurahan . PHP_EOL . PHP_EOL . '[Warga]:' . PHP_EOL . str_replace('<br />', PHP_EOL, nl2br($request->catatan_kelurahan));
        }

        try {
            $data->update($input);

            if ($request->lampiran_id && $request->upload_lampiran) {
                foreach ($request->upload_lampiran as $key => $val) {
                    $imageName = 'lampiran'.rand().'.'.$val->getClientOriginalExtension();
                    $val->move(public_path('/uploaded_files/surat'), $imageName);
                    $upload_lampiran[] = $imageName;

                    $currentLampiran = $this->suratPermohonanLampiran->where('surat_permohonan_id', $data->surat_permohonan_id)->where('lampiran_id', $request->lampiran_id[$key])->first();
                    \File::delete(public_path('uploaded_files/surat/'.$currentLampiran->upload_lampiran));

                    if ($val && $request->lampiran_id[$key]) {
                        $lampiranSurat = [
                            'surat_permohonan_id' => $data->surat_permohonan_id,
                            'lampiran_id' => $request->lampiran_id[$key],
                            'upload_lampiran' => $imageName,
                        ];

                        $currentLampiran->update($lampiranSurat);
                    }
                }
            }
           \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function selectNamaWarga($existId=false)
    {
        $model = \DB::table('anggota_keluarga')
                     ->select('anggota_keluarga_id','nama')
                    //  ->whereNotNull('anggota_keluarga.province_id')
                     ->get();

        $result = '<option></option>';

        foreach($model as $key => $val){

            $result .= '<option value="'.$val->anggota_keluarga_id.'" '.($val->anggota_keluarga_id == $existId ? "selected" : "").'>'.$val->nama.'</option>';
        }
  
        return $result;
    }


    public function selectNamaAnggota($existId = false)
    {   
        $isRT = \helper::checkUserRole('rt');
        $isAdmin = \helper::checkUserRole('admin');
        $isKepalaKeluarga = \helper::checkUserRole('kepalaKeluarga');
        $isWarga = \helper::checkUserRole('warga');

        $route_name = explode('.',\Route::currentRouteName());
        $isEdit = $route_name[2] == 'edit';

        $user = \DB::table('users')
                    ->select('users.anggota_keluarga_id','keluarga.rt_id','anggota_keluarga.keluarga_id')
                    ->join('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                    ->join('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                    ->where('users.anggota_keluarga_id',\Auth::user()->anggota_keluarga_id)
                    ->first();

        $db_anggota_keluarga = \DB::table('anggota_keluarga')
                                  ->join('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                                  ->when($isRT && !$isAdmin,function($query)use($user){
                                      $query->where('keluarga.rt_id',$user->rt_id);
                                  })
                                  ->when((!$isRT && !$isAdmin),function($query)use($user,$isKepalaKeluarga,$isWarga){
                                      if($isKepalaKeluarga && $isWarga){
                                        $query->where('anggota_keluarga.keluarga_id', $user->keluarga_id);
                                      }else{
                                        $query->where('anggota_keluarga_id', $user->anggota_keluarga_id);
                                      }
                                  })
                                  ->get();

        $selectNamaAnggota = '<option></option>';

        foreach ($db_anggota_keluarga as $key => $value) {

            $selectedRTCreate = $isRT && !$isAdmin && !$isEdit && $value->anggota_keluarga_id == $user->anggota_keluarga_id;
            $selectedRTEdit = $isRT && !$isAdmin && $isEdit && $value->anggota_keluarga_id == $existId;
            $selectedWarga = !$isRT && !$isAdmin && $value->anggota_keluarga_id == $user->anggota_keluarga_id;

            $selected = null;

            switch(true){
                case($selectedRTCreate):
                    $selected = 'selected';
                    break;
                case($selectedRTEdit):
                    $selected = 'selected';
                    break;
                case($selectedWarga):
                    $selected = 'selected';
                    break;
                default: $selected = ''; break;
            }
          
            $selectNamaAnggota .= '<option value="'.$value->anggota_keluarga_id.'"'.$selected.'>'.$value->nama.'</option>';
        }

        return $selectNamaAnggota;
    }

    public function getDataWarga($id)
    {   
        $warga = $this->warga
                     ->select('anggota_keluarga.nama','anggota_keluarga.alamat','anggota_keluarga.tgl_lahir','anggota_keluarga.rt_id','anggota_keluarga.rw_id','anggota_keluarga.kelurahan_id','anggota_keluarga.subdistrict_id','anggota_keluarga.city_id','anggota_keluarga.province_id')->findOrFail($id);

        $result = [
            'nama' => $warga->nama,
            'alamat' => $warga->alamat,
            'tgl_lahir' => $warga->tgl_lahir,
            'rt_id' => $warga->rt_id,
            'rw_id' => $warga->rw_id,
            'kelurahan_id' => $warga->kelurahan_id,
            'subdistrict_id' => $warga->subdistrict_id,
            'city_id' => $warga->city_id,
            'province_id' => $warga->province_id,
        ];

        return response()->json(['result' => 'success',
                                'data' => $result]);
    }

    public function destroy($id)
    {
          $model = $this->surat->findOrFail($id);
          $lampiran = SuratPermohonanLampiran::where('surat_permohonan_id', $model->surat_permohonan_id)->get();

          try{
            foreach ($lampiran as $key) {
                if($key->upload_lampiran){
                    \File::delete(public_path('uploaded_files/surat/'.$key->upload_lampiran));
                }
            }

              $model->destroy($id);
              return response()->json(['status' => 'success']);

          }catch(\Exception $e){
              \DB::rollback();
              throw $e;
          }
    }
}