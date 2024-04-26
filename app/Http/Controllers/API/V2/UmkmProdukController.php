<?php

namespace App\Http\Controllers\API\V2;

use App\Http\Controllers\Controller;
use App\Models\UMKM\Umkm;
use App\Models\UMKM\UmkmImage;
use App\Models\UMKM\UmkmProduk;
use App\Models\UMKM\Kategori;
use App\Repositories\UMKM\UmkmProdukRepository;
use Illuminate\Http\Request;
use App\Events\UmkmProdukSiapDipesan;

class UmkmProdukController extends Controller
{
    public function __construct(Umkm $umkm, UmkmProduk $umkmProduk, UmkmImage $umkmImage, Kategori $kategori, UmkmProdukRepository $umkmProdRepo)
    {
        $this->userLoggedIn = auth('api')->user();
        $this->umkm         = $umkm;
        $this->umkmProduk   = $umkmProduk;
        $this->umkmImage    = $umkmImage;
        $this->kategori     = $kategori;
        $this->umkmProdRepo = $umkmProdRepo; 
    }

    public function index()
    {
        $loggedIn = $this->userLoggedIn->anggotaKeluarga;
        $umkm_produk = UmkmProduk::select(
            'umkm_produk.umkm_produk_id',
            'umkm.nama as nama_umkm',
            'anggota_keluarga.rw_id',
            'umkm_kategori.nama as kategori',
            'umkm_produk.image',
            'umkm_produk.nama',
            'umkm_produk.deskripsi',
            'umkm_produk.url',
            'umkm_produk.harga',
            'umkm_produk.stok',
            'umkm_produk.berat',
            'umkm_produk.aktif',
            'umkm_produk.siap_dipesan',
            'umkm_produk.created_at',
            'umkm_produk.updated_at'
        )
            ->join('umkm', 'umkm.umkm_id', 'umkm_produk.umkm_id')
            ->join('anggota_keluarga', 'umkm.anggota_keluarga_id', 'anggota_keluarga.anggota_keluarga_id')
            ->join('umkm_kategori', 'umkm_kategori.umkm_kategori_id', 'umkm_produk.umkm_kategori_id')
            ->orderBy('umkm_produk.created_at', 'DESC')
            ->where('umkm_produk.aktif', true)
            ->where('umkm.aktif', true)
            ->where('umkm.disetujui', true)
            ->where('anggota_keluarga.rw_id', $loggedIn->rw_id)
            ->get();

        $result = [];

        foreach ($umkm_produk as $key => $val) {
            $data['umkm_produk_id'] = $val->umkm_produk_id;
            $data['rw_id']          = $val->rw_id;
            $data['nama_umkm']      = $val->nama_umkm;
            $data['kategori']       = $val->kategori;
            $data['image']          = $val->image ? asset('uploaded_files/umkm/' . $val->image) : null;
            $data['nama']           = $val->nama;
            $data['deskripsi']      = $val->deskripsi;
            $data['url']            = $val->url;
            $data['harga']          = $val->harga;
            $data['stok']           = $val->stok;
            $data['berat']          = $val->berat;
            $data['aktif']          = $val->aktif;
            $data['siap_dipesan']   = $val->siap_dipesan;
            $data['created_at']     = $val->created_at;
            $data['updated_at']     = $val->updated_at;

            array_push($result, $data);
        }

        return response()->json(compact('result'));
    }

    public function listAsOwner(Request $request)
    {
        $loggedIn = $this->userLoggedIn->anggotaKeluarga;
        $produk = $this->umkmProduk
                       ->whereHas('umkm', function ($q) use($loggedIn) {
                            return $q->where('anggota_keluarga_id', $loggedIn->anggota_keluarga_id);
                        })
                       ->with(['images']);
        
        if($request->has('umkm_produk_id')) {
            $produk = $produk->where('umkm_produk_id', $request->umkm_produk_id)->first();
        }

        else {
            $produk = $produk->get();
        }

        return response()->json($produk);
    }

    public function detail($id)
    {
        $umkm_produk = UmkmProduk::select(
            'umkm_produk.umkm_produk_id',
            'umkm_produk.umkm_id',
            'umkm_kategori.nama as kategori',
            'umkm_produk.image',
            'umkm_produk.nama',
            'umkm_produk.deskripsi',
            'umkm_produk.url',
            'umkm_produk.harga',
            'umkm_produk.stok',
            'umkm_produk.berat',
            'umkm_produk.aktif',
            'umkm_produk.siap_dipesan',
            'umkm_produk.created_at',
            'umkm_produk.updated_at'
        )
            ->join('umkm_kategori', 'umkm_kategori.umkm_kategori_id', 'umkm_produk.umkm_kategori_id')
            ->where('umkm_produk.umkm_produk_id', $id)
            ->orderBy('umkm_produk.created_at', 'DESC')
            ->first();

        $owner = Umkm::with('anggota_keluarga:anggota_keluarga_id,mobile,city_id')
                     ->where('umkm.umkm_id', $umkm_produk->umkm_id)
                     ->first();

        $umkm_image = UmkmImage::where('umkm_image.umkm_produk_id', $id)->get();

        $owner->setAttribute('image', \helper::imageLoad('umkm', $owner->image));
        $umkm_produk->setAttribute('owner', $owner);
        $umkm_produk->setAttribute('image', \helper::imageLoad('umkm', $umkm_produk->image));
        $umkm_produk->setAttribute('file_images', $umkm_image);


        return response()->json($umkm_produk);
    }

