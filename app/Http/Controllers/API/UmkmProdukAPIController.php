<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UMKM\Umkm;
use App\Models\UMKM\UmkmImage;
use App\Models\UMKM\UmkmProduk;
use Illuminate\Http\Request as Request;

class UmkmProdukAPIController extends Controller
{
    public function index()
    {
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
            'umkm_produk.created_at',
            'umkm_produk.updated_at'
        )
            ->join('umkm', 'umkm.umkm_id', 'umkm_produk.umkm_id')
            ->join('anggota_keluarga', 'umkm.anggota_keluarga_id', 'anggota_keluarga.anggota_keluarga_id')
            ->join('umkm_kategori', 'umkm_kategori.umkm_kategori_id', 'umkm_produk.umkm_kategori_id')
            ->orderBy('umkm_produk.created_at', 'DESC')
            ->get();

        $result = [];

        foreach ($umkm_produk as $key => $val) {
            $data['umkm_produk_id']= $val->umkm_produk_id;
            $data['rw_id']         = $val->rw_id;
            $data['nama_umkm']     = $val->nama_umkm;
            $data['kategori']      = $val->kategori;
            $data['image']         = $val->image ? asset('uploaded_files/umkm/' . $val->image) : null;
            $data['nama']          = $val->nama;
            $data['deskripsi']     = $val->deskripsi;
            $data['url']           = $val->url;
            $data['harga']         = $val->harga;
            $data['stok']          = $val->stok;
            $data['berat']         = $val->berat;
            $data['aktif']         = $val->aktif;
            $data['created_at']    = $val->created_at;
            $data['updated_at']    = $val->updated_at;

            array_push($result, $data);
        }

        return response()->json(compact('result'));
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
            'umkm_produk.created_at',
            'umkm_produk.updated_at'
        )
            ->join('umkm', 'umkm.umkm_id', 'umkm_produk.umkm_id')
            ->join('anggota_keluarga', 'umkm.anggota_keluarga_id', 'anggota_keluarga.anggota_keluarga_id')
            ->join('umkm_kategori', 'umkm_kategori.umkm_kategori_id', 'umkm_produk.umkm_kategori_id')
            ->where('umkm_produk.nama', 'ILIKE', '%' . $request->nama . '%')
            ->orderBy('umkm_produk.created_at', 'DESC')
            ->get();

        $result = [];

        foreach ($umkm_produk as $key => $val) {
            $data['umkm_produk_id']= $val->umkm_produk_id;
            $data['rw_id']         = $val->rw_id;
            $data['nama_umkm']     = $val->nama_umkm;
            $data['kategori']      = $val->kategori;
            $data['image']         = $val->image ? asset('uploaded_files/umkm/' . $val->image) : null;
            $data['nama']          = $val->nama;
            $data['deskripsi']     = $val->deskripsi;
            $data['url']           = $val->url;
            $data['harga']         = $val->harga;
            $data['stok']          = $val->stok;
            $data['berat']         = $val->berat;
            $data['aktif']         = $val->aktif;
            $data['created_at']    = $val->created_at;
            $data['updated_at']    = $val->updated_at;

            array_push($result, $data);
        }

        return response()->json(compact('result'));
    }
}
