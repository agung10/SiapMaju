<?php

namespace App\Http\Controllers\UMKM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UMKM\KategoriRepository;

class UmkmKategoriController extends Controller
{
    public function __construct(KategoriRepository $_kategoriRepository)
    {
        $route_name = explode('.',\Route::currentRouteName());
        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

        $this->kategori = $_kategoriRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->route1.'.'.$this->route2.'.'.$this->route3);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->route1.'.'.$this->route2.'.'.$this->route3);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->kategori->store($request);
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
        $data = $this->kategori->show($id);

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = \Crypt::decryptString($id);
        $data = $this->kategori->show($id);

        return view($this->route1.'.'.$this->route2.'.'.$this->route3, compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = \Crypt::decryptString($id);
        
        return $this->kategori->update($request,$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->kategori->delete($id);
    }

    public function dataTables()
    {
        return $this->kategori->dataTables();
    }
}
