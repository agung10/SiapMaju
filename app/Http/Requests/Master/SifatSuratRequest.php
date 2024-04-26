<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class SifatSuratRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
  
        $validations = [
            'sifat_surat'   => ['required', 'max:255'],
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'sifat_surat.required' => 'Sifat Surat Tidak Boleh Kosong !',
            'sifat_surat.max' => 'Sifat Surat maksimal 255 karakter',
        ];
        
        return $returns;
    }
}
