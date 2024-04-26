<?php

namespace App\Http\Controllers\RoleManagement;

use App\Http\Controllers\Controller;
use App\Models\Master\Keluarga\AnggotaKeluarga;
use App\Models\Master\Keluarga\MutasiWarga;
use App\Repositories\RajaOngkir\RajaOngkirRepository;
use Illuminate\Http\Request;
use App\Repositories\RoleManagement\ListWargaRepository;

class ListWargaController extends Controller
{

    public function __construct(ListWargaRepository $_ListWargaRepository, RajaOngkirRepository $rajaOngkir)
    {
        $route_name = explode('.',\Route::currentRouteName());
        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

        $this->warga = $_ListWargaRepository;
        $this->rajaOngkir = $rajaOngkir;
    }

    public function index()
    {
        $provinces = $this->rajaOngkir->getProvinces()->pluck('province', 'province_id');
        return view('Master.'.$this->route1.'.'.$this->route2.'.'.$this->route3, compact('provinces'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $id = \Crypt::decryptString($id);
        
        $data = $this->warga->show($id);
        $anggota = \DB::table('anggota_keluarga')->select('anggota_keluarga.anggota_keluarga_id', 'anggota_keluarga.is_rt')->where('anggota_keluarga.anggota_keluarga_id', \Auth::user()->anggota_keluarga_id)->first();
        $mutasi = \DB::table('mutasi_warga')->where('anggota_keluarga_id', $data->anggota_keluarga_id)->latest("mutasi_warga_id")->first();

        return view('Master.'.$this->route1.'.'.$this->route2.'.'.$this->route3,compact('data', 'anggota', 'mutasi'));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function dataTables(Request $request)
    {   
        return $this->warga->dataTables($request);
    }
    
    public function ApprovalRT($id)
    {
        $data = AnggotaKeluarga::findOrFail($id);
        $input['is_active'] = true;

        $url = 'https://dev2.kamarkerja.com:3333/message/text';
        $response = @get_headers($url);

        // if cant reach url
        if (!$response) return redirect()->back()->with('error', 'Maaf terjadi kesalahan'); 

        $whatsappKey = \DB::table('whatsapp_key')
            ->select('whatsapp_key')
            ->first()
            ->whatsapp_key ?? null;

        if (!$whatsappKey) return redirect()->back()->with('error','No Whatsapp belum disandingkan'); 

        $kepalaKeluarga = \DB::table('anggota_keluarga')
        ->select('anggota_keluarga.mobile')
        ->where('anggota_keluarga.keluarga_id', $data->keluarga_id)
        ->where('anggota_keluarga.hub_keluarga_id', 1)
        ->first();

        $mobileKK = '62' . substr($kepalaKeluarga->mobile, 1);

        $whatsapp_msgKK = "Permohonan penambahan anggota keluarga yang anda ajukan atas nama ($data->nama) telah di setujui oleh Ketua RT.";

        try {
            $data->update($input);

            $inputDataMutasi = [
                'anggota_keluarga_id' => $data->anggota_keluarga_id,
                'status_mutasi_warga_id' => 1,
                'tanggal_mutasi' => $data->created_at,
                'keterangan' => '',
            ];
            MutasiWarga::create($inputDataMutasi);

            \Http::post("$url?key=$whatsappKey",[
                'id' => $mobileKK,
                'message' => $whatsapp_msgKK
            ]);

            \DB::commit();
            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }
}
