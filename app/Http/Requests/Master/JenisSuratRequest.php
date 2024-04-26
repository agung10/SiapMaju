<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class JenisSuratRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $encryptID = $this->route()->JenisSurat;

        $id = $encryptID ? \Crypt::decryptString($encryptID) : null;

        $validations = [
            'jenis_permohonan'   => ['required', 'max:100'],
            'kd_surat' => $id ? ['required', 'numeric', 'max:2', 'unique:jenis_surat,kd_surat,'.$id.',jenis_surat_id']
                              : ['required', 'numeric', 'max:2', 'unique:jenis_surat,kd_surat'],
            'keterangan'   => ['required', 'max:100'],
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'jenis_permohonan.required' => 'Jenis Permohonan Tidak Boleh Kosong !',
            'jenis_permohonan.max'      => 'Jenis Permohonan maksimal 100 karakter!',
            'kd_surat.required'         => 'Kode Surat Tidak Boleh Kosong',
            'kd_surat.numeric'          => 'Kode Surat hanya boleh terdiri dari angka',
            'kd_surat.max'              => 'Kode Surat hanya boleh terdiri dari 2 karakter',
            'kd_surat.unique'           => 'Kode Surat Sudah Digunakan !',
            'kd_surat.required'         => 'Kode Surat Tidak Boleh Kosong !',
            'keterangan.required'       => 'Keterangan Tidak Boleh Kosong !',
            'keterangan.max'            => 'Keterangan maksimal 100 karakter!',
        ];
        
        return $returns;
    }
}
