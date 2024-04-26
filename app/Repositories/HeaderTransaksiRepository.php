<?php

namespace App\Repositories;

use App\Models\Master\Kegiatan\{Kegiatan,KatKegiatan};
use App\Models\Master\Keluarga\{AnggotaKeluarga};
use App\Models\Transaksi\{HeaderTrxKegiatan,DetailTrxKegiatan};
use App\Helpers\helper;
use App\User;

class HeaderTransaksiRepository
{
    public function __construct(KatKegiatan $_KatKegiatan, AnggotaKeluarga $_AnggotaKeluarga, HeaderTrxKegiatan $_HeaderTrxKegiatan, Kegiatan $_Kegiatan,DetailTrxKegiatan $_DetailTrxKegiatan, User $user)
    {
       $this->katKegiatan     = $_KatKegiatan;
       $this->anggotaKeluarga = $_AnggotaKeluarga;
       $this->headerTransaksi = $_HeaderTrxKegiatan;
       $this->detailTransaksi = $_DetailTrxKegiatan;
       $this->kegiatan        = $_Kegiatan;
       $this->user            = $user;
    }

    public function dataTables()
    {   
        $datatableButtons = method_exists(new $this->headerTransaksi, 'datatableButtons') ? $this->headerTransaksi->datatableButtons() : ['show', 'edit', 'destroy'];

        $logedUSer = \DB::table('users')
                         ->select('anggota_keluarga.is_rt','anggota_keluarga.is_rw','users.is_admin','keluarga.rt_id','keluarga.rw_id',
                                  'anggota_keluarga.anggota_keluarga_id','rt.rt')
                         ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                         ->leftJoin('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                         ->leftJoin('rt','rt.rt_id','keluarga.rt_id')
                         ->where('user_id',\Auth::user()->user_id)
                         ->first();

        $isRt = $logedUSer->is_rt == true;
        $isRw = $logedUSer->is_rw == true;
        $isAdmin = $logedUSer->is_admin == true;
        $isDKM = $logedUSer->rt == 'DKM';
        $isWarga = $isRt != true && $isRw != true && $isAdmin != true;

        $model = \DB::table('header_trx_kegiatan')
                     ->select('header_trx_kegiatan.header_trx_kegiatan_id','transaksi.nama_transaksi','kat_kegiatan.nama_kat_kegiatan','anggota_keluarga.nama','header_trx_kegiatan.no_pendaftaran','header_trx_kegiatan.no_bukti', 'header_trx_kegiatan.tgl_approval', 'header_trx_kegiatan.updated_at','blok.nama_blok')
                     ->join('transaksi','transaksi.transaksi_id','header_trx_kegiatan.transaksi_id')
                     ->join('kat_kegiatan','kat_kegiatan.kat_kegiatan_id','header_trx_kegiatan.kat_kegiatan_id')
                     ->join('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','header_trx_kegiatan.anggota_keluarga_id')
                     ->join('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                     ->join('blok','blok.blok_id','keluarga.blok_id')
                     ->when($isWarga,function($query){
                        $query->where('header_trx_kegiatan.anggota_keluarga_id',\Auth::user()->anggota_keluarga_id);
                     })
                     ->when($isRt,function($query)use($logedUSer){
                        $query->where('anggota_keluarga.rt_id',$logedUSer->rt_id);
                     })
                     ->when($isRw,function($query)use($logedUSer){
                        $query->where('anggota_keluarga.rw_id',$logedUSer->rw_id);
                     })
                     ->whereIn('kat_kegiatan.kat_kegiatan_id',function($query){
                         $query->select('kegiatan.kat_kegiatan_id')
                                ->from('kegiatan')
                                ->whereIn('kegiatan.rt_id',function($query){
                                    $query->select('rt.rt_id')
                                          ->from('rt')
                                          ->where('rt.rt','!=','DKM');
                                });
                     })
                    ->orderBy('header_trx_kegiatan_id', 'DESC');

        return \DataTables::of($model)
                            ->addIndexColumn()
                            ->addColumn('action', function($data) use ($datatableButtons,$isWarga) {
                                if(empty($data->no_bukti)){
                                    if($isWarga){
                                        return view('partials.buttons.cust-datatable',[
                                            'show'         => in_array("show", $datatableButtons ) ? route('Transaksi.Header'.'.show', \Crypt::encryptString($data->header_trx_kegiatan_id)) : null,
                                            'edit'         => in_array("edit", $datatableButtons ) ? route('Transaksi.Header'.'.edit', \Crypt::encryptString($data->header_trx_kegiatan_id)) : null,
                                            'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $data->header_trx_kegiatan_id : null,
                                        ]);
                                    }else{
                                        return view('partials.buttons.cust-datatable',[
                                            'show'         => in_array("show", $datatableButtons ) ? route('Transaksi.Header'.'.show', \Crypt::encryptString($data->header_trx_kegiatan_id)) : null,
                                            'edit'         => in_array("edit", $datatableButtons ) ? route('Transaksi.Header'.'.edit', \Crypt::encryptString($data->header_trx_kegiatan_id)) : null,
                                            'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $data->header_trx_kegiatan_id : null,
                                        ]);
                                    }
                                }else{
                                    return view('partials.buttons.cust-datatable',[
                                        'show'         => in_array("show", $datatableButtons ) ? route('Transaksi.Header'.'.show', \Crypt::encryptString($data->header_trx_kegiatan_id)) : null,
                                        'customButton' => ['route' => route('Transaksi.Header.create_pdf', \Crypt::encryptString($data->header_trx_kegiatan_id)),
                                                           'name' => 'Print'
                                                          ]
                                    ]);
                                }
                            })
                            ->addColumn('nama',function($data){
                                return "$data->nama ($data->nama_blok)";  
                            })
                            ->rawColumns(['action'])
                            ->make(true);
    }

    public static function selectTransaksi($existId=false,$order=false,$sortOrder=false,$exception=false)
    {
       
        $checkRole = \helper::checkUserRole('all');
        $isWarga = $checkRole['isWarga'];
        $isDKM = $checkRole['isDKM'];
        $isAdmin = $checkRole['isAdmin'];   
        $isRt = $checkRole['isRt'];
        $isRw =$checkRole['isRw'];

        $model = \DB::table('transaksi')
                     ->select('transaksi.transaksi_id','transaksi.nama_transaksi')
                     ->when(!$exception,function($query)use($isWarga){
                         $query->when($isWarga,function($query){
                            $query->where('transaksi.transaksi_id',1);
                        });
                     })
                     ->when($exception == 'dkm',function($query)use($isDKM,$isAdmin,$isRt,$isRw,$isWarga){
                        $query->when(!$isDKM && !$isAdmin,function($query){
                            $query->where('transaksi.transaksi_id',1);
                        });
                     })
                     ->when($order,function($query)use($order,$sortOrder){
                        $query->orderBy($order,$sortOrder);
                     })
                     ->get();
        
        $result = '<option></option>';
                         
        foreach($model as $key => $val){
            $selected = $val->transaksi_id == $existId ? "selected"
                                                       : ($isWarga && $val->transaksi_id === 1 ? "selected" : ""); 

            $result .= '<option value="'.$val->transaksi_id.'" '.$selected.'>'.$val->nama_transaksi.'</option>';
        }
  
        return $result;
    }


    public function selectKatKegiatan($existId=false,$exception=false)
    {   
        $logedUSer = \DB::table('users')
                         ->select('anggota_keluarga.is_rt','anggota_keluarga.is_rw','users.is_admin','keluarga.rt_id','keluarga.rw_id')
                         ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                         ->leftJoin('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                         ->where('user_id',\Auth::user()->user_id)
                         ->first();

        $isRt = $logedUSer->is_rt == true;
        $isRw = $logedUSer->is_rw == true;
        $isAdmin = $logedUSer->is_admin == true;
        $isWarga = $isRt != true && $isRw != true && $isAdmin != true;

        $model = \DB::table('kat_kegiatan')
                     ->select('kat_kegiatan.kat_kegiatan_id','kat_kegiatan.nama_kat_kegiatan','kat_kegiatan.kode_kat')
                     ->when($exception == 'dkm',function($query){
                        $query->whereNotIn('kat_kegiatan.kat_kegiatan_id',function($query){
                           $query->select('kegiatan.kat_kegiatan_id')
                                  ->from('kegiatan')
                                  ->whereNotIn('kegiatan.rt_id',function($query){
                                      $query->select('rt.rt_id')
                                            ->from('rt')
                                            ->where('rt.rt','DKM');
                                  });
                       });
                    })
                    ->when($exception === false,function($query)use($isWarga,$isRt,$isRw,$logedUSer){
                        $query->whereIn('kat_kegiatan.kat_kegiatan_id',function($query){
                           $query->select('kegiatan.kat_kegiatan_id')
                                  ->from('kegiatan')
                                  ->whereIn('kegiatan.rt_id',function($query){
                                      $query->select('rt.rt_id')
                                            ->from('rt')
                                            ->where('rt.rt','!=','DKM');
                                  });
                       });
                       $query->when($isWarga || $isRt,function($query)use($logedUSer){
                            $query->whereIn('kat_kegiatan.kat_kegiatan_id',function($query)use($logedUSer){
                                $query->select('kat_kegiatan_id')
                                      ->from('kegiatan')
                                      ->where('rt_id',$logedUSer->rt_id);
                            });
                        });
                        $query->when($isRw,function($query)use($logedUSer){
                            $query->whereIn('kat_kegiatan.kat_kegiatan_id',function($query)use($logedUSer){
                                $query->select('kat_kegiatan_id')
                                      ->from('kegiatan')
                                      ->where('rw_id',$logedUSer->rw_id);
                            });
                        });
                    })
                    ->get();
   
        $result = '<option></option>';

        foreach($model as $key => $val){
            $result .= '<option value="'.$val->kat_kegiatan_id.'" '.($val->kat_kegiatan_id == $existId ? "selected" : "").'>'.$val->nama_kat_kegiatan.'('.$val->kode_kat.')'.'</option>';
        }
  
        return $result;
    }

    public function selectKodeKatKegiatan($existId=false)
    {   
        $model = \DB::table('kat_kegiatan')
                     ->select('kat_kegiatan.kat_kegiatan_id','kat_kegiatan.nama_kat_kegiatan','kat_kegiatan.kode_kat')
                     ->get();
        
        $result = '<option></option>';

        foreach($model as $key => $val){
            $result .= '<option value="'.$val->kode_kat.'" '.($val->kat_kegiatan_id == $existId ? "selected" : "").'>'.$val->kode_kat.'</option>';
        }
  
        return $result;
    }

    public function selectKepalaKeluarga($existId=false,$exception=false)
    {   
        $logedUSer = \DB::table('users')
                         ->select('anggota_keluarga.is_rt','anggota_keluarga.is_rw','anggota_keluarga.is_dkm','users.is_admin','keluarga.rt_id','keluarga.rw_id',
                                  'anggota_keluarga.anggota_keluarga_id','rt.rt')
                         ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                         ->leftJoin('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                         ->leftJoin('rt','rt.rt_id','keluarga.rt_id')
                         ->where('user_id',\Auth::user()->user_id)
                         ->first();

        $isRt = $logedUSer->is_rt == true;
        $isRw = $logedUSer->is_rw == true;
        $isAdmin = $logedUSer->is_admin == true;
        $isDKM = $logedUSer->is_dkm == true;
        $isWarga = $isRt != true && $isRw != true && $isAdmin != true;
        
        $model = \DB::table('anggota_keluarga')
                     ->select('anggota_keluarga.anggota_keluarga_id','anggota_keluarga.nama')
                     ->join('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                     ->when(!$exception,function($query)use($logedUSer,$isWarga,$isRt,$isRw){
                        $query->when($isWarga,function($query)use($logedUSer){
                            $query->where('anggota_keluarga.anggota_keluarga_id',$logedUSer->anggota_keluarga_id);
                        });
                        $query->when($isRt,function($query)use($logedUSer){
                            $query->where('keluarga.rt_id',$logedUSer->rt_id);
                        });
                        $query->when($isRw,function($query)use($logedUSer){
                            $query->where('keluarga.rw_id',$logedUSer->rw_id);
                        });
                     })
                     ->when($exception == 'dkm',function($query)use($isDKM,$isAdmin,$logedUSer){
                         $query->when(!$isDKM && !$isAdmin,function($query)use($logedUSer){
                            $query->where('anggota_keluarga.anggota_keluarga_id',$logedUSer->anggota_keluarga_id);
                         });
                     })
                     ->where('anggota_keluarga.hub_keluarga_id',1)
                     ->orderBy('anggota_keluarga.nama')
                     ->get();
        
        $result = '<option></option>';

        foreach($model as $key => $val){
            $result .= '<option value="'.$val->anggota_keluarga_id.'" '.($val->anggota_keluarga_id == $existId ? "selected" : "").'>'.$val->nama.'</option>';
        }
  
        return $result;
    }

    public function getJenisTransaksi($request,$existId=false)
    {
        $model = \DB::table('jenis_transaksi')
                     ->select('jenis_transaksi_id','nama_jenis_transaksi','satuan')
                     ->where('kegiatan_id',$request->kegiatan_id)
                     ->get();

        $result = '<option '.($existId ? '' : 'selected').' disabled>Pilih Jenis Transaksi</option>';

        foreach($model as $key => $val){
            $result .= '<option value="'.$val->jenis_transaksi_id.'"'.($val->jenis_transaksi_id == $existId ? 'selected' : '').'>'.$val->nama_jenis_transaksi.'('.$val->satuan.')</option>';
        }

        return response()->json(compact('result'));
    }   
                     
    public function getKodeKegiatan($request)
    {
        $model = $this->katKegiatan
                      ->select('kode_kat')
                      ->findOrFail($request->kat_kegiatan_id);

        $result = $this->selectKegiatan($request->kat_kegiatan_id);

        return response()->json(['kode_kat' => $model->kode_kat,
                                 'selectElKegiatan' => $result
                                ]);
    }

    public function selectKegiatan($kat_kegiatan_id,$existId=false)
    {   
        $logedUSer = \DB::table('users')
                         ->select('anggota_keluarga.is_rt','anggota_keluarga.is_rw','users.is_admin','keluarga.rt_id','keluarga.rw_id')
                         ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                         ->leftJoin('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                         ->where('user_id',\Auth::user()->user_id)
                         ->first();

        $currentBroswerURL = $_SERVER['HTTP_REFERER'];
        $isDKMPage = strpos($currentBroswerURL,'DKM') > 0;

        $isRt = $logedUSer->is_rt == true;
        $isRw = $logedUSer->is_rw == true;
        $isAdmin = $logedUSer->is_admin == true;
        $isWarga = $isRt != true && $isRw != true && $isAdmin != true;
        

        $kegiatan = $this->kegiatan
                        ->select('kegiatan.kegiatan_id','kegiatan.nama_kegiatan')
                        ->when(!$isDKMPage,function($query)use($logedUSer,$isWarga,$isRt,$isRw){
                            $query->when($isWarga || $isRt,function($query)use($logedUSer){
                                $query->where('kegiatan.rt_id',$logedUSer->rt_id);
                            });
                            $query->when($isRw,function($query)use($logedUSer){
                                $query->where('kegiatan.rw_id',$logedUSer->rw_id);
                            });
                        })
                        ->where('kegiatan.kat_kegiatan_id',$kat_kegiatan_id)
                        ->get();

        $result = '<option disabled selected>Pilih Kegiatan</option>';

        foreach($kegiatan as $key => $val){
            $result .= '<option value="'.$val->kegiatan_id.'" '.($val->kegiatan_id == $existId ? "selected" : "").'>'.$val->nama_kegiatan.'</option>';
        }
  
        return $result;
    }

    public function getKepalaKeluarga($request,$existId=false)
    {    
        $kepala_keluarga = $this->anggotaKeluarga
                                ->select('keluarga_id','alamat')
                                ->findOrFail($request->kepala_keluarga_id);

        $anggota_keluarga = \DB::table('anggota_keluarga')
                                ->select('anggota_keluarga.anggota_keluarga_id','anggota_keluarga.nama')
                                ->where('anggota_keluarga.keluarga_id',$kepala_keluarga->keluarga_id)
                                ->get();

        $result = '<select  name="nama_detail_trx"  onChange="selectKeluargaHandler(event)" class="form-control transRow detailKeluarga">';
        $result .= '<option disabled'.($existId ? '' : 'selected').'>Pilih Anggota Keluarga</option>';

        foreach($anggota_keluarga as $key => $val){
            $result .= '<option value="'.$val->nama.'"'.($val->nama == $existId ? 'selected' : '').' >'.$val->nama.'</option>';
        }

        $result .= '<option value="get more">--Tambah Anggota Keluarga--</option>';
        $result .= '</select>';
       
        return response()->json(['result' => $result,
                                 'alamat' => $kepala_keluarga->alamat
                                ]);
    }

    public function store($request)
    {   
        $input = $request->all();
        
        $transaction = false;
        $rules = ['transaksi_id' => 'required',
                  'kat_kegiatan_id' => 'required',
                  'kegiatan_id' => 'required',
                  'anggota_keluarga_id' => 'required',
                  'no_pendaftaran' => 'required',
                  'tgl_pendaftaran' => 'required'
                ];

        $validator = helper::validation($request->all(),$rules);

        if($request->hasFile('bukti_pembayaran')){
            $input['bukti_pembayaran'] = 'bukti_pembayaran_'.rand().'.'.$request->bukti_pembayaran->getClientOriginalExtension();

            $request->bukti_pembayaran->move(public_path('upload/transaksi_kegiatan'),$input['bukti_pembayaran']);
        }

        \DB::beginTransaction();

        if($validator->fails()){
            return response()->json(['status' => 'failed', 'errors' => $validator->getMessageBag()->toArray()]);
        }

        try{
            
            $save = $this->headerTransaksi->create($input);

            \DB::commit();

            return response()->json(['status' => 'success',
                                     'header_id' => $save->header_trx_kegiatan_id
                                    ]);

        }catch(\Exception $e){
            \DB::rollback();
            throw $e;
        }
    }

    public function update($request,$id)
    {   
        $model = $this->headerTransaksi->findOrFail($id);

        $inputs = $request->all();

        $inputs['user_updated_id'] = \Auth::user()->user_id;

        $rules = ['transaksi_id' => 'required',
                  'kat_kegiatan_id' => 'required',
                  'kegiatan_id' => 'required',
                  'anggota_keluarga_id' => 'required',
         ];

        $validator = helper::validation($request->all(),$rules);

        if($validator->fails()){
            return response()->json(['status' => 'failed','errors' => $validator->getMessageBag()->toArray()]);
        }

        if($request->hasFile('bukti_pembayaran')){
            $modelTransaksi = $this->headerTransaksi->findOrFail($id);

            $inputs['bukti_pembayaran'] = 'bukti_pembayaran_'.rand().'.'.$request->bukti_pembayaran->getClientOriginalExtension();

            $request->bukti_pembayaran->move(public_path('upload/transaksi_kegiatan'),$inputs['bukti_pembayaran']);

            \File::delete(public_path('upload/transaksi_kegiatan/'.$modelTransaksi->bukti_pembayaran));
        }

        \DB::beginTransaction();

        try{

            $model->update($inputs);

            \DB::commit();

            return response()->json(['status' => 'success']);


        }catch(\Exception $e){
            \DB::rollback();
            throw $e;
        }
    }

    public function saveDetail($request)
    {
        $input = $request->all();

        $transaction = false;
        
        foreach($input as $key => $val){
            $rules = ['header_trx_kegiatan_id' => 'required',
                  'jenis_transaksi_id' => 'required',
                  'nama_detail_trx' => 'required',
                  'nilai' => 'required',
                  'jumlah' => 'required',
                  'total' => 'required'
                ];

            $validator = helper::validation($val,$rules);
            \DB::beginTransaction();

            if($validator->fails()){
                return response()->json(['status' => 'failed', 'errors' => $validator->getMessageBag()->toArray()]);
            }

            try{
                
                $this->detailTransaksi->create($val);

                \DB::commit();

            }catch(\Exception $e){
                \DB::rollback();
                throw $e;
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function updateDetail($request,$id)
    {
        $input = $request->all();

        foreach($input as $key => $val){
            $rules = ['header_trx_kegiatan_id' => 'required',
                      'jenis_transaksi_id' => 'required',
                      'nama_detail_trx' => 'required',
                      'nilai' => 'required',
                      'jumlah' => 'required',
                      'total' => 'required'
                     ];
            
            $storeData = ['header_trx_kegiatan_id' => $val['header_trx_kegiatan_id'],
                          'jenis_transaksi_id' => $val['jenis_transaksi_id'],
                          'nama_detail_trx' => $val['nama_detail_trx'],
                          'nilai' => $val['nilai'],
                          'jumlah' => $val['jumlah'],
                          'total' => $val['total']
                         ];
       
            $validator = helper::validation($storeData,$rules);
            \DB::beginTransaction();

            if($validator->fails()){
                return response()->json(['status' => 'failed', 'errors' => $validator->getMessageBag()->toArray()]);
            }

            try{
                     
                if($val['detail_trx_kegiatan_id']){
                    $this->detailTransaksi
                         ->findOrFail($val['detail_trx_kegiatan_id'])
                         ->update($storeData);
                }else{
                    $this->detailTransaksi->create($storeData);
                }
                     

                \DB::commit();  

            }catch(\Exception $e){
                \DB::rollback();
                throw $e;
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function deleteDetail($id)
    {   
        \DB::beginTransaction();

        try{

            $this->detailTransaksi->destroy($id);
            \DB::commit();

        }catch(\Exception $e){
            \DB::rollback();
            throw $e;
        }
        

        return response()->json(['status' => 'success']);
    }

    public function show($id)
    {
        return $this->headerTransaksi
                    ->select('*')
                    ->leftJoin('transaksi','transaksi.transaksi_id','header_trx_kegiatan.transaksi_id')
                    ->leftJoin('kat_kegiatan','kat_kegiatan.kat_kegiatan_id','header_trx_kegiatan.kat_kegiatan_id')
                    ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','header_trx_kegiatan.anggota_keluarga_id')
                    ->leftJoin('kegiatan','kegiatan.kegiatan_id','header_trx_kegiatan.kegiatan_id')
                    ->findOrFail($id);
    }

    public function destroy($id)
    {   
        $modelTransaksi = $this->headerTransaksi->findOrFail($id);

        \File::delete(public_path('upload/transaksi_kegiatan/'.$modelTransaksi->bukti_pembayaran));

        \DB::beginTransaction();

        try{
            $this->detailTransaksi
                 ->where('header_trx_kegiatan_id',$id)
                 ->delete();

            $this->headerTransaksi->destroy($id);
            \DB::commit();

            return response()->json(['status' => 'success']);
        }catch(\Exception $e){
            \DB::rollback();
            throw $e;
        }
    }

    public function detailTransaksi($id)
    {
        return \DB::table('detail_trx_kegiatan')
                    ->select('header_trx_kegiatan.header_trx_kegiatan_id','header_trx_kegiatan.transaksi_id','header_trx_kegiatan.kat_kegiatan_id','header_trx_kegiatan.kegiatan_id','header_trx_kegiatan.anggota_keluarga_id','header_trx_kegiatan.no_pendaftaran','header_trx_kegiatan.tgl_pendaftaran','header_trx_kegiatan.no_bukti','header_trx_kegiatan.tgl_approval','header_trx_kegiatan.user_approval','header_trx_kegiatan.user_updated_id','header_trx_kegiatan.created_at','header_trx_kegiatan.bukti_pembayaran','detail_trx_kegiatan.detail_trx_kegiatan_id','detail_trx_kegiatan.header_trx_kegiatan_id','detail_trx_kegiatan.jenis_transaksi_id','detail_trx_kegiatan.nama_detail_trx','detail_trx_kegiatan.nilai','detail_trx_kegiatan.jumlah','detail_trx_kegiatan.total','detail_trx_kegiatan.tgl_approval','detail_trx_kegiatan.user_approval','detail_trx_kegiatan.user_updated_id','detail_trx_kegiatan.created_at','detail_trx_kegiatan.updated_at','jenis_transaksi.jenis_transaksi_id','jenis_transaksi.kegiatan_id','jenis_transaksi.nama_jenis_transaksi','jenis_transaksi.satuan','jenis_transaksi.user_updated','jenis_transaksi.created_at','jenis_transaksi.updated_at')
                    ->leftJoin('header_trx_kegiatan','header_trx_kegiatan.header_trx_kegiatan_id','detail_trx_kegiatan.header_trx_kegiatan_id')
                    ->leftJoin('jenis_transaksi','jenis_transaksi.jenis_transaksi_id','detail_trx_kegiatan.jenis_transaksi_id')
                    ->where('detail_trx_kegiatan.header_trx_kegiatan_id',$id)
                    ->get();
    }

    public function detailTable($id)
    {   
        $model = $this->detailTransaksi($id);

        $result = '';
        if(count($model) > 0){
            foreach($model as $key => $val){
                $requestKegiatanId = (object) ['kegiatan_id' => $val->kegiatan_id];
                
                $result .= '<tr data-id="'.$val->detail_trx_kegiatan_id.'">';
                $result .= '<form class="formDetailTransakasi">';
                $result .= '<td>';
                $result .= '<input onChange="inputNamaChangeHandler(event)" name="nama_detail_trx" type="text" value="'.$val->nama_detail_trx.'" class="form-control transRow" placeholder="Nama Anggota"/>';
                $result .= '</td>';
                $result .= '<td>';
                $result .= '<select onChange="detailTransChangeHandler(event)" name="jenis_transaksi_id" class="form-control transRow detailTransaksi">';
                $result .= $this->getJenisTransaksi($requestKegiatanId,$val->jenis_transaksi_id)->original['result'];
                $result .= '</select>';
                $result .= '</td>';
                $result .= '<td>';
                $result .= '<input name="nilai" oninput="sumRow(event)" value="'.$val->nilai.'" type="number" class="form-control transRow" placeholder="nilai"/>';
                $result .= '</td>';
                $result .= '<td>';
                $result .= '<input name="jumlah" oninput="sumRow(event)" value="'.$val->jumlah.'" type="number" class="form-control transRow" placeholder="jumlah"/>';
                $result .= '</td>';
                $result .= '<td>';
                $result .= '<input name="total" readonly type="text" value="'.$val->total.'" class="form-control transRow" placeholder="total"/>';
                $result .= '</td>';
                $result .= '<td>';
                if($key == 0){
                    $result .= '<button class="btn btn-primary mt-3 addRow"><i class="flaticon2-plus"></i></button>';
                }else if($key > 0){
                    $result .= '<button class="btn btn-danger mt-3" onClick="deleteRow(event)"><i class="flaticon2-cross"></i></button>';
                }
                $result .= '</td>';
                $result .= '</form>';
                $result .= '</tr>';
            }
        }else{
            $result .= '<tr>';
            $result .= '<form class="formDetailTransakasi">';
            $result .= '<td>';
            $result .= '<input name="nama_detail_trx" onChange="inputNamaChangeHandler(event)" type="text" class="form-control transRow" placeholder="Nama Anggota"/>';
            $result .= '</td>';
            $result .= '<td>';
            $result .= '<select onClick="getJenisTransaksi(event)"  onChange="detailTransChangeHandler(event)" name="jenis_transaksi_id" class="form-control transRow detailTransaksi">';
            $result .= '<option disabled selected>Pilih Jenis Transaksi</option>';
            $result .= '</select>';
            $result .= '</td>';
            $result .= '<td>';
            $result .= '<input name="nilai" oninput="sumRow(event)" type="number" class="form-control transRow" placeholder="nilai"/>';
            $result .= '</td>';
            $result .= '<td>';
            $result .= '<input name="jumlah" oninput="sumRow(event)" type="number" class="form-control transRow" placeholder="jumlah"/>';
            $result .= '</td>';
            $result .= '<td>';
            $result .= '<input name="total" readonly type="text" class="form-control transRow" placeholder="total"/>';
            $result .= '</td>';
            $result .= '<td>';
            $result .= '<button class="btn btn-primary mt-3 addRow"><i class="flaticon2-plus"></i></button>';
            $result .= '</td>';
            $result .= '</form>';
            $result .= '</tr>';
        }
        
        
        return $result;
    }

    public function printPdf($headerTrxKegiatanId)
    {
        $id              = \Crypt::decryptString($headerTrxKegiatanId);
        $data            = $this->show($id);
        $regPendaftaran  = explode('/',$data->no_pendaftaran)[0];
        $noBukti         = !empty($data->no_bukti) ? explode('/',$data->no_pendaftaran)[0] : '';
        $detailTransaksi = $this->detailTransaksi($id);
        $tanggal         = \helper::datePrint($data->tgl_pendaftaran);

        $petugas = $this->user
                        ->select('anggota_keluarga.nama','users.email','users.is_admin')
                        ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                        ->where('users.user_id',$data->user_approval)
                        ->first();
              
        $pdf = \PDF::loadView('Transaksi.Approval.printPDF', compact('data','tanggal','detailTransaksi','petugas'))
              ->setPaper('A5', 'portrait')
              ->setOptions(['dpi' => 80,'fontHeightRatio' => 1,'defaultFont' => 'calibri']);
        
      

        return $pdf->stream();
    }
    
}
