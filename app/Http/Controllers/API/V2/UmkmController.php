<?php

namespace App\Http\Controllers\API\V2;

use App\Http\Controllers\Controller;
use App\Models\UMKM\Umkm;
use App\Models\UMKM\UmkmMedsos;
use App\Models\UMKM\UmkmProduk;
use App\Models\UMKM\UmkmImage;
use App\Models\UMKM\Medsos;
use App\Repositories\UMKM\UmkmRepository;
use Illuminate\Http\Request;

class UmkmController extends Controller
{
    public function __construct(Umkm $umkm, UmkmMedsos $umkmMedsos, UmkmProduk $umkmProduk, UmkmImage $umkmImage, UmkmRepository $umkmRepo, Medsos $medsos)
    {
        $this->userLoggedIn = auth('api')->user();
        $this->umkm         = $umkm;
        $this->umkmMedsos   = $umkmMedsos;
        $this->umkmProduk   = $umkmProduk;
        $this->umkmImage    = $umkmImage;
        $this->umkmRepo     = $umkmRepo;
        $this->medsos       = $medsos;
    }

    public function index()
    {
        $loggedIn = $this->userLoggedIn->anggotaKeluarga;
        $umkm = $this->umkm
                     ->whereHas('anggota_keluarga', function ($query) use($loggedIn) {
                        return $query->where('rw_id', '=', $loggedIn->rw_id);
                      })
                     ->with(['anggota_keluarga:anggota_keluarga_id,nama as owner'])
                     ->where('disetujui', true)
                     ->where('aktif', true)
                     ->orderBy('promosi')
                     ->orderBy('created_at', 'DESC')
                     ->get();

        return response()->json($umkm);
    }

