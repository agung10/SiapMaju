<?php

namespace App\Http\Controllers\API\V2;

use App\Http\Controllers\Controller;
use App\Repositories\PbbRepository;
use Illuminate\Http\Request;

class PbbController extends Controller
{
    public function __construct(PbbRepository $pbb)
    {
        $this->userLoggedIn = auth('api')->user();
        $this->pbb = $pbb;
        $this->kepalaKeluarga  = 1;
    }

    public function get()
    {
        $wargaLoggedIn = $this->userLoggedIn->anggotaKeluarga;
        $pbb = $this->pbb->orderBy('created_at', 'DESC');

        if($wargaLoggedIn->hub_keluarga_id === $this->kepalaKeluarga)
        {
            $anggotaKeluargaIds = $wargaLoggedIn->allAnggotaKeluargaId();
            $pbb = $pbb->whereIn('anggota_keluarga_id', $anggotaKeluargaIds)->get();
        }
        else
        {
            $pbb = $pbb->where('anggota_keluarga_id', $wargaLoggedIn->anggota_keluarga_id)->get();
        }


        return response()->json($pbb);
    }

    public function detail(Request $request)
    {
        $pbb = $this->pbb->with(['blok', 'anggotaKeluarga'])->find($request->pbb_id);

        return response()->json([
            'status' => $pbb ? true : false,
            'data' => $pbb,
            'msg' => $pbb ? 'success' : 'failed'
        ]);
    }

    public function bayar(Request $request)
    {
        \DB::beginTransaction();

        try{
            
            $pbb = $this->pbb->updatePembayaranPbb($request, $request->pbb_id);
            $response = [
                'status' => $pbb['status'], 
                'msg'    => $pbb['notification']
            ];

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            \Log::error($e);

            $response = [
                'status' => false,
                'msg'    => $e->getMessage()
            ];
        }

        return response()->json($response);
    }

}
