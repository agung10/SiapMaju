<?php

namespace App\Http\Requests\SumberBiaya;

use Illuminate\Foundation\Http\FormRequest;

class KatSumberBiayaRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
  
        $validations = [
            'nama_sumber'    => ['required'],
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'nama_sumber.required' => 'Nama Sumber Biaya Tidak Boleh Kosong !',
        ];
        
        return $returns;
    }
}
