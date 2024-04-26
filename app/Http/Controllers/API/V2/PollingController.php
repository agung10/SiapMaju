<?php

namespace App\Http\Controllers\API\V2;

use App\Models\Laporan\Laporan;
use App\Models\Polling\HasilPolling;
use App\Models\Polling\Pertanyaan;
use App\Models\Polling\PilihJawaban;
use App\Models\Master\RT;
use App\Models\Master\Keluarga\AnggotaKeluarga;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PollingController extends Controller
{
    public function __construct(Pertanyaan $pertanyaan, PilihJawaban $jawaban, AnggotaKeluarga $anggotaKeluarga, HasilPolling $hasilPolling)
    {
        $this->userLoggedIn    = auth('api')->user();
        $this->pertanyaan      = $pertanyaan;
        $this->jawaban         = $jawaban;
        $this->anggotaKeluarga = $anggotaKeluarga;
        $this->hasilPolling    = $hasilPolling;
        $this->kepalaKeluarga  = 1;
        $this->sebagaiIstri    = 3;
        $this->sebagaiSuami    = 4;
    }

    public function listPertanyaan(Request $request)
    {
        $wargaLoggedIn = $this->userLoggedIn->anggotaKeluarga;
        $ketuaRTWarga  = $this->anggotaKeluarga->where('rt_id', $wargaLoggedIn->rt_id)->where('is_rt', true)->first();
        $ketuaRWWarga  = $this->anggotaKeluarga->where('rw_id', $wargaLoggedIn->rw_id)->where('is_rw', true)->first();

        $pertanyaan = $this->pertanyaan
            ->select([
                'id_polling', 'isi_pertanyaan', 'close_date', 'created_at'
            ])
            ->where(function ($query) use ($ketuaRTWarga, $ketuaRWWarga) {
                $query->where('user_create', $ketuaRTWarga->user->user_id)
                    ->orWhere('pertanyaan.user_create', $ketuaRWWarga->user->user_id)
                    ->orWhere('pertanyaan.user_create', 1) // dibuat oleh admin
                    ->orWhere('pertanyaan.user_create', null); // belum diisi
            })
            ->when($request->id_polling, function ($query) use ($request) {
                $query->where('pertanyaan.id_polling', $request->id_polling);
            })
            ->orderBy('pertanyaan.created_at', 'DESC')
            ->with('answer:id_pilih_jawaban,id_polling,isi_pilih_jawaban')
            ->with(['result' => function ($q) use ($wargaLoggedIn) {
                $q->where('hasil_polling.anggota_keluarga_id', $wargaLoggedIn->anggota_keluarga_id);
            }]);

        $pertanyaan = $request->id_polling ? $pertanyaan->first() : $pertanyaan->get();

        return response()->json($pertanyaan);
    }

    public function listAnggota(Request $request)
    {
        try {
            $anggotaKeluarga = $this->userLoggedIn->anggotaKeluarga;
            $keluarga = $this->anggotaKeluarga
                ->select([
                    'anggota_keluarga.anggota_keluarga_id as id', 'anggota_keluarga.hub_keluarga_id', 'anggota_keluarga.keluarga_id', 'anggota_keluarga.nama as name', 'hub_keluarga.hubungan_kel', 'anggota_keluarga.rt_id', 'anggota_keluarga.tgl_lahir', 'anggota_keluarga.is_active'
                ])
                ->where('anggota_keluarga.is_active', true)
                ->join('hub_keluarga', 'hub_keluarga.hub_keluarga_id', 'anggota_keluarga.hub_keluarga_id')
                ->join('users', 'users.anggota_keluarga_id', 'anggota_keluarga.anggota_keluarga_id')
                ->orderBy('hub_keluarga.hub_keluarga_id');

            if ($anggotaKeluarga->hub_keluarga_id === $this->kepalaKeluarga) {
                $keluarga = $keluarga->where('anggota_keluarga.keluarga_id', $anggotaKeluarga->keluarga_id);
            } else {
                $keluarga = $keluarga->where('anggota_keluarga.anggota_keluarga_id', $anggotaKeluarga->anggota_keluarga_id);
            }

            if ($request->has('id_polling')) {
                $pollingId = $request->id_polling;
                $polling = Pertanyaan::findOrFail($pollingId);
                $rt = RT::findOrFail($polling->rt_id);
                if ($rt->rt === "DKM") {
                    $anggotaCanPolling = [$this->kepalaKeluarga, $this->sebagaiIstri, $this->sebagaiSuami];
                    $keluarga = $keluarga->whereIn('anggota_keluarga.hub_keluarga_id', $anggotaCanPolling);
                }
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }

        return response()->json($keluarga->get());
    }

    public function listJawaban(Request $request)
    {
        $pollingId           = $request->id_polling;
        $anggotaKeluarga     = $this->userLoggedIn->anggotaKeluarga;
        $listAnggotaKeluarga = $anggotaKeluarga->hub_keluarga_id === $this->kepalaKeluarga
            ? $anggotaKeluarga->allAnggotaKeluargaId()
            : [$anggotaKeluarga->anggota_keluarga_id];

        $jawaban = $this->hasilPolling
            ->whereIn('anggota_keluarga_id', $listAnggotaKeluarga)
            ->where('id_polling', $pollingId)
            ->with([
                'citizen:anggota_keluarga_id,nama',
                'jawaban:id_pilih_jawaban,id_polling,isi_pilih_jawaban'
            ])
            ->get();

        return response()->json($jawaban);
    }

    public function storeJawaban(Request $request)
    {
        $response = ['status' => false, 'msg'    => ''];

        \DB::beginTransaction();

        try {
            $anggotaKeluargaId = $request->anggota_keluarga_id;
            $pollingId         = $request->id_polling;
            $jawaban           = $request->id_pilih_jawaban;
            $keterangan        = $request->keterangan;


            $pollingClosed = $this->pertanyaan
                ->where('id_polling', $pollingId)
                ->whereDate('close_date', '<', date('Y-m-d'))
                ->exists();

            if ($pollingClosed) {
                $response['msg'] = 'Polling telah ditutup';

                return $response;
            }

            $answered = $this->hasilPolling
                ->where('id_polling', $pollingId)
                ->where('anggota_keluarga_id', $anggotaKeluargaId)
                ->first();

            if ($answered) {
                $answered->delete();
            }

            $storeJawaban = $this->hasilPolling->create([
                'id_polling'          => $pollingId,
                'id_pilih_jawaban'    => $jawaban,
                'anggota_keluarga_id' => $anggotaKeluargaId,
                'keterangan'          => $keterangan

            ]);

            $response['status'] = true;
            $response['msg']    = 'Jawaban berhasil disimpan';

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error($e);

            $response['msg'] = $e->getMessage();
        }

        return response()->json($response);
    }

    public function result(Request $request)
    {
        $pollingId = $request->id_polling;
        $isDKM     = null;
        $result    = [];
        $jawaban   = $this->jawaban->where('id_polling', $pollingId)->get();

        // check is polling dkm
        $polling = Pertanyaan::findOrFail($pollingId);
        $rt = RT::findOrFail($polling->rt_id);
        if ($rt->rt === "DKM") {
            $isDKM = true;
        }

        foreach ($jawaban as $j) {
            $arr['name'] = $j->isi_pilih_jawaban;

            if ($isDKM) {
                $arr['total'] = $this->hasilPolling
                    ->join('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'hasil_polling.anggota_keluarga_id')
                    ->where('anggota_keluarga.agama_id', 1) //muslim only
                    ->where('id_polling', $pollingId)
                    ->where('id_pilih_jawaban', $j->id_pilih_jawaban)
                    ->count();
            } else {
                $arr['total'] = $this->hasilPolling
                    ->where('id_polling', $pollingId)
                    ->where('id_pilih_jawaban', $j->id_pilih_jawaban)
                    ->count();
            }

            array_push($result, $arr);
        }

        return response()->json($result);
    }
}