    public function search(Request $request)
    {
        $loggedIn = $this->userLoggedIn->anggotaKeluarga;
    	$umkm_produk = UmkmProduk::select(
            'umkm_produk.umkm_produk_id',
            'umkm.nama as nama_umkm',
            'anggota_keluarga.rw_id',
            'umkm_kategori.nama as kategori',
            'umkm_produk.image',
            'umkm_produk.nama',
            'umkm_produk.deskripsi',
            'umkm_produk.url',
            'umkm_produk.harga',
            'umkm_produk.stok',
            'umkm_produk.berat',
            'umkm_produk.aktif',
            'umkm_produk.siap_dipesan',
            'umkm_produk.created_at',
            'umkm_produk.updated_at'
        )
            ->join('umkm', 'umkm.umkm_id', 'umkm_produk.umkm_id')
            ->join('anggota_keluarga', 'umkm.anggota_keluarga_id', 'anggota_keluarga.anggota_keluarga_id')
            ->join('umkm_kategori', 'umkm_kategori.umkm_kategori_id', 'umkm_produk.umkm_kategori_id')
            ->where('umkm_produk.nama', 'ILIKE', '%' . $request->nama . '%')
            ->where('anggota_keluarga.rw_id', $loggedIn->rw_id)
            ->where('umkm_produk.aktif', true)
            ->where('umkm.aktif', true)
            ->where('umkm.disetujui', true)
            ->orderBy('umkm_produk.created_at', 'DESC')
            ->get();

        $result = [];

        foreach ($umkm_produk as $key => $val) {
            $data['umkm_produk_id'] = $val->umkm_produk_id;
            $data['rw_id']          = $val->rw_id;
            $data['nama_umkm']      = $val->nama_umkm;
            $data['kategori']       = $val->kategori;
            $data['image']          = $val->image ? asset('uploaded_files/umkm/' . $val->image) : null;
            $data['nama']           = $val->nama;
            $data['deskripsi']      = $val->deskripsi;
            $data['url']            = $val->url;
            $data['harga']          = $val->harga;
            $data['stok']           = $val->stok;
            $data['berat']          = $val->berat;
            $data['aktif']          = $val->aktif;
            $data['siap_dipesan']   = $val->siap_dipesan;
            $data['created_at']     = $val->created_at;
            $data['updated_at']     = $val->updated_at;

            array_push($result, $data);
        }

        return response()->json(compact('result'));
    }

    public function listKategori(Request $request)
    {
        $data = $this->kategori
            ->select(
                'umkm_kategori_id as id',
                'nama as name'
            )
            ->orderBy('nama')
            ->get();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $response = ['status' => false, 'msg' => 'Gagal menambahkan produk'];

        try {
            $store = $this->umkmProdRepo->storeProduk($request);
            $storeResponse = json_decode($store->content(), true);

            if($storeResponse['status'] === 'success') {
                $response['status'] = true;
                $response['msg'] = 'Produk berhasil ditambahkan';
            }
            

        } catch (\Exception $e) {
            \Log::error($e);
            $response['msg'] = $e->getMessage();
        }

        return response()->json($response);
    }

    public function update(Request $request)
    {
        $response = ['status' => false, 'msg' => 'Gagal memperbarui produk'];

        \DB::beginTransaction();
        try {
            $id = $request->umkm_produk_id;
            $produk = $this->umkmProduk->findOrFail($id);
            $request['harga'] = \helper::number_formats($request->harga, 'db', 0);
            $umkmIUmageIds = $this->umkmImage
                                  ->where('umkm_produk_id', $request->umkm_produk_id)
                                  ->pluck('umkm_image_id')
                                  ->toArray();
            $requestException = [];

            foreach ($request->all() as $key => $value) {
                // check any request type image except request->image (main image produk)
                if(is_object($value) && $key != 'image'){
                    $file = $request->file($key);
                    $fileName = 'umkm_produk_' . time() . '_' . rand() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploaded_files/umkm/umkm_image'), $fileName);

                    // check image replace old image or new updload
                    if( in_array( $key ,$umkmIUmageIds ) ) {
                        $oldImage = $this->umkmImage->find($key);
                        \File::delete(public_path('uploaded_files/umkm/umkm_image/' . $oldImage->file_image));
                        $oldImage->update(['file_image' => $fileName]);
                    }
                    else
                    {
                        $this->umkmImage->create([
                            'umkm_produk_id' => $request->umkm_produk_id,
                            'file_image' => $fileName
                        ]);
                    }
                    // remove from request
                    array_push($requestException, $key);
                }
            }  
            
            $input = $request->except(array_merge($requestException, ['image']));
            $produk->update($input);

            if(isset($input['siap_dipesan']) && $input['siap_dipesan'] == true) {
                $wargaLoggedIn = $this->userLoggedIn->anggotaKeluarga;
                UmkmProdukSiapDipesan::dispatch($wargaLoggedIn->rw_id, $produk->umkm->nama, $produk->umkm_produk_id, $produk->nama);
            }
                
            if($request->hasFile('image')) {
                $oldImage = $produk->image;
                \File::delete(public_path('uploaded_files/umkm/' . $produk->image));
                $fileName = 'umkm_produk' . rand() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('uploaded_files/umkm'), $fileName);
                $produk->update(['image' => $fileName]);
            }
            
            \DB::commit();

            $response['msg']    = 'Produk berhasil diperbarui';
            $response['status'] = true;
        } catch (\Exception $e) {
            \Log::error($e);
            $response['msg'] = $e->getMessage();
        }

        return response()->json($response);
    }
}
