<?php

namespace App\Http\Requests\Surat;

use Illuminate\Foundation\Http\FormRequest;

class SuratMasukKeluarRwRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
  
        $validations = [
            'jenis_surat_rw_id'   => ['required'],
            'sifat_surat_id'   => ['required'],
            'no_surat'   => ['required', 'max:50'],
            'lampiran'   => ['max:50'],
            'hal'   => ['required','max:100'],
            'tgl_surat'   => ['required'],
            'asal_surat'   => ['required'],
            'nama_pengirim'   => ['required','max:100'],
            'tujuan_surat'   => ['required'],
            'nama_penerima'   => ['required','max:100'],
            'isi_surat'   => ['required','max:500'],
            'warga_id'   => ['required'],
            'upload_file'   => ['max:10000','mimes:jpg,jpeg,png,pdf'],
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'jenis_surat_rw_id.required' => 'Jenis Surat Tidak Boleh Kosong !',
            'sifat_surat_id.required' => 'Sifat Surat Tidak Boleh Kosong !',
            'no_surat.required' => 'No Surat Tidak Boleh Kosong !',
            'no_surat.max' => 'No Surat maksimal 50 karakter',
            'hal.required' => 'Hal Tidak Boleh Kosong !',
            'hal.max' => 'Hal Maksimal 100 Karakter!',
            'tgl_surat.required' => 'Tanggal Surat Tidak Boleh Kosong !',
            'asal_surat.required' => 'Tujuan Surat Tidak Boleh Kosong !',
            'nama_pengirim.required' => 'Nama Pengirim Tidak Boleh Kosong !',
            'nama_pengirim.max' => 'Nama Pengirim Maximal 100 Karakter',
            'nama_penerima.required' => 'Nama Penerima Tidak Boleh Kosong!',
            'nama_penerima.max' => 'Nama Penerima Maksimal 100 Karakter!',
            'tujuan_surat.required' => 'Tujuan Surat Tidak Boleh Kosong !',
            'isi_surat.required' => 'Isi Surat Tidak Boleh Kosong !',
            'warga_id.required' => 'Warga Tidak Boleh Kosong !',
            'upload_file.max' => 'Ukuran file maksimal 10MB',
            'upload_file.mimes' => 'Format file harus JPG,PNG,JPEG,PDF',
        ];
        
        return $returns;
    }
}
