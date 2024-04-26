<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class AnggotaKeluargaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $validations = [
            'nama'   => ['required','max:255'],
            'mobile' => ['required'],
            'tgl_lahir' => ['required'],
            'jenis_kelamin' => ['required'],
        ];
    
        return $validations;
    }

    public function messages() {
        $returns = [
            'nama.required' => 'Nama Tidak Boleh Kosong !',
            'nama.max' => 'Nama Maksimal 255 Karakter',
            'mobile.required' => 'Mobile Tidak Boleh Kosong !',
            'tgl_lahir.required' => 'Tanggal Lahir Tidak Boleh Kosong !',
            'jenis_kelamin.required' => 'Jenis Kelamin Tidak Boleh Kosong !',
        ];
        
        return $returns;
    }
}