    public function detail($id)
    {
        $umkm = $this->umkm->select(
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

        $umkm_medsos = $this->umkmMedsos->select(
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

        $umkm_produk = $this->umkmProduk->select(
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
            ->where('umkm_produk.umkm_id', $id)
            ->join('umkm_kategori', 'umkm_kategori.umkm_kategori_id', 'umkm_produk.umkm_kategori_id')
            ->orderBy('umkm_produk.created_at', 'DESC')
            ->where('umkm_produk.aktif', true)
            ->get();

        $umkm_produk->map(function($val, $i){
            $val->image = \helper::imageLoad('umkm', $val->image);

            return $val;
        });

        $umkm_image = $this->umkmImage->select(
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

    public function detailAsOwner()
    {
        $loggedIn = $this->userLoggedIn->anggotaKeluarga;
        $umkm = $this->umkm->with('umkmMedsos')->where('anggota_keluarga_id', $loggedIn->anggota_keluarga_id)->first();

        return response()->json($umkm);
    }

    public function search(Request $request)
    {
        $loggedIn = $this->userLoggedIn->anggotaKeluarga;
        $umkm = $this->umkm
                     ->whereHas('anggota_keluarga', function ($query) use($loggedIn) {
                        return $query->where('rw_id', '=', $loggedIn->rw_id);
                      })
                     ->with(['anggota_keluarga:anggota_keluarga_id,nama as owner'])
                     ->where('nama', 'ILIKE', '%' . $request->nama . '%')
                     ->where('disetujui', true)
                     ->where('aktif', true)
                     ->orderBy('promosi')
                     ->orderBy('created_at', 'DESC')
                     ->get();

        return response()->json($umkm);
    }

    public function listMedsos(Request $request)
    {
        $data = $this->medsos
            ->select(
                'medsos.medsos_id as id',
                'medsos.nama as name'
            )
            ->orderBy('nama')
            ->get();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $response = ['status' => false, 'msg' => 'Gagal menambahkan UMKM'];

        try {
            $request['medsos_id']  = json_decode($request->medsos_id);
            $request['medsos_url'] = json_decode($request->medsos_url);

            $store = $this->umkmRepo->storeUmkm($request);
            $storeResponse = json_decode($store->content(), true);

            if($storeResponse['status'] === 'success') {
                $response['status'] = true;
                $response['msg'] = 'UMKM berhasil ditambahkan';
            }
            

        } catch (\Exception $e) {
            \Log::error($e);
            $response['msg'] = $e->getMessage();
        }

        return response()->json($response);
    }

    public function update(Request $request)
    {
        $response = ['status' => false, 'msg' => 'Gagal memperbarui UMKM'];

        try {
            $request['medsos_id']  = json_decode($request->medsos_id);
            $request['medsos_url'] = json_decode($request->medsos_url);

            $update = $this->umkmRepo->updateUmkm($request, $request->umkm_id);
            $updateResponse = json_decode($update->content(), true);

            if($updateResponse['status'] === 'success') {
                $response['status'] = true;
                $response['msg'] = 'UMKM berhasil diperbarui';
            }
            

        } catch (\Exception $e) {
            \Log::error($e);
            $response['msg'] = $e->getMessage();
        }

        return response()->json($response);
    }

    public function getMap()
    {
        $loggedIn = $this->userLoggedIn->anggotaKeluarga;
        $umkm = $this->umkm
                     ->select([
                        'umkm.umkm_id', 'umkm.nama as nama_umkm', 'umkm.deskripsi', 'umkm.image', 'blok.nama_blok', 'blok.blok_id', 'blok.lang', 'blok.long', 'anggota_keluarga.nama'
                     ])
                     ->join('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'umkm.anggota_keluarga_id')
                     ->join('keluarga', 'keluarga.keluarga_id', 'anggota_keluarga.keluarga_id')
                     ->join('blok', 'blok.blok_id', 'keluarga.blok_id')
                     ->where('umkm.aktif', true)
                     ->where('umkm.disetujui', true)
                     ->where('anggota_keluarga.is_active', true)
                     ->where('anggota_keluarga.rw_id', $loggedIn->rw_id)
                     ->orderBy('blok.nama_blok')
                     ->get();

        $mapUmkm = [];
        $tempBlokId = null;

        foreach($umkm as $key => $u) {
            $arr['blokId']     = $u->blok_id;
            $arr['namaBlok']   = $u->nama_blok;
            $arr['long']       = $u->long;
            $arr['lang']       = $u->lang;
            $arr['umkmName']   = $u->nama_umkm;
            $arr['jumlahUmkm'] = 1;
            $arr['umkm']       = [];
            
            $umkm = [
                "id"    => $u->umkm_id,
                "owner" => $u->nama,
                "nama"  => $u->nama_umkm,
                "logo"  => $u->image_src,
                "desc"  => $u->deskripsi
            ];

            if($tempBlokId === $u->blok_id) {
                $sameBlokKey = array_search($u->blok_id, array_column($mapUmkm, 'blokId'));
                $mapUmkm[$sameBlokKey]['jumlahUmkm']++;
                array_push($mapUmkm[$sameBlokKey]['umkm'], $umkm);
            }
            else {
                array_push($arr['umkm'], $umkm);
                array_push($mapUmkm,$arr);
            }

            $tempBlokId = $u->blok_id;
        }

        return response()->json($mapUmkm);
    }

    public function listUmkmPerBlok()
    {
        $loggedIn = $this->userLoggedIn->anggotaKeluarga;
        $umkm = $this->umkm
                     ->select([
                        'umkm.nama as label', 'umkm.umkm_id as value', 'blok.blok_id'])
                     ->join('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'umkm.anggota_keluarga_id')
                     ->join('keluarga', 'keluarga.keluarga_id', 'anggota_keluarga.keluarga_id')
                     ->join('blok', 'blok.blok_id', 'keluarga.blok_id')
                     ->where('umkm.aktif', true)
                     ->where('umkm.disetujui', true)
                     ->where('anggota_keluarga.is_active', true)
                     ->where('anggota_keluarga.rw_id', $loggedIn->rw_id)
                     ->orderBy('umkm.nama')
                     ->get();

        return response()->json($umkm);
    }
}
