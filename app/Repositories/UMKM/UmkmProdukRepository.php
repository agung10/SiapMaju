<?php

namespace App\Repositories\UMKM;

use App\Repositories\BaseRepository;
use App\Models\UMKM\{UmkmProduk, UmkmImage};

class UmkmProdukRepository extends BaseRepository
{
    public function __construct(UmkmProduk $_UmkmProduk, UmkmImage $_UmkmImage)
    {
        $this->model = $_UmkmProduk;
        $this->umkmImage = $_UmkmImage;
    }

    public function dataTables($request)
    {
        $checkRole = \helper::checkUserRole('all');
        $isAdmin = $checkRole['isAdmin'] || \Auth::user()->anggota_keluarga_id === 10;   
    
        $cekUMKM = \DB::table('umkm')
                    ->select('anggota_keluarga_id')
                    ->where('aktif', true)
                    ->where('anggota_keluarga_id', \Auth::user()->anggota_keluarga_id)
                    ->groupBy('anggota_keluarga_id')
                    ->get();

        $isUMKM = $cekUMKM != $isAdmin ? true : false;

        if (request()->ajax()) {
            $model = $this->model
                ->select(
                    'umkm_produk.umkm_produk_id',
                    'umkm_produk.image',
                    'umkm_produk.nama',
                    'umkm_produk.harga',
                    'umkm_produk.stok',
                    'umkm_produk.aktif',
                    'umkm_produk.updated_at',
                    'umkm.nama as nama_umkm',
                    'umkm_produk.siap_dipesan'                
                )
                ->join('umkm', 'umkm.umkm_id', 'umkm_produk.umkm_id')
                ->orWhere(function ($query) use ($isUMKM) {
                    if ($isUMKM) {
                        $query->where('anggota_keluarga_id', \Auth::user()->anggota_keluarga_id);
                    }
                })
                ->when(!empty($request->umkm_id), function ($query) use ($request) {
                    $query->where('umkm.umkm_id', $request->umkm_id);
                });
        }

        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                return view('partials.buttons.datatable', [
                    'show'    => route('UMKM.Produk.show', \Crypt::encryptString($model->umkm_produk_id)),
                    'edit'    => route('UMKM.Produk.edit', \Crypt::encryptString($model->umkm_produk_id)),
                    'destroy' => route('UMKM.Produk.destroy', \Crypt::encryptString($model->umkm_produk_id)),
                ]);
            })
            ->addColumn('image', function ($model) {
                return view('partials.datatable-image', [
                    'folder' => 'umkm',
                    'img'    => $model->image
                ]);
            })
            ->addColumn('aktif', function ($model) {
                if ($model->aktif) {
                    return '<span class="badge badge-primary">Aktif</span>';
                } else {
                    return '<span class="badge badge-danger">Tidak Aktif</span>';
                }
            })
            ->editColumn('siap_dipesan', function ($row) {
                $siapDipesan = $row->siap_dipesan
                    ? '<span class="badge badge-success">Ya</span>'
                    : '<span class="badge badge-warning text-white">Tidak</span>';
                
                return $siapDipesan;
            })
            ->addColumn('harga', function ($model) {
                return number_format($model->harga, 0, ',', '.');
            })
            ->rawColumns(['action', 'aktif', 'siap_dipesan', 'image', 'harga'])
            ->make(true);
    }

    public function storeProduk($request)
    {
        $storeProdukData = $request->except('file_image_1', 'file_image_2', 'file_image_3', 'proengsoft_jsvalidation');

        \DB::beginTransaction();
        try {
            $storeProdukData['harga'] = \helper::number_formats($request->harga, 'db', 0);
            $storeProdukData['image'] = 'umkm_produk' . rand() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('uploaded_files/umkm'), $storeProdukData['image']);
            
            $storeProduk = $this->model->create($storeProdukData);
            $this->storeProdukImage($request, $storeProduk->umkm_produk_id);

            \DB::commit();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
             throw $e;
        }
    }

    public function showProduk($id)
    {
        $produk = $this->model
            ->select(
                'umkm_produk.umkm_produk_id', 
                'umkm_produk.umkm_id',
                'umkm_produk.umkm_kategori_id', 
                'umkm_produk.image', 
                'umkm_produk.nama', 
                'umkm_produk.url', 
                'umkm_produk.harga', 
                'umkm_produk.stok', 
                'umkm_produk.berat', 
                'umkm_produk.deskripsi', 
                'umkm_produk.aktif',
                'umkm_produk.siap_dipesan', 
                'umkm.nama as nama_umkm', 
                'umkm_kategori.nama as kategori', 
            )
            ->join('umkm', 'umkm.umkm_id', 'umkm_produk.umkm_id')
            ->join('umkm_kategori', 'umkm_kategori.umkm_kategori_id', 'umkm_produk.umkm_kategori_id')
            ->findOrFail($id);

        $produk_image = $this->umkmImage
            ->select(
                'umkm_image.umkm_image_id',
                'umkm_image.file_image', 
            )
            ->where('umkm_image.umkm_produk_id', $produk->umkm_produk_id)
            ->get();

        return compact('produk', 'produk_image');
    }

    public function updateProduk($request, $id)
    {
        $umkmProduk = $this->model->findOrFail($id);
        $input = $request->only('umkm_id', 'umkm_kategori_id', 'image', 'nama', 'deskripsi', 'url', 'harga', 'stok', 'berat', 'aktif', 'siap_dipesan');
        $session = \Session('file_image');

        \DB::beginTransaction();
        try {
            $input['harga'] = \helper::number_formats($request->harga, 'db', 0);
            if ($request->hasFile('image')) {
                $input['image'] = 'umkm_produk' . rand() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('uploaded_files/umkm'), $input['image']);

                \File::delete(public_path('uploaded_files/umkm/' . $umkmProduk->image));
            }

            if ($session) {
                $this->updateUmkmImage($session, $id);
            }

            $umkmProduk->update($input);

            \DB::commit();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function updateUmkmImage($session, $produkId)
    {
        foreach ($session as $key => $val) {

            if ($val['file_image_id'] !== 'none') {

                $umkmImage = $this->umkmImage
                    ->findOrFail($val['file_image_id']);
            }

            $currentFilePath = public_path('uploaded_files/umkm/temp/' . $val['file_image']);
            $newFilePath = public_path('uploaded_files/umkm/umkm_image/' . $val['file_image']);
            $fileMoved = rename($currentFilePath, $newFilePath);

            if ($fileMoved) {
                $newImage = ['file_image' => $val['file_image']];
                if ($val['file_image_id'] == 'none') {
                    $addNewImage = [
                        'umkm_produk_id' => $produkId,
                        'file_image' => $val['file_image']
                    ];
                    $this->umkmImage->create($addNewImage);
                } else {
                    $oldFile = $umkmImage->file_image;
                    \File::delete(public_path('uploaded_files/umkm/umkm_image/' . $oldFile));
                    $umkmImage->update($newImage);
                }
            }
        }
    }

    public function storeProdukImage($request, $umkm_produk_id)
    {
        $file_image = [];
        if ($request->file_image_1) {
            $image = 'umkm_image' . rand() . '.' . $request->file_image_1->getClientOriginalExtension();
            $request->file_image_1->move(public_path('uploaded_files/umkm/umkm_image'), $image);
            array_push($file_image, $image);
        }

        if ($request->file_image_2) {
            $image = 'umkm_image' . rand() . '.' . $request->file_image_2->getClientOriginalExtension();
            $request->file_image_2->move(public_path('uploaded_files/umkm/umkm_image'), $image);
            array_push($file_image, $image);
        }

        if ($request->file_image_3) {
            $image = 'umkm_image' . rand() . '.' . $request->file_image_3->getClientOriginalExtension();
            $request->file_image_3->move(public_path('uploaded_files/umkm/umkm_image'), $image);
            array_push($file_image, $image);
        }

        foreach ($file_image as $key => $val) {
            $storeImageProduk = [
                'file_image' => $val,
                'umkm_produk_id' => $umkm_produk_id
            ];
            $this->umkmImage->create($storeImageProduk);
        }
    }

    public function deleteProduk($id)
    {
        $produk = $this->model
            ->findOrFail($id);

        $produkImage = $this->umkmImage
            ->where('umkm_produk_id', $produk->umkm_produk_id)
            ->get();

        if ($produkImage) {
            foreach ($produkImage as $key => $val) {
                \File::delete(public_path('uploaded_files/umkm/umkm_image/' . $val->file_image));
            }

            $produkImage = $this->umkmImage
                ->where('umkm_produk_id', $produk->umkm_produk_id)
                ->delete();
        }

        if ($produk->image) {
            \File::delete(public_path('uploaded_files/umkm/' . $produk->image));
        }

        $this->model->destroy($id);
    }

    public function cartImage($request, $id)
    {

        $session = \Session('file_image');

        if ($session) {
            if (array_key_exists($request->imageIndex, $session)) {

                $arrSession =  $session[$request->imageIndex];
                $file = $arrSession['file_image'];
                unlink(public_path('uploaded_files/umkm/temp/') . $file);
            }
        }

        $fileName = 'umkm_image_' . rand() . '.' . $request->file_image->getClientOriginalExtension();
        $request->file_image->move(public_path('uploaded_files/umkm/temp'), $fileName);

        $session[$request->imageIndex] = [
            'file_image' => $fileName,
            'file_image_id' => $id
        ];

        \Request::session()->put('file_image', $session);
        \Session::save();
    }

    public function clearTempFile($tempData)
    {
        foreach ($tempData as $key => $val) {

            $file = public_path('uploaded_files/umkm/temp/') . $val['file_image'];

            if (file_exists($file)) {
                unlink($file);
            }

            \Request::session()->forget('file_image');
        }
    }
}
