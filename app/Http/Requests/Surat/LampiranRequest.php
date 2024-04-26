<?php

namespace App\Http\Requests\Surat;

use Illuminate\Foundation\Http\FormRequest;

class LampiranRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $validations = [
            'jenis_surat_id' => ['required'],
            'nama_lampiran' => ['required'],
            'keterangan' => ['required'],
            'kategori' => ['required'],
            'status' => ['required'],
        ];

        return $validations;
    }

    public function messages()
    {
        $returns = [
            'jenis_surat_id.required' => 'Jenis Surat Tidak Boleh Kosong !',
            'nama_lampiran.required' => 'Nama Lampiran Tidak Boleh Kosong !',
            'keterangan.required' => 'Keterangan Tidak Boleh Kosong !',
            'kategori.required' => 'Kategori Tidak Boleh Kosong !',
            'status.required' => 'Status Tidak Boleh Kosong !',
        ];

        return $returns;
    }
}
