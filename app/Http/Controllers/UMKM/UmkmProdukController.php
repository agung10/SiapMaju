<?php

namespace App\Http\Controllers\UMKM;

use App\Http\Controllers\Controller;
use App\Repositories\UMKM\{UmkmProdukRepository,UmkmRepository};
use Illuminate\Http\Request;
use App\Http\Requests\UMKM\UMKMProdukRequest;

class UmkmProdukController extends Controller
{
    public function __construct(UmkmProdukRepository $_UmkmProdukRepository, UmkmRepository $_UmkmRepository)
    {
        $route_name = explode('.',\Route::currentRouteName());
        $this->route1 = ((isset($route_name[0])) ? $route_name[0] : (''));
        $this->route2 = ((isset($route_name[1])) ? $route_name[1] : (''));
        $this->route3 = ((isset($route_name[2])) ? $route_name[2] : (''));

        $this->produk = $_UmkmProdukRepository;
        $this->umkm   = $_UmkmRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $umkm = $this->umkm->selectUMKM();
        $resultUmkm = '<option></option>';
        foreach ($umkm as $umkm_id => $nama) {
            $resultUmkm .= '<option value="'.$umkm_id.'">'.$nama.'</option>';
        }

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('resultUmkm'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $umkm = $this->umkm->selectUMKM();
        $resultUmkm = '<option></option>';
        foreach ($umkm as $umkm_id => $nama) {
            $resultUmkm .= '<option value="'.$umkm_id.'">'.$nama.'</option>';
        }

        $umkmKategori = $this->umkm->selectKategori();
        $resultKategori = '<option></option>';
        foreach ($umkmKategori as $umkm_kategori_id => $nama) {
            $resultKategori .= '<option value="'.$umkm_kategori_id.'">'.$nama.'</option>';
        }

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('resultUmkm', 'resultKategori'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UMKMProdukRequest $request)
    {
        return $this->produk->storeProduk($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = \Crypt::decryptString($id);
        
        $data = $this->produk->showProduk($id); 
        
        $produk = $data['produk'];
        $produkImage = $data['produk_image'];

        return view($this->route1.'.'.$this->route2.'.'.$this->route3,compact('produk', 'produkImage'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $session = \Session('file_image');

        if($session){
            $this->produk->clearTempFile($session);            
        }

        $id = \Crypt::decryptString($id);

        $data = $this->produk->showProduk($id);
        $produk = $data['produk'];
        $produkImage = $data['produk_image'];

        $umkm = $this->umkm->selectUMKM();
        $resultUmkm = '<option disabled selected></option>';
        foreach ($umkm as $umkm_id => $nama) {
            $resultUmkm .= '<option value="'.$umkm_id.'"'.((!empty($umkm_id)) ? ((!empty($umkm_id == $data['produk']['umkm_id'])) ? ('selected') : ('')) : ('')).'>'.$nama.'</option>';
        }

        $umkmKategori = $this->umkm->selectKategori();
        $resultKategori = '<option disabled selected></option>';
        foreach ($umkmKategori as $umkm_kategori_id => $nama) {
            $resultKategori .= '<option value="'.$umkm_kategori_id.'"'.((!empty($umkm_kategori_id)) ? ((!empty($umkm_kategori_id == $data['produk']['umkm_kategori_id'])) ? ('selected') : ('')) : ('')).'>'.$nama.'</option>';
        }

        $jsonProdukImage = json_encode($produkImage);

        return view($this->route1.'.'.$this->route2.'.'.$this->route3,compact('produk', 'produkImage', 'resultUmkm', 'resultKategori', 'jsonProdukImage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UMKMProdukRequest $request, $id)
    {
        $id = \Crypt::decryptString($id);

        return $this->produk->updateProduk($request,$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = \Crypt::decryptString($id);

        return $this->produk->deleteProduk($id);
    }

    public function cartImage(Request $request,$id)
    {
        return $this->produk->cartImage($request,$id);
    }

    public function dataTables(Request $request)
    {
        return $this->produk->dataTables($request);
    }
}
