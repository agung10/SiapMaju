<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class JenisSuratRwRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
  
        $validations = [
            'jenis_surat'   => ['required', 'max:255'],
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'jenis_surat.required' => 'Jenis Surat Tidak Boleh Kosong !',
            'jenis_surat.max' => 'Jenis Surat maksimal 255 karakter',
        ];
        
        return $returns;
    }
}
