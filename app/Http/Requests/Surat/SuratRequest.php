<?php

namespace App\Http\Requests\Surat;

use Illuminate\Foundation\Http\FormRequest;

class SuratRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
  
        $validations = [
            'anggota_keluarga_id'  => ['required'],
            'jenis_surat_id'       => ['required'],
            'tempat_lahir'         => ['required', 'max:50'],
            'tgl_lahir'            => ['required'],
            'bangsa'               => ['required','max:50'],
            'status_pernikahan_id' => ['required'],
            'agama_id'             => ['required'],
            'pekerjaan'            => ['required','max:50'],
            'no_kk'                => ['required','max:50'],
            'no_ktp'               => ['required','max:30'],
            'tgl_permohonan'       => ['required'],
            'lampiran1'            => ['max:10000','mimes:jpg,jpeg,png,pdf'],
            'lampiran2'            => ['max:10000','mimes:jpg,jpeg,png,pdf'],
            'lampiran3'            => ['max:10000','mimes:jpg,jpeg,png,pdf'],
            'upload_lampiran'      => ['max:2048','mimes:jpg,jpeg,png,pdf,gif'],
            'keperluan'            => ['required'],
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'anggota_keluarga_id.required' => 'Nama Warga Tidak Boleh Kosong !',
            'jenis_surat_id.required' => 'Jenis Surat Tidak Boleh Kosong !',
            'tempat_lahir.required' => 'Tempat Lahir Tidak Boleh Kosong !',
            'tempat_lahir.max' => 'Tempat Lahir maksimal 50 karakter',
            'tgl_lahir.required' => 'Tanggal Lahir Tidak Boleh Kosong !',
            'bangsa.required' => 'Bangsa Tidak Boleh Kosong !',
            'status_pernikahan_id.required' => 'Status Pernikahan Tidak Boleh Kosong !',
            'agama_id.required' => 'Agama Tidak Boleh Kosong !',
            'pekerjaan.required' => 'Pekerjaan Tidak Boleh Kosong !',
            'pekerjaan.max' => 'Pekerjaan maksimal 50 karakter',
            'no_kk.required' => 'No KK Tidak Boleh Kosong !',
            'no_kk.max' => 'No KK maksimal 50 karakter',
            'no_ktp.required' => 'No KTP Tidak Boleh Kosong !',
            'no_ktp.max' => 'No KTP maksimal 30 karakter',
            'tgl_permohonan.required' => 'Tanggal Permohonan Tidak Boleh Kosong !',
            'lampiran1.max' => 'Ukuran file maksimal 10MB',
            'lampiran1.mimes' => 'Format file harus JPG,PNG,JPEG,PDF',
            'lampiran2.max' => 'Ukuran file maksimal 10MB',
            'lampiran2.mimes' => 'Format file harus JPG,PNG,JPEG,PDF',
            'lampiran3.max' => 'Ukuran file maksimal 10MB',
            'lampiran3.mimes' => 'Format file harus JPG,PNG,JPEG,PDF',
            'upload_lampiran.max' => 'Ukuran file maksimal 2MB',
            'upload_lampiran.mimes' => 'Format file harus JPG,PNG,JPEG,PDF,GIF',
        ];
        
        return $returns;
    }
}
