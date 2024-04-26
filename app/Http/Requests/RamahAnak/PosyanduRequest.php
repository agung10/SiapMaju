<?php

namespace App\Http\Requests\RamahAnak;
use Illuminate\Foundation\Http\FormRequest;

class PosyanduRequest extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function rules() {
        $validations = [
            'anggota_keluarga_id' => ['required'],
            'tgl_input' => ['required'],
            'berat' => ['required'],
            'tinggi' => ['required'],
            'lingkar_kepala' => ['required'],
            'nilai_stunting' => ['required']
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'anggota_keluarga_id.required' => 'Nama Anak Tidak Boleh Kosong !',
            'tgl_input.required' => 'Tanggal Vaksinasi Tidak Boleh Kosong !',
            'berat.required' => 'Berat Badan Tidak Boleh Kosong !',
            'tinggi.required' => 'Tinggi Badan Tidak Boleh Kosong !',
            'lingkar_kepala.required' => 'Lingkar Kepala Tidak Boleh Kosong !',
            'nilai_stunting.required' => 'Nilai Stunting Tidak Boleh Kosong !'
        ];
        
        return $returns;
    }
}