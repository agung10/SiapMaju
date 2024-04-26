<?php

namespace App\Http\Controllers\PBB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\PbbRepository;

class PembagianController extends Controller
{
    public function __construct(PbbRepository $pbb)
    {
        $this->pbb = $pbb;
    }
    
    public function index()
    {
        return view ('pbb.pembagian.index');
    }

    public function create()
    {
        $blok = $this->pbb->getBlokByUserLogin();
        
        return view ('pbb.pembagian.create', compact('blok'));
    }

    public function store(Request $request)
    {  
        $storedPbb = $this->pbb->storePembagianPbb($request);

        if($storedPbb['status'])
        {
            return redirect()->route('pbb.pembagian.index')->with($storedPbb);
        }
        else
        {
            return redirect()->back()->with($storedPbb);
        }
    }

    public function show($encryptedPbbId)
    {   
        $pbb = $this->pbb->findByEncrypted($encryptedPbbId);

        return view('pbb.pembagian.show', compact('pbb'));
    }

    public function edit($encryptedPbbId)
    {   
        $pbb   = $this->pbb->findByEncrypted($encryptedPbbId);
        $warga = $this->pbb->getWargaByBlok($pbb);
        $blok  = $this->pbb->getBlokByUserLogin();

        return view('pbb.pembagian.edit', compact('pbb', 'warga', 'blok'));
    }

    public function update(Request $request, $encryptedPbbId)
    {
        $updatedPbb = $this->pbb->updatePembagianPbb($request, $encryptedPbbId);

        if($updatedPbb['status'])
        {
            return redirect()->route('pbb.pembagian.index')->with($updatedPbb);
        }
        else
        {
            return redirect()->back()->with($updatedPbb);
        }
    }

    public function dataTables(Request $request)
    {
        return $this->pbb->datatablePembagianPbb($request);
    }

    public function getWargaByBlok(Request $request)
    {
        $warga = $this->pbb->getWargaByBlok($request);

        return response()->json(['status' => 'success', 'data' => $warga]);
    }
}
