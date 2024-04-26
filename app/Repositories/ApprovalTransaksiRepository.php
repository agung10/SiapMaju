<?php

namespace App\Repositories;

use App\Models\Transaksi\HeaderTrxKegiatan;
use App\Repositories\WhatsappKeyRepository;

class ApprovalTransaksiRepository
{
    public function __construct(HeaderTrxKegiatan $_HeaderTrxKegiatan, WhatsappKeyRepository $whatsapp)
    {
        $this->header = $_HeaderTrxKegiatan;
        $this->whatsapp = $whatsapp;
    }

    public function dataTables($exception = false)
    {
        $datatableButtons = method_exists(new $this->header, 'datatableButtons') ? $this->header->datatableButtons() : ['show', 'edit', 'destroy'];

        $logedUSer = \DB::table('users')
                         ->select('anggota_keluarga.is_rt','anggota_keluarga.is_rw','users.is_admin','keluarga.rt_id','keluarga.rw_id',
                                  'anggota_keluarga.anggota_keluarga_id')
                         ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                         ->leftJoin('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                         ->where('user_id',\Auth::user()->user_id)
                         ->first();

        $isRt = $logedUSer->is_rt == true;
        $isRw = $logedUSer->is_rw == true;
        $isAdmin = $logedUSer->is_admin == true;
        $isWarga = $isRt != true && $isRw != true && $isAdmin != true;

        $model = \DB::table('header_trx_kegiatan')
                     ->select('header_trx_kegiatan.header_trx_kegiatan_id','transaksi.nama_transaksi','kat_kegiatan.nama_kat_kegiatan','anggota_keluarga.nama','header_trx_kegiatan.no_pendaftaran','header_trx_kegiatan.no_bukti','blok.nama_blok')
                     ->join('transaksi','transaksi.transaksi_id','header_trx_kegiatan.transaksi_id')
                     ->join('kat_kegiatan','kat_kegiatan.kat_kegiatan_id','header_trx_kegiatan.kat_kegiatan_id')
                     ->join('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','header_trx_kegiatan.anggota_keluarga_id')
                     ->join('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                     ->join('blok','blok.blok_id','keluarga.blok_id')
                     ->whereNull('tgl_approval')
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
                     ->when($exception === false,function($query) use ($logedUSer, $isRt, $isRw){
                        $query->when($isRt == true, function($query) use ($logedUSer){
                            $query->where('anggota_keluarga.rt_id', $logedUSer->rt_id);
                        }); 
                        $query->when($isRw == true, function($query) use ($logedUSer){
                            $query->where('anggota_keluarga.rw_id', $logedUSer->rw_id);
                        }); 
                         $query->whereIn('kat_kegiatan.kat_kegiatan_id',function($query){
                                $query->select('kegiatan.kat_kegiatan_id')
                                   ->from('kegiatan')
                                   ->whereIn('kegiatan.rt_id',function($query){
                                       $query->select('rt.rt_id')
                                             ->from('rt')
                                             ->where('rt.rt','!=','DKM');
                                   });
                        });
                     });
            
        return \DataTables::of($model)
                            ->addIndexColumn()
                            ->addColumn('action', function($data) use ($datatableButtons,$isWarga,$exception) {
                                $route = $exception == 'dkm' ? route('Transaksi.ApprovalDKM.show',\Crypt::encryptString($data->header_trx_kegiatan_id)) : route('Transaksi.Approval.show',\Crypt::encryptString($data->header_trx_kegiatan_id));

                                        return view('partials.buttons.cust-datatable',[
                                            'customButton' => ['route' => $route,
                                                               'name' => 'Approval'
                                                              ]
                                        ]);
                            })
                            ->addColumn('nama',function($data){
                                return "$data->nama ($data->nama_blok)";  
                            })
                            ->rawColumns(['action'])
                            ->make(true);
    }

    public function approval($id)
    {   
        $transaction = false;

        \DB::beginTransaction();
        try{    

            $model = $this->header
                          ->select('*')
                          ->leftJoin('transaksi','transaksi.transaksi_id','header_trx_kegiatan.transaksi_id','kegiatan.nama_kegiatan')
                          ->leftJoin('kat_kegiatan','kat_kegiatan.kat_kegiatan_id','header_trx_kegiatan.kat_kegiatan_id')
                          ->join('kegiatan','kegiatan.kegiatan_id','header_trx_kegiatan.kegiatan_id')
                          ->join('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','header_trx_kegiatan.anggota_keluarga_id')
                          ->join('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                          ->join('rt','rt.rt_id','keluarga.rt_id')
                          ->findOrFail($id);

            $kegiatan    =  $model->nama_kegiatan;
            $time        = date('Y-m-d, H:i:s');
            $noBukti     = date('Ymdhis').'/'.$model->kode_kat.'/'.$model->nama_transaksi.'/'.date('Y-m',strtotime($model->tgl_pendaftaran));
            $createdDate = explode('/',\helper::datePrint(date('Y-m-d',strtotime($model->tgl_pendaftaran))));
            $isDKM       = explode('.',\Route::currentRouteName())[1] === 'ApprovalDKM';
            
            $pengurus = $isDKM ? 'DKM' : $model->rt;

            $whatsappMsg = "Terimakasih atas Pembayaran $kegiatan dan  sudah kami terima dengan No Transaksi $noBukti, untuk bulan $createdDate[1] $createdDate[2].Pengurus $pengurus [SiapMaju-$time]";

            $whatsappResponse = $this->whatsapp->send($whatsappMsg, $model->mobile);
        
            if(!$whatsappResponse['status']) {
                redirect()->back()->with('error','No Whatsapp belum disandingkan');
            } 

            $input = [  
                'no_bukti' => $noBukti,
                'tgl_approval' => date('Y-m-d'),
                'user_approval' => \Auth::id(),
                'user_updated_id' => \Auth::id(),
            ];
        
            $model->update($input);

            \DB::commit();
            $transaction = true;
        }catch(\Exception $e){
            \DB::rollback();
            throw $e;
        }

        if($isDKM){
            return redirect()->route('Transaksi.ApprovalDKM.index')->with('success','Approval Transaksi Berhasil!');
        }else{
            return redirect()->route('Transaksi.Approval.index')->with('success','Approval Transaksi Berhasil!');
        }
    }
}