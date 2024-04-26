<?php

namespace App\Repositories\UMKM;

use App\Models\Master\Keluarga\AnggotaKeluarga;
use App\Models\UMKM\{Umkm,UmkmMedsos,UmkmProduk};
use App\Repositories\UMKM\UmkmProdukRepository;
use App\Repositories\BaseRepository;

class UmkmRepository extends BaseRepository
{
    public function __construct(Umkm $_Umkm, UmkmMedsos $_UmkmMedsos, UmkmProdukRepository $_UmkmProdukRepository, UmkmProduk $_UmkmProduk)
    {
        $this->model = $_Umkm;
        $this->umkm_medsos = $_UmkmMedsos;
        $this->umkm_produk = $_UmkmProduk;
        $this->repoProduk = $_UmkmProdukRepository;
    }

    public function storeUmkm($request)
    {
        $storeImage         = true;
        $returnRecord       = true;
        $request['promosi'] = $request->promosi ?? false;
        $request['slug']    = \Str::slug($request->nama);
        $input =  $request->except('proengsoft_jsvalidation', 'medsos_id', 'medsos_url');
        
        \DB::beginTransaction();
        try {
            if ($request->hasFile('image')) {
                $input['image'] = 'image' . rand() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('uploaded_files/umkm'), $input['image']);
            }

            $umkm = $this->model->create($input, $storeImage, $returnRecord);

            $anggotaKeluarga = AnggotaKeluarga::select('anggota_keluarga_id')->where('anggota_keluarga_id', $umkm->anggota_keluarga_id)->first();
            $inputD['have_umkm'] = true;
            $anggotaKeluarga->update($inputD);

            if ($request->medsos_id && $request->medsos_url) {
                foreach ($request->medsos_id as $key => $val) {
                    if ($val && $request->medsos_url[$key]) {
                        $storeMedsos = [
                            'medsos_id' => $val,
                            'umkm_id' => $umkm->umkm_id,
                            'url' => $request->medsos_url[$key]
                        ];
    
                        $this->umkm_medsos->create($storeMedsos);
                    }
                }
            }
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            
            return response()->json(['status' => 'failed' , 'msg' => $e]);
        }
    }

    public function umkmMedsos($id)
    {
        return $this->umkm_medsos
            ->select('*')
            ->join('medsos', 'medsos.medsos_id', 'umkm_medsos.medsos_id')
            ->where('umkm_id', $id)
            ->get();
    }

    public function dataTables()
    {
        $checkRole = \helper::checkUserRole('all');
        $isAdmin = $checkRole['isAdmin'];   
    
        $cekUMKM = \DB::table('umkm')
                    ->select('anggota_keluarga_id')
                    ->where('aktif', true)
                    ->where('anggota_keluarga_id', \Auth::user()->anggota_keluarga_id)
                    ->groupBy('anggota_keluarga_id')
                    ->get();

        $isUMKM = $cekUMKM != $isAdmin ? true : false;

        $model =  $this->model
            ->select(
                'umkm.umkm_id',
                'umkm.nama',
                'umkm.deskripsi',
                'umkm.image',
                'umkm.aktif',
                'umkm.disetujui',
                'umkm.updated_at'
            )
            ->orWhere(function ($query) use ($isUMKM) {
                if ($isUMKM) {
                    $query->where('anggota_keluarga_id', \Auth::user()->anggota_keluarga_id);
                }
            });

        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                $button = '<a href="#" style="margin:10px;" class="btn btn-light-dark font-weight-bold mr-2 tambahProduk" data-id="' . $model->umkm_id . '">Tambah Produk</a>';

                return view('partials.buttons.datatable', [
                    'show'    => route('UMKM.Umkm.show', \Crypt::encryptString($model->umkm_id)),
                    'edit'    => route('UMKM.Umkm.edit', \Crypt::encryptString($model->umkm_id)),
                    'destroy' => route('UMKM.Umkm.destroy', \Crypt::encryptString($model->umkm_id)),
                    'extra' => $button
                ]);
            })
            ->addColumn('aktif', function ($model) {
                if ($model->aktif) {
                    return '<span class="badge badge-primary">Aktif</span>';
                } else {
                    return '<span class="badge badge-danger">Tidak Aktif</span>';
                }
            })
            ->editColumn('disetujui', function ($model) {
                if ($model->disetujui) {
                    return '<span class="badge badge-primary">Disetujui</span>';
                } else {
                    return '<span class="badge badge-danger">Tidak Disetujui</span>';
                }
            })
            ->addColumn('image', function ($model) {
                return view('partials.datatable-image', [
                    'folder' => 'umkm',
                    'img'    => $model->image
                ]);
            })
            ->editColumn('deskripsi', function ($row) {
                return strip_tags($row->deskripsi);
            })
            ->rawColumns(['action', 'aktif', 'image', 'disetujui'])
            ->make(true);
    }

    public function showUmkm($id)
    {
        return $this->model
            ->select(
                'anggota_keluarga.nama',
                'umkm.anggota_keluarga_id',
                'umkm.umkm_id',
                'umkm.nama as nama_umkm',
                'umkm.deskripsi',
                'umkm.image',
                'umkm.aktif',
                'umkm.disetujui',
                'umkm.promosi',
                'umkm.has_website'
            )
            ->join('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'umkm.anggota_keluarga_id')
            ->findOrFail($id);
    }

    public function updateUmkm($request, $id)
    {
        $umkm = $this->model->findOrFail($id);

        $updateImage  = true;
        $returnRecord = true;
        $request['promosi'] = $request->promosi ?? false;
        $request['slug']    = \Str::slug($request->nama);
        $input = $request->except('proengsoft_jsvalidation', 'medsos_id', 'medsos_url');
        
        \DB::beginTransaction();
        try {
            $this->update($input, $id, $updateImage, $returnRecord);
            $this->umkm_medsos->where('umkm_id', $umkm->umkm_id)->delete();

            if ($request->medsos_id && $request->medsos_url) {
                foreach ($request->medsos_id as $key => $val) {
                    if ($val && $request->medsos_url[$key]) {
                        $storeMedsos = [
                            'medsos_id' => $val,
                            'umkm_id' => $umkm->umkm_id,
                            'url' => $request->medsos_url[$key]
                        ];

                        $this->umkm_medsos->updateOrCreate($storeMedsos);
                    }
                }
            }
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function deleteUmkm($id)
    {
        $umkm = $this->model->findOrFail($id);
        $countUmkm = Umkm::select('anggota_keluarga_id')->where('anggota_keluarga_id', $umkm->anggota_keluarga_id)->count();
        
        if ($countUmkm - 1 == 0) {
            $anggotaKeluarga = AnggotaKeluarga::select('anggota_keluarga_id')->where('anggota_keluarga_id', $umkm->anggota_keluarga_id)->first();
            $inputD['have_umkm'] = false;
            $anggotaKeluarga->update($inputD);
        }

        $produk = $this->umkm_produk
            ->select('umkm_produk_id')
            ->where('umkm_id', $umkm->umkm_id)
            ->get();

        foreach ($produk as $key => $val) {
            $this->repoProduk->deleteProduk($val->umkm_produk_id);
        }

        if ($umkm->image) {
            \File::delete(public_path('uploaded_files/umkm/' . $umkm->image));
        }

        return $this->model->destroy($id);
    }

    public function totalUmkmUnapproved()
    {
        return $this->model->whereNull('disetujui')->count();
    }

    public function latestUmkmNeedApproval()
    {
        $latestUmkmNeedApproval = $this->model
            ->with('anggota_keluarga')
            ->orderBy('disetujui', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return $latestUmkmNeedApproval;
    }

    public function selectUMKM($exception=false) 
    {
        $checkRole = \helper::checkUserRole('all');
        $isAdmin = $checkRole['isAdmin'];   
    
        $cekUMKM = \DB::table('umkm')
                    ->select('umkm_id', 'nama')
                    ->where('aktif', true)
                    ->where('anggota_keluarga_id', \Auth::user()->anggota_keluarga_id)
                    ->pluck('nama', 'umkm_id');

        $isUMKM = $cekUMKM != $isAdmin ? true : false;
        
        $data = \DB::table('umkm')
            ->select(
                'umkm.umkm_id',
                'umkm.nama'
            )
            ->when(!$exception, function ($query) use ($isUMKM){
                $query->when($isUMKM, function ($query) {
                    $query->where('anggota_keluarga_id', \Auth::user()->anggota_keluarga_id);
                });
            })
            ->orderBy('nama')
            ->pluck('nama', 'umkm_id');
        
        return $data;
    }

    public function selectKategori()
    {
        $data = \DB::table('umkm_kategori')
            ->select(
                'umkm_kategori.umkm_kategori_id',
                'umkm_kategori.nama'
            )
            ->orderBy('nama')
            ->pluck('nama', 'umkm_kategori_id');
        
        return $data;
    }
}
