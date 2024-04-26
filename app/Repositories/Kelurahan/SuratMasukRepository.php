<?php

namespace App\Repositories\Kelurahan;
use App\Models\Surat\{ SuratPermohonan };

class SuratMasukRepository
{
    public function __construct(SuratPermohonan $_SuratPermohonan) {
       $this->surat_permohonan = $_SuratPermohonan;
    }

    public function updateHelpdesk($request, $letterID) {
        \DB::beginTransaction();
        try {
            $data = $this->surat_permohonan->findOrFail($letterID);
            if ($request->catatan_kelurahan) {
                $requestLetter['status_upload'] = 1;
                $requestLetter['catatan_kelurahan'] = ((!empty($data->catatan_kelurahan)) ? (nl2br($data->catatan_kelurahan) . PHP_EOL . PHP_EOL . '[Helpdesk]:' . PHP_EOL . str_replace('<br />', PHP_EOL, nl2br($request->catatan_kelurahan))) : ('[Helpdesk]:' . PHP_EOL . str_replace('<br />', PHP_EOL, nl2br($request->catatan_kelurahan))));

                $url = 'https://dev2.kamarkerja.com:3333/message/text';
                $response = @get_headers($url);

                // if cant reach url
                if (!$response) return redirect()->back()->with('error','Maaf terjadi kesalahan'); 

                $whatsappKey = \DB::table('whatsapp_key')
                ->select('whatsapp_key')
                ->first()
                ->whatsapp_key ?? null;

                if (!$whatsappKey) return redirect()->back()->with('error', 'No Whatsapp belum disandingkan'); 

                $mobileW = '62' . substr($data->anggota_keluarga->mobile, 1);
                $whatsapp_msgW = "[INFORMASI SIAPMAJU] Diinformasikan adanya catatan dari kelurahan untuk surat permohonan Bapak/Ibu. Mohon untuk kembali membuka aplikasi SIAPMAJU. Pesan dibuat secara otomatis, mohon untuk tidak membalas pesan ini.";
            
                \Http::post("$url?key=$whatsappKey",[
                    'id' => $mobileW,
                    'message' => $whatsapp_msgW
                ]);
            }
            else {
                $requestLetter['tgl_approve_kelurahan'] = date('Y-m-d H:i:s');
                $requestLetter['petugas_kelurahan_id'] = \Auth::user()->user_id;
                $requestLetter['status_upload'] = 2;
            }
            $data->update($requestLetter);
            \DB::commit();
            return redirect()->route('Kelurahan.SuratMasuk.index')->with('success', 'Data telah disimpan!');
        }
        catch (\Exception $e) {
            \DB::rollback();
            abort(403, $e->getMessage());
        }
    }

    public function storeLetterContent($request, $letterID) {
        \DB::beginTransaction();
        try {
            $data = $this->surat_permohonan->findOrFail($letterID);
            $requestLetter['no_surat_kel'] = $request->letterNumb;
            $requestLetter['isi_surat'] = $request->letterContent;
            $data->update($requestLetter);
            \DB::commit();
        }
        catch (\Exception $e) {
            \DB::rollback();
            abort(403, $e->getMessage());
        }
    }

