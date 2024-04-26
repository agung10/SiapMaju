<?php

namespace App\Http\Requests\Keluarga;

use Illuminate\Foundation\Http\FormRequest;

class HubunganKeluargaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $validations = [
            'hubungan_kel'   => ['required','max:255']
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'hubungan_kel.required' => 'Hubungan Keluarga Tidak Boleh Kosong !',
            'hubungan_kel.max' => 'Hubungan Keluarga Maksimal 255 Karakter !'
        ];
        
        return $returns;
    }
}
