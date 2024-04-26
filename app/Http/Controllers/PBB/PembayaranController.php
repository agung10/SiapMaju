<?php

namespace App\Http\Controllers\PBB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\PbbRepository;

class PembayaranController extends Controller
{
    public function __construct(PbbRepository $pbb)
    {
        $this->pbb = $pbb;
    }
    
    public function index()
    {
        $tahun_pajak = \DB::table('pbb')->select('tahun_pajak')->distinct('tahun_pajak')->orderBy('tahun_pajak', 'asc')->get();
        return view ('pbb.pembayaran.index', compact('tahun_pajak'));
    }

    public function show($encryptedPbbId)
    {   
        $pbb = $this->pbb->findByEncrypted($encryptedPbbId);

        return view('pbb.pembayaran.show', compact('pbb'));
    }

    public function edit($encryptedPbbId)
    {   
        $pbb = $this->pbb->findByEncrypted($encryptedPbbId);

        return view('pbb.pembayaran.edit', compact('pbb'));
    }

    public function update(Request $request, $encryptedPbbId)
    {
        $pbbId = \Crypt::decryptString($encryptedPbbId);
        $updatedPbb = $this->pbb->updatePembayaranPbb($request, $pbbId);

        if($updatedPbb['status'])
        {
            return redirect()->route('pbb.pembayaran.index')->with($updatedPbb);
        }
        else
        {
            return redirect()->back()->with($updatedPbb);
        }
    }

    public function dataTables(Request $request, $tahun_pajak = false)
    {
        return $this->pbb->datatablePembayaranPbb($request, $tahun_pajak);
    }
}
