<?php

namespace App\Http\Requests\Keluarga;
use Illuminate\Foundation\Http\FormRequest;

class MutasiWargaRequest extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function rules() {
        $validations = [
            'anggota_keluarga_id' => ['required'],
            'status_mutasi_warga_id' => ['required'],
            'tanggal_mutasi' => ['required']
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'anggota_keluarga_id.required' => 'Nama Warga Tidak Boleh Kosong !',
            'status_mutasi_warga_id.required' => 'Status Mutasi Warga Tidak Boleh Kosong !',
            'tanggal_mutasi.required' => 'Tanggal Mutasi Tidak Boleh Kosong !'
        ];
        
        return $returns;
    }
}