<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UMKM\Umkm;
use App\Models\UMKM\UmkmMedsos;
use App\Models\UMKM\UmkmProduk;
use App\Models\UMKM\UmkmImage;
use Illuminate\Http\Request;

class UmkmAPIController extends Controller
{
    public function index()
    {
        $umkm = Umkm::select(
            'umkm.umkm_id',
            'anggota_keluarga.nama as owner',
            'umkm.image',
            'umkm.nama',
            'umkm.deskripsi',
            'umkm.aktif',
            'umkm.disetujui',
            'umkm.promosi',
            'umkm.has_website',
            'umkm.slug',
            'umkm.created_at',
            'umkm.updated_at'
        )
            ->join('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'umkm.anggota_keluarga_id')
            ->orderBy('umkm.created_at', 'DESC')
            ->get();

        $result = [];

        foreach ($umkm as $key => $val) {
            $data['umkm_id'] = $val->umkm_id;
            $data['owner'] = $val->owner;
            $data['image'] = $val->image ? asset('uploaded_files/umkm/' . $val->image) : null;
            $data['nama'] = $val->nama;
            $data['deskripsi'] = $val->deskripsi;
            $data['aktif'] = $val->aktif;
            $data['disetujui'] = $val->disetujui;
            $data['promosi'] = $val->promosi;
            $data['has_website'] = $val->has_website;
            $data['slug'] = $val->slug;
            $data['created_at'] = $val->created_at;
            $data['updated_at'] = $val->updated_at;

            array_push($result, $data);
        }

        return response()->json(compact('result'));
    }

    public function detail($id)
    {
        $umkm = Umkm::select(
            'umkm.umkm_id',
            'anggota_keluarga.nama as owner',
            'umkm.image',
            'umkm.nama',
            'umkm.deskripsi',
            'umkm.aktif',
            'umkm.disetujui',
            'umkm.promosi',
            'umkm.has_website',
            'umkm.slug',
            'umkm.created_at',
            'umkm.updated_at'
        )
            ->join('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'umkm.anggota_keluarga_id')
            ->where('umkm.umkm_id', $id)
            ->first();

        $umkm->setAttribute('image', \helper::imageLoad('umkm', $umkm->image));

        $umkm_medsos = UmkmMedsos::select(
            'umkm_medsos.umkm_medsos_id',
            'medsos.nama',
            'medsos.icon',
            'umkm_medsos.url',
        )
            ->join('medsos', 'medsos.medsos_id', 'umkm_medsos.medsos_id')
            ->where('umkm_medsos.umkm_id', $id)
            ->get();

        $umkm_medsos->map(function($val, $i){
            $val->icon = \helper::imageLoad('medsos', $val->icon);

            return $val;
        });

        $umkm->setAttribute('medsos', $umkm_medsos);

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
            ->where('umkm_produk.umkm_id', $id)
            ->join('umkm_kategori', 'umkm_kategori.umkm_kategori_id', 'umkm_produk.umkm_kategori_id')
            ->orderBy('umkm_produk.created_at', 'DESC')
            ->get();

        $umkm_produk->map(function($val, $i){
            $val->image = \helper::imageLoad('umkm', $val->image);

            return $val;
        });

        $umkm_image = UmkmImage::select(
            'umkm_image.umkm_image_id',
            'umkm_image.umkm_produk_id',
            'umkm_image.file_image',
        )
            ->whereIn('umkm_image.umkm_produk_id', $umkm_produk->pluck('umkm_produk_id'))
            ->get();


        foreach ($umkm_produk as $key => $val) {
            $val->setAttribute('umkm_images', $umkm_image->where('umkm_produk_id', $val->umkm_produk_id)->all());
        }

        $umkm->setAttribute('produk', $umkm_produk);

        return response()->json($umkm);
    }

    public function search(Request $request)
    {
        $searchUmkm = Umkm::select(
            'umkm.umkm_id',
            'anggota_keluarga.nama as owner',
            'umkm.image',
            'umkm.nama',
            'umkm.deskripsi',
            'umkm.aktif',
            'umkm.disetujui',
            'umkm.promosi',
            'umkm.has_website',
            'umkm.slug',
            'umkm.created_at',
            'umkm.updated_at'
        )
            ->join('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'umkm.anggota_keluarga_id')
            ->where('umkm.nama', 'ILIKE', '%' . $request->nama . '%')
            ->orderBy('umkm.created_at', 'DESC')
            ->get();

        $search = [];

        foreach ($searchUmkm as $key => $val) {
            $data['umkm_id'] = $val->umkm_id;
            $data['owner'] = $val->owner;
            $data['image'] = asset('uploaded_files/umkm/' . $val->image);
            $data['nama'] = $val->nama;
            $data['deskripsi'] = $val->deskripsi;

            array_push($search, $data);
        }

        return response()->json(compact('search'));
    }
}
