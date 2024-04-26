<?php

namespace App\Http\Controllers\Panduan;

use Illuminate\Http\Request;
use App\Http\Requests\Panduan\PanduanRequest;
use App\Http\Controllers\Controller;

class PanduanController extends Controller
{

    use \App\Traits\StorageTrait;

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('panduan.index');
    }

    public function update(PanduanRequest $request)
    {
        $fileName = 'panduan.pdf';
        $oldName = $fileName;
        $folderName = 'panduan';
        $alert = [
            'status'       => true,
            'notification' => 'Panduan berhasil disimpan', 
            'type'         => 'success'
        ]; 

        $this->storeFile($request->panduan, $folderName, $fileName, $oldName);

        return redirect()->route('panduan.index')->with($alert);
    }
}
