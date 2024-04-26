<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class SumberSuratRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
  
        $validations = [
            'asal_surat'   => ['required', 'max:255'],
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'asal_surat.required' => 'Asal Surat Tidak Boleh Kosong !',
            'asal_surat.max' => 'Asal Surat maksimal 255 karakter',
        ];
        
        return $returns;
    }
}