    public function dataTablesNoSearch() {
        $currentKelurahan = \DB::table('users')->select('anggota_keluarga.kelurahan_id')
                    ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                    ->where('user_id',\Auth::user()->user_id)
                    ->first()->kelurahan_id;
        $roleKelurahan = \helper::checkUserRole('kelurahan');

        $model = \DB::table('surat_permohonan')
            ->select('surat_permohonan.no_surat', 'surat_permohonan.surat_permohonan_id', 'surat_permohonan.hal', 'surat_permohonan.nama_lengkap')
            ->where('surat_permohonan.status_upload', 0)
            ->whereNotNull('surat_permohonan.no_surat')
            ->whereNotNull('surat_permohonan.tgl_approve_rt')
            ->whereNotNull('surat_permohonan.tgl_approve_rw')
            ->whereNull('surat_permohonan.tgl_approve_kelurahan')
            ->when($roleKelurahan == true, function($q) use ($currentKelurahan){
                $q->where('surat_permohonan.kelurahan_id', $currentKelurahan);
            });

        $datatableButtons = method_exists(new SuratPermohonan, 'datatableButtons') ? SuratPermohonan::datatableButtons() : ['show', 'edit', 'destroy'];

        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action',function($model) use($datatableButtons){
                return view('partials.buttons.cust-datatable', [
                    'show' => in_array("show", $datatableButtons ) ? route('Kelurahan.SuratMasuk'.'.show', \Crypt::encryptString($model->surat_permohonan_id)) : null,
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function dataTables($no_surat) {
        $currentKelurahan = \DB::table('users')->select('anggota_keluarga.kelurahan_id')
                    ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                    ->where('user_id',\Auth::user()->user_id)
                    ->first()->kelurahan_id;
        $roleKelurahan = \helper::checkUserRole('kelurahan');

        $model = \DB::table('surat_permohonan')
            ->select('surat_permohonan.no_surat', 'surat_permohonan.surat_permohonan_id', 'surat_permohonan.hal', 'surat_permohonan.nama_lengkap')
            ->where(['surat_permohonan.no_surat' => $no_surat, 'surat_permohonan.status_upload' => 0])
            ->whereNotNull('surat_permohonan.no_surat')
            ->whereNotNull('surat_permohonan.tgl_approve_rt')
            ->whereNotNull('surat_permohonan.tgl_approve_rw')
            ->whereNull('surat_permohonan.tgl_approve_kelurahan')
            ->when($roleKelurahan == true, function($q) use ($currentKelurahan){
                $q->where('surat_permohonan.kelurahan_id', $currentKelurahan);
            })
            ->get();

        $datatableButtons = method_exists(new SuratPermohonan, 'datatableButtons') ? SuratPermohonan::datatableButtons() : ['show', 'edit', 'destroy'];

        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action',function($model) use($datatableButtons){
                return view('partials.buttons.cust-datatable', [
                    'show' => in_array("show", $datatableButtons ) ? route('Kelurahan.SuratMasuk'.'.show', \Crypt::encryptString($model->surat_permohonan_id)) : null,
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getRequestLetterData($letterID) {
        return $this->surat_permohonan->select('surat_permohonan_id', 'nama_lengkap', 'jenis_surat_id', 'tempat_lahir', 'tgl_lahir', 'bangsa', 'agama_id', 'status_pernikahan_id', 'pekerjaan', 'no_kk', 'no_ktp', 'tgl_permohonan', 'lampiran1', 'lampiran2', 'lampiran3', 'province_id', 'city_id', 'subdistrict_id', 'kelurahan_id', 'rw_id', 'rt_id', 'alamat', 'keperluan')
            ->findOrFail($letterID);
    }

    public function updateHeadOfSection($request, $letterID) {
        \DB::beginTransaction();
        try {
            $data = $this->surat_permohonan->findOrFail($letterID);
            $requestLetter['tgl_approve_kasi'] = date('Y-m-d H:i:s');
            $requestLetter['kasi_id'] = \Auth::user()->user_id;
            $requestLetter['status_upload'] = 3;
            $data->update($requestLetter);
            \DB::commit();
            return redirect()->route('Kelurahan.Kepala-Seksi.index')->with('success', 'Data telah disimpan!');
        }
        catch (\Exception $e) {
            \DB::rollback();
            abort(403, $e->getMessage());
        }
    }

    public function dataTablesKepalaSeksiNoSearch() {
        $currentKelurahan = \DB::table('users')->select('anggota_keluarga.kelurahan_id')
                    ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                    ->where('user_id',\Auth::user()->user_id)
                    ->first()->kelurahan_id;
        $roleKelurahan = \helper::checkUserRole('kelurahan');

        $model = $this->surat_permohonan->select('no_surat', 'surat_permohonan_id', 'hal', 'nama_lengkap')
            ->where('status_upload', 2)
            ->whereNotNull(['tgl_approve_rw', 'tgl_approve_rt'])
            ->whereNull(['tgl_approve_kasi', 'tgl_approve_sekel', 'tgl_approve_lurah'])
            ->when($roleKelurahan == true, function($q) use ($currentKelurahan){
                $q->where('surat_permohonan.kelurahan_id', $currentKelurahan);
            });

        $datatableButtons = method_exists(new SuratPermohonan, 'datatableButtons') ? SuratPermohonan::datatableButtons() : ['show', 'edit', 'destroy'];

        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action',function($model) use($datatableButtons){
                return view('partials.buttons.cust-datatable', [
                    'show' => ((in_array('show', $datatableButtons)) ? (route('Kelurahan.Kepala-Seksi.show', \Crypt::encryptString($model->surat_permohonan_id))) : (null)),
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function dataTablesKepalaSeksi($request) {
        $currentKelurahan = \DB::table('users')->select('anggota_keluarga.kelurahan_id')
                    ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                    ->where('user_id',\Auth::user()->user_id)
                    ->first()->kelurahan_id;
        $roleKelurahan = \helper::checkUserRole('kelurahan');

        $model = $this->surat_permohonan->select('no_surat', 'surat_permohonan_id', 'hal', 'nama_lengkap')
            ->where(['no_surat' => $request->numb, 'status_upload' => 2])
            ->whereNotNull(['tgl_approve_rw', 'tgl_approve_rt'])
            ->whereNull(['tgl_approve_kasi', 'tgl_approve_sekel', 'tgl_approve_lurah'])
            ->when($roleKelurahan == true, function($q) use ($currentKelurahan){
                $q->where('surat_permohonan.kelurahan_id', $currentKelurahan);
            })
            ->get();

        $datatableButtons = method_exists(new SuratPermohonan, 'datatableButtons') ? SuratPermohonan::datatableButtons() : ['show', 'edit', 'destroy'];

        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action',function($model) use($datatableButtons){
                return view('partials.buttons.cust-datatable', [
                    'show' => ((in_array('show', $datatableButtons)) ? (route('Kelurahan.Kepala-Seksi.show', \Crypt::encryptString($model->surat_permohonan_id))) : (null)),
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function updateSecretary($request, $letterID) {
        \DB::beginTransaction();
        try {
            $data = $this->surat_permohonan->findOrFail($letterID);
            $requestLetter['tgl_approve_sekel'] = date('Y-m-d H:i:s');
            $requestLetter['sekel_id'] = \Auth::user()->user_id;
            $requestLetter['status_upload'] = 4;
            $data->update($requestLetter);
            \DB::commit();
            return redirect()->route('Kelurahan.Sekretaris.index')->with('success', 'Data telah disimpan!');
        }
        catch (\Exception $e) {
            \DB::rollback();
            abort(403, $e->getMessage());
        }
    }

    public function dataTablesSecretaryNoSearch() {
        $currentKelurahan = \DB::table('users')->select('anggota_keluarga.kelurahan_id')
                    ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                    ->where('user_id',\Auth::user()->user_id)
                    ->first()->kelurahan_id;
        $roleKelurahan = \helper::checkUserRole('kelurahan');

        $model = $this->surat_permohonan->select('no_surat', 'surat_permohonan_id', 'hal', 'nama_lengkap')
            ->where('status_upload', 3)
            ->whereNotNull(['tgl_approve_rw', 'tgl_approve_rt', 'tgl_approve_kasi'])
            ->whereNull(['tgl_approve_sekel', 'tgl_approve_lurah'])
            ->when($roleKelurahan == true, function($q) use ($currentKelurahan){
                $q->where('surat_permohonan.kelurahan_id', $currentKelurahan);
            });

        $datatableButtons = method_exists(new SuratPermohonan, 'datatableButtons') ? SuratPermohonan::datatableButtons() : ['show', 'edit', 'destroy'];

        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action',function($model) use($datatableButtons){
                return view('partials.buttons.cust-datatable', [
                    'show' => ((in_array('show', $datatableButtons)) ? (route('Kelurahan.Sekretaris.show', \Crypt::encryptString($model->surat_permohonan_id))) : (null)),
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function dataTablesSecretary($request) {
        $currentKelurahan = \DB::table('users')->select('anggota_keluarga.kelurahan_id')
                    ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                    ->where('user_id',\Auth::user()->user_id)
                    ->first()->kelurahan_id;
        $roleKelurahan = \helper::checkUserRole('kelurahan');

        $model = $this->surat_permohonan->select('no_surat', 'surat_permohonan_id', 'hal', 'nama_lengkap')
            ->where(['no_surat' => $request->numb, 'status_upload' => 3])
            ->whereNotNull(['tgl_approve_rw', 'tgl_approve_rt', 'tgl_approve_kasi'])
            ->whereNull(['tgl_approve_sekel', 'tgl_approve_lurah'])
            ->when($roleKelurahan == true, function($q) use ($currentKelurahan){
                $q->where('surat_permohonan.kelurahan_id', $currentKelurahan);
            })
            ->get();

        $datatableButtons = method_exists(new SuratPermohonan, 'datatableButtons') ? SuratPermohonan::datatableButtons() : ['show', 'edit', 'destroy'];

        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action',function($model) use($datatableButtons){
                return view('partials.buttons.cust-datatable', [
                    'show' => ((in_array('show', $datatableButtons)) ? (route('Kelurahan.Sekretaris.show', \Crypt::encryptString($model->surat_permohonan_id))) : (null)),
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function updateGroove($request, $letterID) {
        \DB::beginTransaction();
        try {
            $data = $this->surat_permohonan->join('jenis_surat', 'jenis_surat.jenis_surat_id', 'surat_permohonan.jenis_surat_id')->join('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','surat_permohonan.anggota_keluarga_id')->findOrFail($letterID);
            $requestLetter['tgl_approve_lurah'] = date('Y-m-d H:i:s');
            $requestLetter['approve_lurah'] = \Auth::user()->user_id;
            $requestLetter['status_upload'] = 5;
            $data->update($requestLetter);

            $url = 'https://dev2.kamarkerja.com:3333/message/text';
            $response = @get_headers($url);

            // if cant reach url
            if (!$response) return redirect()->back()->with('error','Maaf terjadi kesalahan'); 

            $whatsappKey = \DB::table('whatsapp_key')
            ->select('whatsapp_key')
            ->first()
            ->whatsapp_key ?? null;

            if (!$whatsappKey) return redirect()->back()->with('error', 'No Whatsapp belum disandingkan'); 

            $mobile = '62' . substr($data->mobile, 1);
            
            $whatsapp_msg = "[INFORMASI SIAPMAJU], Surat keterangan [$data->jenis_permohonan] sudah selesai dari Kelurahan. Mohon untuk memeriksa status surat pada aplikasi";
        
            \Http::post("$url?key=$whatsappKey",[
                'id' => $mobile,
                'message' => $whatsapp_msg
            ]);

            \DB::commit();
            return redirect()->route('Kelurahan.Lurah.index')->with('success', 'Data telah disimpan!');
        }
        catch (\Exception $e) {
            \DB::rollback();
            abort(403, $e->getMessage());
        }
    }

    public function dataTablesGrooveNoSearch() {
        $currentKelurahan = \DB::table('users')->select('anggota_keluarga.kelurahan_id')
                    ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                    ->where('user_id',\Auth::user()->user_id)
                    ->first()->kelurahan_id;
        $roleKelurahan = \helper::checkUserRole('kelurahan');

        $model = $this->surat_permohonan->select('no_surat', 'surat_permohonan_id', 'hal', 'nama_lengkap')
            ->where('status_upload', 4)
            ->whereNotNull(['tgl_approve_rw', 'tgl_approve_rt', 'tgl_approve_kasi', 'tgl_approve_sekel'])
            ->whereNull(['tgl_approve_lurah'])
            ->when($roleKelurahan == true, function($q) use ($currentKelurahan){
                $q->where('surat_permohonan.kelurahan_id', $currentKelurahan);
            });

        $datatableButtons = method_exists(new SuratPermohonan, 'datatableButtons') ? SuratPermohonan::datatableButtons() : ['show', 'edit', 'destroy'];

        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action',function($model) use($datatableButtons){
                return view('partials.buttons.cust-datatable', [
                    'show' => ((in_array('show', $datatableButtons)) ? (route('Kelurahan.Lurah.show', \Crypt::encryptString($model->surat_permohonan_id))) : (null)),
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function dataTablesGroove($request) {
        $currentKelurahan = \DB::table('users')->select('anggota_keluarga.kelurahan_id')
                    ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                    ->where('user_id',\Auth::user()->user_id)
                    ->first()->kelurahan_id;
        $roleKelurahan = \helper::checkUserRole('kelurahan');

        $model = $this->surat_permohonan->select('no_surat', 'surat_permohonan_id', 'hal', 'nama_lengkap')
            ->where(['no_surat' => $request->numb, 'status_upload' => 4])
            ->whereNotNull(['tgl_approve_rw', 'tgl_approve_rt', 'tgl_approve_kasi', 'tgl_approve_sekel'])
            ->whereNull(['tgl_approve_lurah'])
            ->when($roleKelurahan == true, function($q) use ($currentKelurahan){
                $q->where('surat_permohonan.kelurahan_id', $currentKelurahan);
            })
            ->get();

        $datatableButtons = method_exists(new SuratPermohonan, 'datatableButtons') ? SuratPermohonan::datatableButtons() : ['show', 'edit', 'destroy'];

        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action',function($model) use($datatableButtons){
                return view('partials.buttons.cust-datatable', [
                    'show' => ((in_array('show', $datatableButtons)) ? (route('Kelurahan.Lurah.show', \Crypt::encryptString($model->surat_permohonan_id))) : (null)),
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}