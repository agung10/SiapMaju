<?php

namespace App\Repositories\Kelurahan;
use App\Models\Surat\SuratPermohonan;

class SuratRepository
{
    public function __consturct() {}

    public function editLetter($letterID) {
        return SuratPermohonan::findOrFail($letterID);
    }

    public function updateLetter($request, $letterID) {
        $data = SuratPermohonan::findOrFail($letterID);
        \DB::beginTransaction();
        try {
            $letter['no_surat_kel'] = $request->no_surat_kel;
            $letter['isi_surat'] = $request->isi_surat;
            if ($request->hasFile('upload_surat_kelurahan')) {
                $document = str_replace(' ', '', date('Y-m-d H:i:s'));
                $document = str_replace('-', '', $document);
                $document = str_replace(':', '', $document);
                $letter['upload_surat_kelurahan'] = 'surat-kelurahan-' . $document . '.' . $request->upload_surat_kelurahan->getClientOriginalExtension();
                $request->upload_surat_kelurahan->move(public_path('uploaded_files/surat_kelurahan'), $letter['upload_surat_kelurahan']);
                if ($data->upload_surat_kelurahan) $this->deletingFiles('surat_kelurahan', $data->upload_surat_kelurahan);
            }
            $data->update($letter);
            \DB::commit();
            return $data;
        }
        catch (\Exception $e) {
            \DB::rollback();
            abort(403, $e->getMessage());
        }
    }

    public function checkingFiles($sourceFile) {
        $curl = curl_init($sourceFile);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return ((($code >= 200) && ($code < 300) || ($code == 400)) ? (true) : (false));
    }

    public function deletingFiles($folder, $file) {
        $sourceFile = asset('uploaded_files/' . $folder . '/' . $file);
        $existFile = $this->checkingFiles($sourceFile);

        if ($existFile) {
            return unlink(public_path('uploaded_files/' . $folder . '/' . $file));
        }

        return false;
    }

    public function dataTables() {
        $currentKelurahan = \DB::table('users')->select('anggota_keluarga.kelurahan_id')
                    ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                    ->where('user_id',\Auth::user()->user_id)
                    ->first()->kelurahan_id;
        $roleKelurahan = \helper::checkUserRole('kelurahan');

        $model = \DB::table('surat_permohonan')->select('no_surat', 'surat_permohonan_id', 'hal', 'nama_lengkap', 'status_upload', 'tgl_approve_kelurahan', 'tgl_approve_kasi', 'tgl_approve_sekel', 'tgl_approve_lurah', 'approve_lurah', 'upload_surat_kelurahan')
            ->whereNotNull('surat_permohonan.tgl_approve_kelurahan')
            ->when($roleKelurahan == true, function($q) use ($currentKelurahan){
                $q->where('surat_permohonan.kelurahan_id', $currentKelurahan);
            });

        $datatableButtons = method_exists(new SuratPermohonan, 'datatableButtons') ? SuratPermohonan::datatableButtons() : ['show', 'edit', 'destroy'];

        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('status', function($model) use ($datatableButtons) {
                if ($model->status_upload == 2) {
                    $status = 'Helpdesk Approved';
                    $approved = $model->tgl_approve_kelurahan;
                }
                else if ($model->status_upload == 3) {
                    $status = 'Kepala Seksi Approved';
                    $approved = $model->tgl_approve_kasi;
                }
                else if ($model->status_upload == 4) {
                    $status = 'Sekretaris Approved';
                    $approved = $model->tgl_approve_sekel;
                }
                else if ($model->status_upload == 5) {
                    $status = 'Lurah Approved';
                    $approved = $model->tgl_approve_lurah;
                }

                return ((($model->status_upload == 0) || ($model->status_upload == 1)) ? ('') : ('<label style="display:flex; flex-direction:column; justify-content:center;"><code style="margin-bottom:3px;">' . $status . '</code><code>' . date('d M Y', strtotime($approved)) . '</code></label>'));
            })
            ->addColumn('action', function($model) use ($datatableButtons) {
                $uploadBtn = '';
                if ($model->status_upload >= 2) {
                    $uploadBtn = '<a title="Upload Dokumen" class="btn btn-light-primary font-weight-bold mr-2 uploadDocument class' . $model->surat_permohonan_id . '" data-edit="' . route('Kelurahan.Surat.edit', \Crypt::encryptString($model->surat_permohonan_id)) . '">Upload</a>';
                }

                $previewBtn = '';
                if (($model->status_upload == 5) && ($model->upload_surat_kelurahan)) {
                    $previewBtn = '<a class="btn btn-light-primary font-weight-bold mr-2 btn-preview" data-path="' . asset('uploaded_files/surat_kelurahan/' . $model->upload_surat_kelurahan) . '">Hasil</a>';
                }
                
                return view('partials.buttons.cust-datatable', [
                    'show' => ((in_array('show', $datatableButtons)) ? (route('Kelurahan.Surat.show', \Crypt::encryptString($model->surat_permohonan_id))) : (null))
                ]) . $uploadBtn . $previewBtn;
                
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
}